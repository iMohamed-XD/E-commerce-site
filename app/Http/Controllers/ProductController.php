<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;
        if (!$shop) {
            return redirect()->route('dashboard');
        }

        $products = $shop->products()->latest()->get();
        return view('products.index', compact('products', 'shop'));
    }

    public function create()
    {
        $shop = Auth::user()->shop;
        $categories = $shop->categories;
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id', // Changed from 'category' to 'category_id'
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $shop = Auth::user()->shop;

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products');
        }

        $shop->products()->create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => $imagePath,
            'is_active' => $request->has('is_active'),
            'discount_percent' => $request->discount_percent ?: null,
            'discount_active' => $request->filled('discount_percent'),
        ]);

        return redirect()->route('products.index')->with('success', 'تمت إضافة المنتج بنجاح!');
    }

    public function edit(Product $product)
    {
        Gate::authorize('manage', $product);
        $categories = Auth::user()->shop->categories;
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        Gate::authorize('manage', $product);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id', // Changed from 'category' to 'category_id'
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::delete($product->image_path);
            }
            $product->image_path = $request->file('image')->store('products');
        }

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'is_active' => $request->has('is_active'),
            'discount_percent' => $request->discount_percent ?: null,
        ]);

        return redirect()->route('products.index')->with('success', 'تم تعديل المنتج بنجاح!');
    }

    public function destroy(Product $product) // Kept Product $product as this is ProductController
    {
        Gate::authorize('manage', $product);

        if ($product->image_path) {
            Storage::delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'تم حذف المنتج بنجاح!');
    }

    public function bulkAction(Request $request)
    {
        $shop = Auth::user()->shop;
        
        $request->validate([
            'action' => 'required|in:delete,discount,remove_discount',
            'product_ids' => 'required|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $productIds = json_decode($request->product_ids, true);
        if (!is_array($productIds) || empty($productIds)) {
            return back()->withErrors(['product_ids' => 'لم يتم تحديد أي منتج.']);
        }

        $productsQuery = $shop->products()->whereIn('id', $productIds);

        if ($request->action === 'delete') {
            $products = $productsQuery->get();
            foreach($products as $product) {
                if ($product->image_path) {
                    Storage::delete($product->image_path);
                }
                $product->delete();
            }
            return redirect()->route('products.index')->with('success', 'تم حذف المنتجات المحددة بنجاح.');
        }

        if ($request->action === 'discount') {
            if (!$request->discount_percent) {
                return back()->withErrors(['يجب إدخال نسبة الخصم.']);
            }
            $productsQuery->update([
                'discount_percent' => $request->discount_percent,
                'discount_active' => true,
            ]);
            return redirect()->route('products.index')->with('success', 'تم تطبيق الخصم على المنتجات المحددة.');
        }

        if ($request->action === 'remove_discount') {
            $productsQuery->update([
                'discount_active' => false,
            ]);
            return redirect()->route('products.index')->with('success', 'تم إزالة الخصم عن المنتجات المحددة.');
        }

        return back();
    }
    public function toggleDiscount(Product $product)
    {
        Gate::authorize('manage', $product);

        if (!$product->discount_percent) {
            return back()->with('error', 'يرجى تحديد نسبة الخصم أولاً.');
        }

        $product->update(['discount_active' => !$product->discount_active]);

        return back()->with('success', 'تم تحديث حالة الخصم!');
    }
}
