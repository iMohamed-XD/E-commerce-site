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

    // Feedback Management
    Route::get('/dashboard/feedback', [\App\Http\Controllers\FeedbackController::class, 'show'])->name('feedback.show');
    Route::post('/dashboard/feedback', [\App\Http\Controllers\FeedbackController::class, 'store'])->name('feedback.store');
    Route::put('/dashboard/feedback', [\App\Http\Controllers\FeedbackController::class, 'update'])->name('feedback.update');

    // Product Management
    Route::post('/products/bulk-action', [\App\Http\Controllers\ProductController::class, 'bulkAction'])->name('products.bulk_action');
    Route::patch('/products/{product}/toggle-discount', [\App\Http\Controllers\ProductController::class, 'toggleDiscount'])->name('products.toggle_discount');
    Route::delete('/products/{product}/images/{image}', [\App\Http\Controllers\ProductController::class, 'destroyImage'])->name('products.images.destroy');
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

// Support / Donation Page
Route::get('/support', [\App\Http\Controllers\SupportController::class, 'index'])->name('support.index');

// Public Storefront Routes with Rate Limiting (MUST be after check-slug)
Route::get('/shop/{slug}', [\App\Http\Controllers\ShopController::class, 'show'])->name('shop.show');
Route::get('/shops/{shop:slug}/products/{product}', [\App\Http\Controllers\BuyerProductController::class, 'show'])->name('buyer.product.show');

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

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

    Route::get('/', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');

    // Sellers
    Route::get('/sellers', [\App\Http\Controllers\Admin\AdminSellerController::class, 'index'])->name('sellers.index');
    Route::get('/sellers/{user}', [\App\Http\Controllers\Admin\AdminSellerController::class, 'show'])->name('sellers.show');
    Route::delete('/sellers/{user}', [\App\Http\Controllers\Admin\AdminSellerController::class, 'destroy'])->name('sellers.destroy');

    // Shops
    Route::get('/shops', [\App\Http\Controllers\Admin\AdminShopController::class, 'index'])->name('shops.index');
    Route::get('/shops/{shop}', [\App\Http\Controllers\Admin\AdminShopController::class, 'show'])->name('shops.show');
    Route::patch('/shops/{shop}', [\App\Http\Controllers\Admin\AdminShopController::class, 'update'])->name('shops.update');
    Route::delete('/shops/{shop}', [\App\Http\Controllers\Admin\AdminShopController::class, 'destroy'])->name('shops.destroy');

    // Products
    Route::get('/products', [\App\Http\Controllers\Admin\AdminProductController::class, 'index'])->name('products.index');
    Route::delete('/products/{product}', [\App\Http\Controllers\Admin\AdminProductController::class, 'destroy'])->name('products.destroy');

    // Promo Codes
    Route::get('/promo-codes', [\App\Http\Controllers\Admin\AdminPromoCodeController::class, 'index'])->name('promo-codes.index');
    Route::delete('/promo-codes/{promoCode}', [\App\Http\Controllers\Admin\AdminPromoCodeController::class, 'destroy'])->name('promo-codes.destroy');

    // Feedbacks
    Route::get('/feedbacks', [\App\Http\Controllers\Admin\AdminFeedbackController::class, 'index'])->name('feedbacks.index');

    // Payment Methods
    Route::get('/payment-methods', [\App\Http\Controllers\Admin\AdminPaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::post('/payment-methods', [\App\Http\Controllers\Admin\AdminPaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::patch('/payment-methods/{pm}', [\App\Http\Controllers\Admin\AdminPaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::delete('/payment-methods/{pm}', [\App\Http\Controllers\Admin\AdminPaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
});
