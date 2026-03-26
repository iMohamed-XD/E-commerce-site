<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect root to login (or dashboard if already authenticated)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Public Storefront Routes
Route::get('/shop/{slug}', [\App\Http\Controllers\ShopController::class, 'show'])->name('shop.show');
Route::post('/shop/{slug}/checkout', [\App\Http\Controllers\ShopController::class, 'checkout'])->name('shop.checkout');
Route::post('/shop/{slug}/apply-promo', [\App\Http\Controllers\ShopController::class, 'applyPromo'])->name('shop.apply_promo');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mahly App Routes
    Route::post('/shop', [\App\Http\Controllers\ShopController::class, 'store'])->name('shop.store');
    Route::patch('/shop', [\App\Http\Controllers\ShopController::class, 'update'])->name('shop.update');
    Route::post('/products/bulk-action', [\App\Http\Controllers\ProductController::class, 'bulkAction'])->name('products.bulk_action');
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/status', [\App\Http\Controllers\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::resource('promo-codes', \App\Http\Controllers\PromoCodeController::class)->only(['index', 'store', 'destroy']);
    Route::patch('/promo-codes/{promoCode}/toggle', [\App\Http\Controllers\PromoCodeController::class, 'toggle'])->name('promo-codes.toggle');
    Route::patch('/products/{product}/toggle-discount', [\App\Http\Controllers\ProductController::class, 'toggleDiscount'])->name('products.toggle_discount');
});

require __DIR__.'/auth.php';
