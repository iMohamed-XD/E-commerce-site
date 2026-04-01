<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\GoogleController;

Route::get('/auth/google',          [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

// Landing Page
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('landing');
})->name('landing');

Route::view('/terms', 'legal.terms')->name('terms');
Route::view('/privacy', 'legal.privacy')->name('privacy');

Route::get('/attributions', function () {
    return view('legal.attributions');
})->name('attributions');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Seller Dashboard & Shop Management (Check slug MUST be before public {slug} route)
Route::middleware(['auth', 'seller'])->group(function () {
    Route::get('/shop/check-slug', [\App\Http\Controllers\ShopController::class, 'checkSlug'])->name('shop.checkSlug');
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

    // Category Management
    Route::resource('categories', \App\Http\Controllers\CategoryController::class)->only(['index', 'store', 'destroy']);
});

// Public Storefront Routes with Rate Limiting (MUST be after check-slug)
Route::get('/shop/{slug}', [\App\Http\Controllers\ShopController::class, 'show'])->name('shop.show');
Route::post('/shop/{slug}/checkout', [\App\Http\Controllers\ShopController::class, 'checkout'])
    ->middleware('throttle:5,1')
    ->name('shop.checkout');
Route::post('/shop/{slug}/apply-promo', [\App\Http\Controllers\ShopController::class, 'applyPromo'])
    ->middleware('throttle:10,1')
    ->name('shop.apply_promo');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
