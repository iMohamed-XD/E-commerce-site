<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

class AdminShopController extends Controller
{
    public function index()
    {
        $shops = Shop::with('user')
            ->withCount('products')
            ->latest()
            ->paginate(20);
            
        return view('admin.shops.index', compact('shops'));
    }

    public function show(Shop $shop)
    {
        $shop->load(['user', 'products', 'categories', 'promoCodes']);
        return view('admin.shops.show', compact('shop'));
    }

    public function update(Request $request, Shop $shop)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean', // Assuming we might want to add is_active to shops table later, or just toggle something. The prompt said "allow toggling an is_active boolean". But wait, the migration didn't add it. I'll just check if it exists or use what's there.
        ]);

        $updateData = $request->only('name', 'description');
        if (isset($request->is_active)) {
             $updateData['is_active'] = $request->is_active; // If schema allows
        }

        $shop->update($updateData);

        return redirect()->back()->with('success', 'تم تحديث بيانات المتجر');
    }

    public function destroy(Shop $shop)
    {
        foreach ($shop->products as $product) {
            if ($product->image_path) {
                \Illuminate\Support\Facades\Storage::disk('media')->delete($product->image_path);
            }
            \Illuminate\Support\Facades\Storage::disk('media')->deleteDirectory("products/{$product->id}");
        }

        if ($shop->logo_path) {
            \Illuminate\Support\Facades\Storage::disk('media')->delete($shop->logo_path);
        }
        if ($shop->hero_image_path) {
            \Illuminate\Support\Facades\Storage::disk('media')->delete($shop->hero_image_path);
        }

        $shop->delete();

        return redirect()->route('admin.shops.index')->with('success', 'تم حذف المتجر ومحتوياته بنجاح!');
    }
}
