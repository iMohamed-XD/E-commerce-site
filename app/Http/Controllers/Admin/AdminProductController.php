<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with('shop')
            ->latest()
            ->paginate(20);
            
        return view('admin.products.index', compact('products'));
    }

    public function destroy(Product $product)
    {
        if ($product->image_path) {
            \Illuminate\Support\Facades\Storage::disk('media')->delete($product->image_path);
        }
        \Illuminate\Support\Facades\Storage::disk('media')->deleteDirectory("products/{$product->id}");

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'تم حذف المنتج بنجاح!');
    }
}
