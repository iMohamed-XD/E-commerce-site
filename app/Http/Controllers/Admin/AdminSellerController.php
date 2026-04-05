<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminSellerController extends Controller
{
    public function index()
    {
        $sellers = User::where('role', 'seller')
            ->with('shop')
            ->latest()
            ->paginate(20);
            
        return view('admin.sellers.index', compact('sellers'));
    }

    public function show(User $user)
    {
        if ($user->role !== 'seller') {
            abort(404);
        }

        $user->load(['shop.products', 'shop.categories', 'shop.promoCodes', 'feedback']);
        return view('admin.sellers.show', compact('user'));
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            abort(403, 'لا يمكن حذف مدير عبر هذا المسار');
        }

        $shop = $user->shop;
        if ($shop) {
            foreach ($shop->products as $product) {
                if ($product->image_path) {
                    \Illuminate\Support\Facades\Storage::disk('media')->delete($product->image_path);
                }
                \Illuminate\Support\Facades\Storage::disk('media')->deleteDirectory("products/{$product->id}");
            }
        }

        $user->delete();

        return redirect()->route('admin.sellers.index')->with('success', 'تم حذف البائع ومستلزماته بنجاح!');
    }
}
