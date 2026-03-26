<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Redirect root to login (or dashboard if already authenticated)
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Public Storefront Routes with Rate Limiting
Route::get('/shop/{slug}', [\App\Http\Controllers\ShopController::class, 'show'])->name('shop.show');
Route::post('/shop/{slug}/checkout', [\App\Http\Controllers\ShopController::class, 'checkout'])
    ->middleware('throttle:5,1')
    ->name('shop.checkout');
Route::post('/shop/{slug}/apply-promo', [\App\Http\Controllers\ShopController::class, 'apply_promo'])
    ->middleware('throttle:10,1')
    ->name('shop.apply_promo');

Route::middleware(['auth', 'seller'])->group(function () {
    // Seller Dashboard & Shop Management
    Route::post('/shop', [\App\Http\Controllers\ShopController::class, 'store'])->name('shop.store');
    Route::patch('/shop', [\App\Http\Controllers\ShopController::class, 'update'])->name('shop.update');
    
    // Product Management
    Route::post('/products/bulk-action', [\App\Http\Controllers\ProductController::class, 'bulkAction'])->name('products.bulk_action');
    Route::patch('/products/{product}/toggle-discount', [\App\Http\Controllers\ProductController::class, 'toggleDiscount'])->name('products.toggle_discount');
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    
    // Order Management
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/status', [\App\Http\Controllers\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Promo Code Management
    Route::resource('promo-codes', \App\Http\Controllers\PromoCodeController::class)->only(['index', 'store', 'destroy']);
    Route::patch('/promo-codes/{promoCode}/toggle', [\App\Http\Controllers\PromoCodeController::class, 'toggle'])->name('promo-codes.toggle');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
