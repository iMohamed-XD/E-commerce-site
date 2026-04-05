<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BuyerProductController extends Controller
{
    public function show(Shop $shop, Product $product)
    {
        if ($product->shop_id !== $shop->id) {
            abort(404);
        }

        $product->load(['shop', 'productImages', 'category']);
        
        $images = [];
        if ($product->image_path) {
            $images[] = Storage::disk('media')->temporaryUrl($product->image_path, now()->addHours(2));
        }
        
        foreach ($product->productImages as $productImage) {
            if ($productImage->path) {
                $images[] = Storage::disk('media')->temporaryUrl($productImage->path, now()->addHours(2));
            }
        }
        
        $images = array_slice($images, 0, 4);

        return view('buyer.products.show', compact('product', 'shop', 'images'));
    }
}
