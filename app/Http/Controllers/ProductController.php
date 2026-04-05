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
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'secondary_images' => 'nullable|array|max:3',
            'secondary_images.*' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        $shop = Auth::user()->shop;

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products');
        }

        $product = $shop->products()->create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => $imagePath,
            'is_active' => $request->has('is_active'),
            'discount_percent' => $request->discount_percent ?: null,
            'discount_active' => $request->filled('discount_percent'),
        ]);

        if ($request->hasFile('secondary_images')) {
            $sort = 0;
            foreach($request->file('secondary_images') as $file) {
                $path = $file->store("products/{$product->id}/secondary");
                $product->productImages()->create([
                    'path' => $path,
                    'sort_order' => $sort++
                ]);
            }
        }

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
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'secondary_images' => 'nullable|array',
            'secondary_images.*' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        $currentImagesCount = $product->productImages()->count();
        if ($request->hasFile('secondary_images')) {
            $newFilesCount = count($request->file('secondary_images'));
            if (($currentImagesCount + $newFilesCount) > 3) {
                return back()->withErrors(['secondary_images' => 'لا يمكنك رفع أكثر من 3 صور إضافية.']);
            }
        }

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

        if ($request->hasFile('secondary_images')) {
            // Delete old secondary images if replacing (as per the info text "رفع صور جديدة هنا سيقوم بحذف الصور الإضافية السابقة واستبدالها.")
            foreach($product->productImages as $oldImg) {
                Storage::delete($oldImg->path);
                $oldImg->delete();
            }

            $sort = $product->productImages()->orderByDesc('sort_order')->first()?->sort_order ?? 0;
            $sort = $sort >= 0 && $product->productImages()->count() > 0 ? $sort + 1 : 0;
            
            foreach ($request->file('secondary_images') as $file) {
                $path = $file->store("products/{$product->id}/secondary");
                $product->productImages()->create([
                    'path' => $path,
                    'sort_order' => $sort++
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'تم تعديل المنتج بنجاح!');
    }

    public function destroy(Product $product) // Kept Product $product as this is ProductController
    {
        Gate::authorize('manage', $product);

        if ($product->image_path) {
            Storage::delete($product->image_path);
        }
        Storage::deleteDirectory("products/{$product->id}");

        $product->delete();

        return redirect()->route('products.index')->with('success', 'تم حذف المنتج بنجاح!');
    }

    public function destroyImage(Product $product, \App\Models\ProductImage $image)
    {
        Gate::authorize('manage', $product);
        if ($image->product_id !== $product->id) {
            abort(404);
        }
        Storage::delete($image->path);
        $image->delete();
        return response()->json(['success' => true]);
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
                Storage::deleteDirectory("products/{$product->id}");
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
