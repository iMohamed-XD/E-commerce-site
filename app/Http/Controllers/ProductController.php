<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $shop = auth()->user()->shop;
        if (!$shop) {
            return redirect()->route('dashboard');
        }

        $products = $shop->products()->latest()->get();
        return view('products.index', compact('products', 'shop'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $shop = auth()->user()->shop;

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $shop->products()->create([
            'name' => $request->name,
            'category' => $request->category,
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
        if ($product->shop_id !== auth()->user()->shop->id) {
            abort(403);
        }
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->shop_id !== auth()->user()->shop->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $product->image_path = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'price' => $request->price,
            'is_active' => $request->has('is_active'),
            'discount_percent' => $request->discount_percent ?: null,
        ]);

        return redirect()->route('products.index')->with('success', 'تم تعديل المنتج بنجاح!');
    }

    public function destroy(Product $product)
    {
        if ($product->shop_id !== auth()->user()->shop->id) {
            abort(403);
        }

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'تم حذف المنتج بنجاح!');
    }

    public function bulkAction(Request $request)
    {
        $shop = auth()->user()->shop;
        
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
                    Storage::disk('public')->delete($product->image_path);
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
        if ($product->shop_id !== auth()->user()->shop->id) {
            abort(403);
        }

        if (!$product->discount_percent) {
            return back()->with('error', 'يرجى تحديد نسبة الخصم أولاً.');
        }

        $product->update(['discount_active' => !$product->discount_active]);

        return back()->with('success', 'تم تحديث حالة الخصم!');
    }
}
