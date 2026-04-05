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

    public function destroy(\Illuminate\Http\Request $request, User $user)
    {
        if ($user->role === 'admin') {
            abort(403, 'لا يمكن حذف مدير عبر هذا المسار');
        }

        // Handle Optional Blocking
        if ($request->has('block_email')) {
            \App\Models\BlockedEmail::create(['email' => $user->email]);
            
            try {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\AccountDeleted($user->name));
            } catch (\Exception $e) {
                // Log and continue if mail fails
                \Illuminate\Support\Facades\Log::error("Failed to send deletion mail to {$user->email}: " . $e->getMessage());
            }
        }

        $shop = $user->shop;
        if ($shop) {
            foreach ($shop->products as $product) {
                if ($product->image_path) {
                    \Illuminate\Support\Facades\Storage::delete($product->image_path);
                }
                \Illuminate\Support\Facades\Storage::deleteDirectory("products/{$product->id}");
            }
        }

        $user->delete();

        $message = $request->has('block_email') 
            ? 'تم حذف البائع وحظر بريده الإلكتروني بنجاح!' 
            : 'تم حذف البائع ومستلزماته بنجاح!';

        return redirect()->route('admin.sellers.index')->with('success', $message);
    }
}
