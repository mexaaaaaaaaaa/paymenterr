<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Paymenter\Extensions\Gateways\YouPIPG\YouPIPG;

// YouPI-PG sends one merchant-level webhook (configured in merchant admin),
// so the invoice is resolved from the signed payload, not the URL.
Route::post('/extensions/gateways/youpipg/webhook', [YouPIPG::class, 'webhook'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('extensions.gateways.youpipg.webhook');
