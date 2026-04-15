<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\ProductStockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function __construct(
        protected ProductStockService $productStockService,
    ) {
    }

    public function index(Request $request)
    {
        $shop = Auth::user()->shop;
        if (!$shop) {
            return redirect()->route('dashboard');
        }

        $status = $request->string('status')->toString();
        if (!in_array($status, ['all', 'pending', 'done', 'canceled', 'archived', 'archived_done', 'archived_canceled'], true)) {
            $status = 'pending';
        }

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 15, 20, 25, 30], true)) {
            $perPage = 10;
        }

        $field = $request->string('field')->toString();
        $value = trim((string) $request->input('value', ''));

        $ordersQuery = $shop->orders()->with(['items.product', 'items.productOption'])->latest();

        $this->applyStatusFilter($ordersQuery, $status);
        $this->applyAttributeFilter($ordersQuery, $field, $value);

        $orders = $ordersQuery->paginate($perPage)->withQueryString();

        $orders->getCollection()->transform(function (Order $order) {
            $normalizedStatus = $this->normalizeStatus($order->status);
            $buyerLocation = $order->buyer_location_text ?: $order->buyer_address;

            $statusConfig = [
                'pending' => ['label' => 'قيد الانتظار', 'classes' => 'bg-[#d4af37]/15 text-[#a07c1e] border-[#d4af37]/35'],
                'done' => ['label' => 'مكتمل', 'classes' => 'bg-green-50 text-green-700 border-green-200'],
                'canceled' => ['label' => 'ملغي', 'classes' => 'bg-red-50 text-red-600 border-red-200'],
                'archived' => ['label' => 'مؤرشف', 'classes' => 'bg-[#0d1b4b]/8 text-[#0d1b4b]/70 border-[#0d1b4b]/20'],
            ];

            $statusMeta = $statusConfig[$normalizedStatus] ?? $statusConfig['pending'];

            $archivedFromLabel = null;
            if ($normalizedStatus === 'archived') {
                $archivedFromLabel = match ($order->archived_from_status) {
                    'done', 'completed' => 'مكتمل',
                    'canceled', 'cancelled' => 'ملغي',
                    default => 'غير معروف',
                };
            }

            return [
                'id' => $order->id,
                'status' => $normalizedStatus,
                'statusLabel' => $statusMeta['label'],
                'statusClasses' => $statusMeta['classes'],
                'archivedFromLabel' => $archivedFromLabel,
                'promoCodeUsed' => $order->promo_code_used,
                'deliveryEstimateLabel' => $order->delivery_estimate ? $order->delivery_estimate_label : null,
                'createdAt' => $order->created_at->format('Y-m-d H:i'),

                'buyerName' => $order->buyer_name,
                'buyerEmail' => $order->buyer_email,
                'buyerPhone' => $order->buyer_phone,
                'buyerLocation' => $buyerLocation,
                'buyerCity' => $order->buyer_city,
                'sellerCitySnapshot' => $order->seller_city_snapshot,
                'paymentMethodLabel' => $order->payment_method === 'shamcash' ? 'شام كاش' : 'الدفع عند الاستلام',
                'shamcashTransactionNumber' => $order->payment_method === 'shamcash' ? $order->shamcash_transaction_number : null,

                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'productName' => $item->product ? $item->product->name : 'منتج محذوف',
                        'optionLabel' => $item->product_option_label,
                        'quantity' => $item->quantity,
                        'unitPriceUsd' => $item->resolvedUnitPriceUsd(),
                        'unitPriceSyp' => $item->resolvedUnitPriceSyp(),
                    ];
                })->values()->all(),

                'subtotalUsd' => $order->productSubtotalUsdValue(),
                'subtotalSyp' => $order->productSubtotalSypValue(),
                'discountUsd' => $order->discountAmountUsdValue(),
                'discountSyp' => $order->discountAmountSypValue(),
                'discountedProductsSubtotalUsd' => $order->discountedProductsSubtotalUsdValue(),
                'discountedProductsSubtotalSyp' => $order->discountedProductsSubtotalSypValue(),
                'deliveryFeeUsd' => $order->deliveryFeeUsdValue(),
                'deliveryFeeSyp' => $order->deliveryFeeSypValue(),
                'finalTotalUsd' => $order->finalTotalUsdValue(),
                'finalTotalSyp' => $order->finalTotalSypValue(),

                'canMarkDone' => $normalizedStatus === 'pending',
                'canCancel' => $normalizedStatus === 'pending',
                'canArchive' => in_array($normalizedStatus, ['done', 'canceled'], true),
                'updateStatusUrl' => route('orders.updateStatus', $order),
            ];
        });

        return Inertia::render('Orders/Index', [
            'shop' => [
                'name' => $shop->name,
            ],
            'orders' => $orders->toArray(),
            'filters' => [
                'status' => $status,
                'perPage' => $perPage,
                'field' => $field,
                'value' => $value,
            ],
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        Gate::authorize('manage', $order);

        $request->validate([
            'status' => 'required|in:pending,done,canceled,archived,completed,cancelled',
        ]);

        $newStatus = $this->normalizeStatus($request->status);
        $oldStatus = $this->normalizeStatus($order->status);

        $allowedTransitions = [
            'pending' => ['done', 'canceled'],
            'done' => ['archived'],
            'canceled' => ['archived'],
            'archived' => [],
        ];

        if (!in_array($newStatus, $allowedTransitions[$oldStatus] ?? [], true)) {
            return redirect()->route('orders.index')->withErrors([
                'status' => 'الانتقال المطلوب بين الحالات غير مسموح.',
            ]);
        }

        DB::transaction(function () use ($order, $oldStatus, $newStatus) {
            if ($oldStatus !== 'canceled' && $newStatus === 'canceled') {
                $order->loadMissing(['items.product', 'items.productOption']);

                foreach ($order->items as $item) {
                    $this->productStockService->restoreOrderItem($item);
                }
            }

            $updatePayload = ['status' => $newStatus];
            if ($newStatus === 'archived') {
                $updatePayload['archived_from_status'] = $oldStatus;
            } elseif ($newStatus !== 'archived') {
                $updatePayload['archived_from_status'] = null;
            }

            $order->update($updatePayload);
        });

        return redirect()->route('orders.index')->with('success', 'تم تحديث حالة الطلب بنجاح!');
    }

    private function normalizeStatus(string $status): string
    {
        return match ($status) {
            'completed' => 'done',
            'cancelled' => 'canceled',
            default => $status,
        };
    }

    private function applyStatusFilter($query, string $status): void
    {
        if ($status === 'all') {
            return;
        }

        if ($status === 'done') {
            $query->whereIn('status', ['done', 'completed']);

            return;
        }

        if ($status === 'canceled') {
            $query->whereIn('status', ['canceled', 'cancelled']);

            return;
        }

        if ($status === 'archived_done') {
            $query->where('status', 'archived')->whereIn('archived_from_status', ['done', 'completed']);

            return;
        }

        if ($status === 'archived_canceled') {
            $query->where('status', 'archived')->whereIn('archived_from_status', ['canceled', 'cancelled']);

            return;
        }

        $query->where('status', $status);
    }

    private function applyAttributeFilter($query, string $field, string $value): void
    {
        if ($value === '') {
            return;
        }

        $allowedFields = [
            'id',
            'buyer_name',
            'buyer_email',
            'buyer_phone',
            'buyer_address',
            'buyer_city',
            'delivery_estimate',
            'promo_code_used',
            'payment_method',
            'total_amount',
            'final_total_usd',
            'final_total_syp',
            'status',
            'archived_from_status',
            'shamcash_transaction_number',
            'created_at',
        ];

        if (!in_array($field, $allowedFields, true)) {
            return;
        }

        if ($field === 'id') {
            $query->where('id', (int) $value);

            return;
        }

        if (in_array($field, ['total_amount', 'final_total_usd', 'final_total_syp'], true)) {
            $query->where($field, (float) $value);

            return;
        }

        if ($field === 'status') {
            $normalized = $this->normalizeStatus($value);
            if ($normalized === 'done') {
                $query->whereIn('status', ['done', 'completed']);
            } elseif ($normalized === 'canceled') {
                $query->whereIn('status', ['canceled', 'cancelled']);
            } else {
                $query->where('status', $normalized);
            }

            return;
        }

        if ($field === 'archived_from_status') {
            $normalized = $this->normalizeStatus($value);
            if ($normalized === 'done') {
                $query->whereIn('archived_from_status', ['done', 'completed']);
            } elseif ($normalized === 'canceled') {
                $query->whereIn('archived_from_status', ['canceled', 'cancelled']);
            } else {
                $query->where('archived_from_status', $normalized);
            }

            return;
        }

        if ($field === 'created_at') {
            $query->whereDate('created_at', $value);

            return;
        }

        $query->where($field, 'like', '%' . $value . '%');
    }
}
