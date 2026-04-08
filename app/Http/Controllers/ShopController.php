<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ShopController extends Controller
{
    public function store(Request $request)
    {
        $publicDisk = Storage::disk('public');

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:shops,slug',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'hero_image' => 'nullable|image|max:4096',
            'color' => ['required', 'string', Rule::in(array_keys(config('shop_colors', ['navy' => []])))],
            'shamcash_account_number' => 'nullable|string|max:255',
            'shamcash_qr' => 'nullable|image|max:4096',
            'shamcash_is_active' => 'nullable|boolean',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('shops/logos', 'public');
        } elseif ($request->filled('cropped_logo')) {
            $imageParts = explode(';base64,', $request->cropped_logo);
            if (count($imageParts) === 2) {
                $imageTypeAux = explode('image/', $imageParts[0]);
                $imageType = $imageTypeAux[1] ?? 'png';
                $imageBase64 = base64_decode($imageParts[1]);
                if ($imageBase64 !== false) {
                    $filename = 'shops/logos/' . uniqid() . '.' . $imageType;
                    $publicDisk->put($filename, $imageBase64);
                    $logoPath = $filename;
                }
            }
        }

        $heroImagePath = null;
        if ($request->hasFile('hero_image')) {
            $heroImagePath = $request->file('hero_image')->store('shops/heroes', 'public');
        }

        $shamcashQrPath = null;
        if ($request->hasFile('shamcash_qr')) {
            $shamcashQrPath = $request->file('shamcash_qr')->store('shops/shamcash', 'public');
        }

        $shamcashAccountNumber = trim((string) $request->input('shamcash_account_number', ''));
        $shamcashAccountNumber = $shamcashAccountNumber !== '' ? $shamcashAccountNumber : null;
        $shamcashIsActive = $request->boolean('shamcash_is_active');
        $hasShamcashSetup = !empty($shamcashAccountNumber) && !empty($shamcashQrPath);
        $shamcashIsActive = $shamcashIsActive && $hasShamcashSetup;

        Shop::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'logo_path' => $logoPath,
            'hero_image_path' => $heroImagePath,
            'color' => $request->input('color', 'navy'),
            'shamcash_account_number' => $shamcashAccountNumber,
            'shamcash_qr_path' => $shamcashQrPath,
            'shamcash_is_active' => $shamcashIsActive,
        ]);

        return redirect()->route('dashboard')->with('success', 'تم إنشاء متجرك بنجاح!');
    }

    public function show($slug)
    {
        $shop = Shop::where('slug', $slug)->with(['products' => function ($query) {
            $query->where('is_active', true)->orderBy('created_at', 'desc');
        }])->firstOrFail();

        $categories = $shop->categories()
            ->whereHas('products', function ($query) {
                $query->where('is_active', true);
            })->get();

        return view('shop.show', compact('shop', 'categories'));
    }

    public function applyPromo(Request $request, $slug)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();

        $code = strtoupper(trim($request->code));
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

    public function checkout(Request $request, $slug)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();

        $request->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'nullable|email|max:255',
            'buyer_phone' => 'required|string|max:255',
            'buyer_address' => 'required|string',
            'cart' => 'required|json',
            'promo_code' => 'nullable|string',
            'payment_method' => 'required|in:cod,shamcash',
            'shamcash_transaction_number' => 'nullable|string|max:255',
        ]);

        $cart = json_decode($request->cart, true);
        if (empty($cart)) {
            return back()->withErrors(['cart' => 'عربة التسوق فارغة']);
        }

        $totalAmount = 0;
        $orderItems = [];

        foreach ($cart as $item) {
            $product = $shop->products()->where('id', $item['id'])->where('is_active', true)->first();
            if ($product) {
                $priceToUse = $product->effectivePrice();
                $totalAmount += $priceToUse * $item['quantity'];

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price_at_time_of_order' => $priceToUse,
                ];
            }
        }

        if ($totalAmount == 0) {
            return back()->withErrors(['cart' => 'المنتجات في العربة غير صالحة.']);
        }

        $shamcashAvailable = $shop->shamcash_is_active && !empty($shop->shamcash_account_number) && !empty($shop->shamcash_qr_path);
        $paymentMethod = $request->input('payment_method', 'cod');
        if ($paymentMethod === 'shamcash' && !$shamcashAvailable) {
            $paymentMethod = 'cod';
        }

        $shamcashTransactionNumber = null;
        if ($paymentMethod === 'shamcash') {
            $shamcashTransactionNumber = trim((string) $request->input('shamcash_transaction_number', ''));
            if ($shamcashTransactionNumber === '') {
                return back()->withErrors([
                    'shamcash_transaction_number' => 'يرجى إدخال رقم عملية التحويل عبر شام كاش.',
                ])->withInput();
            }
            if (str_starts_with($shamcashTransactionNumber, '#')) {
                return back()->withErrors([
                    'shamcash_transaction_number' => 'رقم عملية شام كاش يظهر غالبًا مع الرمز #، يرجى إدخال الرقم فقط بدون #.',
                ])->withInput();
            }
        }

        $promoCodeUsed = null;
        if ($request->filled('promo_code')) {
            $promo = $shop->promoCodes()->where('code', strtoupper(trim($request->promo_code)))->first();
            if ($promo && $promo->isValid()) {
                $discount = ($totalAmount * $promo->discount_percentage) / 100;
                $totalAmount -= $discount;
                $promoCodeUsed = $promo->code . ' (-' . $promo->discount_percentage . '%)';
            }
        }

        $order = Order::create([
            'shop_id' => $shop->id,
            'buyer_name' => $request->buyer_name,
            'buyer_email' => $request->buyer_email,
            'buyer_phone' => $request->buyer_phone,
            'buyer_address' => $request->buyer_address,
            'promo_code_used' => $promoCodeUsed,
            'payment_method' => $paymentMethod,
            'shamcash_transaction_number' => $shamcashTransactionNumber,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price_at_time_of_order' => $item['price_at_time_of_order'],
            ]);
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

        $exists = $query->exists();

        return response()->json(['available' => !$exists]);
    }

    public function update(Request $request)
    {
        $publicDisk = Storage::disk('public');

        $shop = Auth::user()->shop;
        if (!$shop) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:shops,slug,' . $shop->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'hero_image' => 'nullable|image|max:4096',
            'color' => ['required', 'string', Rule::in(array_keys(config('shop_colors', ['navy' => []])))],
            'shamcash_account_number' => 'nullable|string|max:255',
            'shamcash_qr' => 'nullable|image|max:4096',
            'shamcash_is_active' => 'nullable|boolean',
            'shamcash_remove_qr' => 'nullable|boolean',
        ]);

        $shop->name = $request->name;
        $shop->slug = $request->slug;
        $shop->description = $request->description;
        $shop->color = $request->input('color', 'navy');
        $shop->shamcash_account_number = trim((string) $request->input('shamcash_account_number', '')) ?: null;

        if ($request->hasFile('logo')) {
            if ($shop->logo_path) {
                $publicDisk->delete($shop->logo_path);
            }
            $shop->logo_path = $request->file('logo')->store('shops/logos', 'public');
        } elseif ($request->filled('cropped_logo')) {
            if ($shop->logo_path) {
                $publicDisk->delete($shop->logo_path);
            }
            $imageParts = explode(';base64,', $request->cropped_logo);
            if (count($imageParts) === 2) {
                $imageTypeAux = explode('image/', $imageParts[0]);
                $imageType = $imageTypeAux[1] ?? 'png';
                $imageBase64 = base64_decode($imageParts[1]);
                if ($imageBase64 !== false) {
                    $filename = 'shops/logos/' . uniqid() . '.' . $imageType;
                    $publicDisk->put($filename, $imageBase64);
                    $shop->logo_path = $filename;
                }
            }
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
        $shop->shamcash_is_active = $request->boolean('shamcash_is_active') && $hasShamcashSetup;

        $shop->save();

        return back()->with('success', 'تم تحديث معلومات المتجر بنجاح!');
    }
}
