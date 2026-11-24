<?php

use Illuminate\Support\Facades\Route;
use Paymenter\Extensions\Others\MagicLink\Http\Controllers\MagicLinkController;

Route::middleware('web')->group(function () {
    Route::get('/login/magiclink', [MagicLinkController::class, 'showForm'])
        ->name('magiclink.form');

    Route::post('/login/magiclink/send', [MagicLinkController::class, 'sendLink'])
        ->name('magiclink.send');

    Route::get('/login/magiclink/callback', [MagicLinkController::class, 'login'])
        ->name('magiclink.login');
});
