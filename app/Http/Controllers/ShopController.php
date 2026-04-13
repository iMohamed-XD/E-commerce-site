<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shop;
use App\Services\CheckoutPricingService;
use App\Services\ExchangeRateService;
use App\Services\LocationService;
use App\Services\ProductStockService;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ShopController extends Controller
{
    public function __construct(
        protected LocationService $locationService,
        protected ProductStockService $productStockService,
        protected CheckoutPricingService $checkoutPricingService,
        protected ExchangeRateService $exchangeRateService,
    ) {
    }

    public function store(StoreShopRequest $request)
    {
        $publicDisk = Storage::disk('public');
        $validated = $request->validated();
        $locationData = $this->locationService->normalizeLocationPayload($validated);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('shops/logos', 'public');
        } elseif ($request->filled('cropped_logo')) {
            $logoPath = $this->storeBase64Image($publicDisk, $request->string('cropped_logo')->toString(), 'shops/logos');
        }

        $heroImagePath = null;
        if ($request->hasFile('hero_image')) {
            $heroImagePath = $request->file('hero_image')->store('shops/heroes', 'public');
        }

        $shamcashQrPath = null;
        if ($request->hasFile('shamcash_qr')) {
            $shamcashQrPath = $request->file('shamcash_qr')->store('shops/shamcash', 'public');
        }

        $shamcashAccountNumber = trim((string) ($validated['shamcash_account_number'] ?? '')) ?: null;
        $hasShamcashSetup = !empty($shamcashAccountNumber) && !empty($shamcashQrPath);
        $shamcashIsActive = !empty($validated['shamcash_is_active']) && $hasShamcashSetup;

        Shop::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'delivery_fee_usd' => round(max(0, (float) ($validated['delivery_fee_usd'] ?? 0)), 2),
            'location_text' => $locationData['location_text'],
            'city' => $locationData['city'],
            'same_day_delivery_enabled' => (bool) ($validated['same_day_delivery_enabled'] ?? false),
            'logo_path' => $logoPath,
            'hero_image_path' => $heroImagePath,
            'color' => $validated['color'] ?? 'navy',
            'shamcash_account_number' => $shamcashAccountNumber,
            'shamcash_qr_path' => $shamcashQrPath,
            'shamcash_is_active' => $shamcashIsActive,
        ]);

        return redirect()->route('dashboard')->with('success', 'تم إنشاء متجرك بنجاح!');
    }

    public function show($slug)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();
        $usdToSypRate = $this->exchangeRateService->getCurrentUsdToSypRate();
        $search = trim((string) request('search', ''));
        $canonicalSellerCity = $this->locationService->canonicalizeCity($shop->city);

        $productsQuery = $shop->products()
            ->with(['category', 'productOptions'])
            ->where('is_active', true)
            ->orderByDesc('created_at');

        if ($search !== '') {
            $productsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $products = $productsQuery->paginate(20)->withQueryString();

        $stockProducts = $shop->products()
            ->with('productOptions')
            ->where('is_active', true)
            ->get();

        $stockByProduct = $stockProducts
            ->mapWithKeys(function ($product) {
                $totalStock = $product->has_options
                    ? (int) $product->productOptions->sum('quantity')
                    : (int) $product->quantity_available;

                return [$product->id => $totalStock];
            })
            ->all();

        $optionStockById = $stockProducts
            ->flatMap(fn ($product) => $product->productOptions->mapWithKeys(fn ($option) => [$option->id => (int) $option->quantity]))
            ->all();

        $categories = $shop->categories()
            ->whereHas('products', function ($query) {
                $query->where('is_active', true);
            })
            ->get();

        return view('shop.show', compact(
            'shop',
            'categories',
            'products',
            'search',
            'usdToSypRate',
            'canonicalSellerCity',
            'stockByProduct',
            'optionStockById',
        ));
    }

    public function applyPromo(Request $request, $slug)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();

        $code = strtoupper(trim((string) $request->input('code', '')));
        $promo = $shop->promoCodes()->where('code', $code)->first();

        if (!$promo || !$promo->isValid()) {
            return response()->json(['valid' => false, 'message' => 'كود الخصم غير صحيح أو منتهي الصلاحية.']);
        }

        return response()->json([
            'valid' => true,
            'code' => $promo->code,
            'discount_percentage' => $promo->discount_percentage,
            'message' => 'تم تطبيق كود الخصم بنجاح!',
        ]);
    }

    public function checkout(CheckoutRequest $request, $slug)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();
        $validated = $request->validated();
        $cart = json_decode($validated['cart'], true);

        if (!is_array($cart) || empty($cart)) {
            return back()->withErrors(['cart' => 'عربة التسوق فارغة.'])->withInput();
        }

        $locationData = $this->locationService->normalizeLocationPayload([
            'location_text' => $validated['buyer_location_text'],
            'city' => $validated['buyer_city'],
        ]);

        $shamcashAvailable = $shop->shamcash_is_active
            && !empty($shop->shamcash_account_number)
            && !empty($shop->shamcash_qr_path);

        $paymentMethod = $validated['payment_method'] ?? 'cod';
        if ($paymentMethod === 'shamcash' && !$shamcashAvailable) {
            $paymentMethod = 'cod';
        }

        $shamcashTransactionNumber = null;
        if ($paymentMethod === 'shamcash') {
            $shamcashTransactionNumber = trim((string) ($validated['shamcash_transaction_number'] ?? ''));
            if ($shamcashTransactionNumber === '') {
                return back()->withErrors([
                    'shamcash_transaction_number' => 'يرجى إدخال رقم عملية التحويل عبر شام كاش.',
                ])->withInput();
            }

            if (str_starts_with($shamcashTransactionNumber, '#')) {
                return back()->withErrors([
                    'shamcash_transaction_number' => 'رقم العملية يظهر غالباً مع الرمز #، يرجى إدخال الرقم فقط بدون #.',
                ])->withInput();
            }
        }

        $promoCodeUsed = null;
        $promoDiscountPercent = 0;
        if (!empty($validated['promo_code'])) {
            $promo = $shop->promoCodes()->where('code', strtoupper(trim((string) $validated['promo_code'])))->first();
            if ($promo && $promo->isValid()) {
                $promoDiscountPercent = (float) $promo->discount_percentage;
                $promoCodeUsed = $promo->code . ' (-' . $promo->discount_percentage . '%)';
            }
        }

        try {
            DB::transaction(function () use (
                $shop,
                $validated,
                $cart,
                $locationData,
                $paymentMethod,
                $shamcashTransactionNumber,
                $promoCodeUsed,
                $promoDiscountPercent,
            ) {
                $resolvedItems = $this->productStockService->resolveCheckoutItems($shop, $cart);
                $usdToSypRate = $this->exchangeRateService->getCurrentUsdToSypRate();
                $pricingSnapshot = $this->checkoutPricingService->buildPricingSnapshot(
                    $resolvedItems,
                    $usdToSypRate,
                    $promoDiscountPercent,
                    (float) ($shop->delivery_fee_usd ?? 0),
                );
                $sellerCity = $this->locationService->canonicalizeCity($shop->city);
                $deliveryEstimate = $this->locationService->estimateDelivery(
                    $locationData['city'],
                    $sellerCity,
                    (bool) $shop->same_day_delivery_enabled,
                );

                $order = Order::create([
                    'shop_id' => $shop->id,
                    'buyer_name' => $validated['buyer_name'],
                    'buyer_email' => trim((string) ($validated['buyer_email'] ?? '')) ?: null,
                    'buyer_phone' => $validated['buyer_phone'],
                    'buyer_address' => $locationData['location_text'],
                    'buyer_location_text' => $locationData['location_text'],
                    'buyer_city' => $locationData['city'],
                    'seller_city_snapshot' => $sellerCity ?? $shop->city,
                    'delivery_estimate' => $deliveryEstimate,
                    'promo_code_used' => $promoCodeUsed,
                    'payment_method' => $paymentMethod,
                    'shamcash_transaction_number' => $shamcashTransactionNumber,
                    'usd_to_syp_rate' => $pricingSnapshot['usd_to_syp_rate'],
                    'subtotal_usd' => $pricingSnapshot['subtotal_usd'],
                    'subtotal_syp' => $pricingSnapshot['subtotal_syp'],
                    'discount_amount_usd' => $pricingSnapshot['discount_amount_usd'],
                    'discount_amount_syp' => $pricingSnapshot['discount_amount_syp'],
                    'delivery_fee_usd' => $pricingSnapshot['delivery_fee_usd'],
                    'delivery_fee_syp' => $pricingSnapshot['delivery_fee_syp'],
                    'final_total_usd' => $pricingSnapshot['final_total_usd'],
                    'final_total_syp' => $pricingSnapshot['final_total_syp'],
                    'total_amount' => $pricingSnapshot['final_total_syp'],
                    'status' => 'pending',
                ]);

                foreach ($pricingSnapshot['line_items'] as $lineItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $lineItem['product_id'],
                        'product_option_id' => $lineItem['product_option_id'],
                        'product_option_label' => $lineItem['product_option_label'],
                        'quantity' => $lineItem['quantity'],
                        'price_at_time_of_order' => $lineItem['unit_price_syp'],
                        'unit_price_usd' => $lineItem['unit_price_usd'],
                        'unit_price_syp' => $lineItem['unit_price_syp'],
                    ]);
                }

                $this->productStockService->decrementResolvedItems($resolvedItems);
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('shop.show', $shop->slug)->with('success', 'تم استلام طلبك بنجاح! شكراً لك.');
    }

    public function checkSlug(Request $request)
    {
        $slug = $request->query('slug');
        if (!$slug) {
            return response()->json(['available' => true]);
        }

        $query = Shop::where('slug', $slug);

        if ($shopId = Auth::user()->shop?->id) {
            $query->where('id', '!=', $shopId);
        }

        return response()->json(['available' => !$query->exists()]);
    }

    public function update(UpdateShopRequest $request)
    {
        $shop = Auth::user()->shop;
        if (!$shop) {
            abort(404);
        }

        $publicDisk = Storage::disk('public');
        $validated = $request->validated();
        $locationData = $this->locationService->normalizeLocationPayload($validated);

        $shop->name = $validated['name'];
        $shop->slug = $validated['slug'];
        $shop->description = $validated['description'] ?? null;
        $shop->delivery_fee_usd = round(max(0, (float) ($validated['delivery_fee_usd'] ?? 0)), 2);
        $shop->color = $validated['color'] ?? 'navy';
        $shop->location_text = $locationData['location_text'];
        $shop->city = $locationData['city'];
        $shop->same_day_delivery_enabled = (bool) ($validated['same_day_delivery_enabled'] ?? false);
        $shop->shamcash_account_number = trim((string) ($validated['shamcash_account_number'] ?? '')) ?: null;

        if ($request->hasFile('logo')) {
            if ($shop->logo_path) {
                $publicDisk->delete($shop->logo_path);
            }

            $shop->logo_path = $request->file('logo')->store('shops/logos', 'public');
        } elseif ($request->filled('cropped_logo')) {
            if ($shop->logo_path) {
                $publicDisk->delete($shop->logo_path);
            }

            $shop->logo_path = $this->storeBase64Image($publicDisk, $request->string('cropped_logo')->toString(), 'shops/logos');
        }

        if ($request->hasFile('hero_image')) {
            if ($shop->hero_image_path) {
                $publicDisk->delete($shop->hero_image_path);
            }

            $shop->hero_image_path = $request->file('hero_image')->store('shops/heroes', 'public');
        }

        if ($request->boolean('shamcash_remove_qr') && $shop->shamcash_qr_path) {
            $publicDisk->delete($shop->shamcash_qr_path);
            $shop->shamcash_qr_path = null;
        }

        if ($request->hasFile('shamcash_qr')) {
            if ($shop->shamcash_qr_path) {
                $publicDisk->delete($shop->shamcash_qr_path);
            }

            $shop->shamcash_qr_path = $request->file('shamcash_qr')->store('shops/shamcash', 'public');
        }

        $hasShamcashSetup = !empty($shop->shamcash_account_number) && !empty($shop->shamcash_qr_path);
        $shop->shamcash_is_active = !empty($validated['shamcash_is_active']) && $hasShamcashSetup;
        $shop->save();

        return back()->with('success', 'تم تحديث معلومات المتجر بنجاح!');
    }

    protected function storeBase64Image(FilesystemAdapter $disk, string $imageData, string $directory): ?string
    {
        $imageParts = explode(';base64,', $imageData);
        if (count($imageParts) !== 2) {
            return null;
        }

        $imageTypeAux = explode('image/', $imageParts[0]);
        $imageType = $imageTypeAux[1] ?? 'png';
        $imageBase64 = base64_decode($imageParts[1]);

        if ($imageBase64 === false) {
            return null;
        }

        $filename = $directory . '/' . uniqid() . '.' . $imageType;
        $disk->put($filename, $imageBase64);

        return $filename;
    }
}
