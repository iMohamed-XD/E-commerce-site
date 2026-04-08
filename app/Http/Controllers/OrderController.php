<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $shop = Auth::user()->shop;
        if (!$shop) {
            return redirect()->route('dashboard');
        }

        $status = $request->string('status')->toString();
        if (!in_array($status, ['all', 'pending', 'done', 'canceled', 'archived'], true)) {
            $status = 'pending';
        }

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 15, 20, 25, 30], true)) {
            $perPage = 10;
        }

        $field = $request->string('field')->toString();
        $value = trim((string) $request->input('value', ''));

        $ordersQuery = $shop->orders()->with('items.product')->latest();

        $this->applyStatusFilter($ordersQuery, $status);
        $this->applyAttributeFilter($ordersQuery, $field, $value);

        $orders = $ordersQuery->paginate($perPage)->withQueryString();

        return view('orders.index', compact('orders', 'shop', 'status', 'perPage', 'field', 'value'));
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
                $order->loadMissing('items.product');
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('quantity_available', (int) $item->quantity);
                    }
                }
            }

            $order->update(['status' => $newStatus]);
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
            'promo_code_used',
            'payment_method',
            'total_amount',
            'status',
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

        if ($field === 'total_amount') {
            $query->where('total_amount', (float) $value);
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

        if ($field === 'created_at') {
            $query->whereDate('created_at', $value);
            return;
        }

        $query->where($field, 'like', '%' . $value . '%');
    }
}
