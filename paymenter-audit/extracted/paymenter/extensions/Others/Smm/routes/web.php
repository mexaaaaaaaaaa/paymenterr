<?php

use Illuminate\Support\Facades\Route;
use Paymenter\Extensions\Others\Smm\Http\Controllers\WebhookController;

// Storefront
Route::middleware(['web'])->group(function () {
    Route::get('/category/smm/{slug?}', \Paymenter\Extensions\Others\Smm\Livewire\Storefront\Category::class)->name('smm.category');
    // Platform aliases per requested URL structure
    Route::get('/productslist/{slug}', \Paymenter\Extensions\Others\Smm\Livewire\Storefront\Category::class)->name('smm.platform');
    Route::get('/smm/service/{slug}', \Paymenter\Extensions\Others\Smm\Livewire\Storefront\Service::class)->name('smm.service');
    Route::get('/smm/cart', \Paymenter\Extensions\Others\Smm\Livewire\Storefront\Cart::class)->name('smm.cart');
    Route::get('/smm/checkout', \Paymenter\Extensions\Others\Smm\Livewire\Storefront\Checkout::class)->name('smm.checkout')->middleware('auth');
});

// Customer dashboard (ownership enforced in components)
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/account/smm-orders', \Paymenter\Extensions\Others\Smm\Livewire\Customer\Orders::class)->name('smm.customer.orders');
    Route::get('/account/smm-orders/{order}', \Paymenter\Extensions\Others\Smm\Livewire\Customer\OrderShow::class)->name('smm.customer.orders.show');
});

// Provider webhook (signature verified in controller; CSRF exempt)
Route::post('/extensions/smm/webhook', [WebhookController::class, 'handle'])->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)->name('smm.webhook');
