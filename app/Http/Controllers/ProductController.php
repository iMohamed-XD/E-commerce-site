<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductStockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $shop = Auth::user()->shop;
        if (!$shop) {
            return redirect()->route('dashboard');
        }

        $perPage = (int) $request->input('per_page', 20);
        if (!in_array($perPage, [10, 15, 20, 25, 30], true)) {
            $perPage = 20;
        }

        $field = $request->string('field')->toString();
        $value = trim((string) $request->input('value', ''));

        $productsQuery = $shop->products()->with(['category', 'productOptions'])->latest();

        if ($value !== '') {
            $allowedFields = [
                'id',
                'name',
                'description',
                'price',
                'quantity_available',
                'has_options',
                'is_active',
                'discount_percent',
                'discount_active',
                'category_name',
                'created_at',
            ];

            if (in_array($field, $allowedFields, true)) {
                if ($field === 'id') {
                    $productsQuery->where('id', (int) $value);
                } elseif (in_array($field, ['price', 'quantity_available', 'discount_percent'], true)) {
                    $productsQuery->where($field, (float) $value);
                } elseif ($field === 'has_options') {
                    $productsQuery->where('has_options', in_array(mb_strtolower($value), ['1', 'true', 'yes', 'options', 'variants'], true));
                } elseif (in_array($field, ['is_active', 'discount_active'], true)) {
                    $normalized = in_array(mb_strtolower($value), ['1', 'true', 'yes', 'active', 'نشط', 'مفعل'], true) ? 1 : 0;
                    $productsQuery->where($field, $normalized);
                } elseif ($field === 'category_name') {
                    $productsQuery->whereHas('category', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                } elseif ($field === 'created_at') {
                    $productsQuery->whereDate('created_at', $value);
                } else {
                    $productsQuery->where($field, 'like', '%' . $value . '%');
                }
            }
        }

        $products = $productsQuery->paginate($perPage)->withQueryString();

        return view('products.index', compact('products', 'shop', 'perPage', 'field', 'value'));
    }

    public function create()
    {
        $shop = Auth::user()->shop;
        $categories = $shop?->categories ?? collect();

        return view('products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request, ProductStockService $productStockService)
    {
        $shop = Auth::user()->shop;
        if (!$shop) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validated();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products');
        }

        $discountPercent = isset($validated['discount_percent']) ? (float) $validated['discount_percent'] : null;
        $hasOptions = !empty($validated['has_options']);

        $product = $shop->products()->create([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'price' => (float) $validated['price'],
            'quantity_available' => $hasOptions ? 0 : (int) ($validated['quantity_available'] ?? 0),
            'has_options' => $hasOptions,
            'image_path' => $imagePath,
            'is_active' => !empty($validated['is_active']),
            'discount_percent' => $discountPercent,
            'discount_active' => $discountPercent !== null && $discountPercent > 0,
        ]);

        $productStockService->syncProductOptions($product, $validated['options'] ?? []);

        if ($request->hasFile('secondary_images')) {
            $sort = 0;
            foreach ($request->file('secondary_images') as $file) {
                $path = $file->store("products/{$product->id}/secondary");
                $product->productImages()->create([
                    'path' => $path,
                    'sort_order' => $sort++,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'تمت إضافة المنتج بنجاح!');
    }

    public function edit(Product $product)
    {
        Gate::authorize('manage', $product);

        $categories = Auth::user()->shop?->categories ?? collect();
        $product->loadMissing('productOptions');

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product, ProductStockService $productStockService)
    {
        Gate::authorize('manage', $product);

        $validated = $request->validated();

        if ($request->hasFile('secondary_images')) {
            $newFilesCount = count($request->file('secondary_images'));
            if ($newFilesCount > 3) {
                return back()->withErrors(['secondary_images' => 'لا يمكنك رفع أكثر من 3 صور إضافية.']);
            }
        }

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::delete($product->image_path);
            }

            $product->image_path = $request->file('image')->store('products');
        }

        $discountPercent = isset($validated['discount_percent']) ? (float) $validated['discount_percent'] : null;
        $hasOptions = !empty($validated['has_options']);

        $product->update([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'price' => (float) $validated['price'],
            'quantity_available' => $hasOptions ? 0 : (int) ($validated['quantity_available'] ?? 0),
            'has_options' => $hasOptions,
            'is_active' => !empty($validated['is_active']),
            'discount_percent' => $discountPercent,
            'discount_active' => $discountPercent !== null && $discountPercent > 0,
        ]);

        $productStockService->syncProductOptions($product, $validated['options'] ?? []);

        if ($request->hasFile('secondary_images')) {
            foreach ($product->productImages as $oldImage) {
                Storage::delete($oldImage->path);
                $oldImage->delete();
            }

            $sort = 0;
            foreach ($request->file('secondary_images') as $file) {
                $path = $file->store("products/{$product->id}/secondary");
                $product->productImages()->create([
                    'path' => $path,
                    'sort_order' => $sort++,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'تم تعديل المنتج بنجاح!');
    }

    public function destroy(Product $product)
    {
        Gate::authorize('manage', $product);

        if ($product->image_path) {
            Storage::delete($product->image_path);
        }

        Storage::deleteDirectory("products/{$product->id}");
        $product->delete();

        return redirect()->route('products.index')->with('success', 'تم حذف المنتج بنجاح!');
    }

    public function destroyImage(Product $product, ProductImage $image)
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
            foreach ($products as $product) {
                if ($product->image_path) {
                    Storage::delete($product->image_path);
                }

                Storage::deleteDirectory("products/{$product->id}");
                $product->delete();
            }

            return redirect()->route('products.index')->with('success', 'تم حذف المنتجات المحددة بنجاح.');
        }

        if ($request->action === 'discount') {
            if ($request->discount_percent === null) {
                return back()->withErrors(['discount_percent' => 'يجب إدخال نسبة الخصم.']);
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
