<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ShopController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:shops,slug',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'hero_image' => 'nullable|image|max:4096',
            'color' => ['required', 'string', Rule::in(array_keys(config('shop_colors', ['navy' => []])))],
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('shops/logos');
        } elseif ($request->filled('cropped_logo')) {
            $imageParts = explode(";base64,", $request->cropped_logo);
            if (count($imageParts) === 2) {
                $imageTypeAux = explode("image/", $imageParts[0]);
                $imageType = $imageTypeAux[1] ?? 'png';
                $imageBase64 = base64_decode($imageParts[1]);
                $filename = 'shops/logos/' . uniqid() . '.' . $imageType;
                Storage::put($filename, $imageBase64);
                $logoPath = $filename;
            }
        }

        $heroImagePath = null;
        if ($request->hasFile('hero_image')) {
            $heroImagePath = $request->file('hero_image')->store('shops/heroes');
        }

        $shop = Shop::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'logo_path' => $logoPath,
            'hero_image_path' => $heroImagePath,
            'color' => $request->input('color', 'navy'),
        ]);

        return redirect()->route('dashboard')->with('success', 'تم إنشاء متجرك بنجاح!');
    }

    public function show($slug)
    {
        $shop = Shop::where('slug', $slug)->with(['products' => function($query) {
            $query->where('is_active', true)->orderBy('created_at', 'desc');
        }])->firstOrFail();

        $categories = $shop->categories()
            ->whereHas('products', function($query) {
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
            'message' => 'تم تطبيق كود الخصم بنجاح!'
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

        // Apply global Promo Code discount
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
        
        // If updating, ignore current shop
        if ($shopId = Auth::user()->shop?->id) {
            $query->where('id', '!=', $shopId);
        }

        $exists = $query->exists();

        return response()->json(['available' => !$exists]);
    }

    public function update(Request $request)
    {
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
        ]);

        $shop->name = $request->name;
        $shop->slug = $request->slug;
        $shop->description = $request->description;
        $shop->color = $request->input('color', 'navy');

        if ($request->hasFile('logo')) {
            if ($shop->logo_path) {
                Storage::delete($shop->logo_path);
            }
            $shop->logo_path = $request->file('logo')->store('shops/logos');
        } elseif ($request->filled('cropped_logo')) {
            if ($shop->logo_path) {
                Storage::delete($shop->logo_path);
            }
            $imageParts = explode(";base64,", $request->cropped_logo);
            if (count($imageParts) === 2) {
                $imageTypeAux = explode("image/", $imageParts[0]);
                $imageType = $imageTypeAux[1] ?? 'png';
                $imageBase64 = base64_decode($imageParts[1]);
                $filename = 'shops/logos/' . uniqid() . '.' . $imageType;
                Storage::put($filename, $imageBase64);
                $shop->logo_path = $filename;
            }
        }

        if ($request->hasFile('hero_image')) {
            if ($shop->hero_image_path) {
                Storage::delete($shop->hero_image_path);
            }
            $shop->hero_image_path = $request->file('hero_image')->store('shops/heroes');
        }

        $shop->save();

        return back()->with('success', 'تم تحديث معلومات المتجر بنجاح!');
    }
}
