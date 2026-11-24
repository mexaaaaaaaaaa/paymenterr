<?php

namespace Paymenter\Extensions\Others\Smm\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Paymenter\Extensions\Others\Smm\Models\SmmOrder;
use Paymenter\Extensions\Others\Smm\Services\OrderService;
use Paymenter\Extensions\Others\Smm\Models\SmmProvider;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $provider = SmmProvider::where('is_default', true)->where('is_active', true)->first();
        if (!$provider) {
            return response('No provider configured', 400);
        }

        $payload = $request->getContent();
        $signature = $request->header('X-Signature') ?: $request->header('X-Hmac-Signature') ?: '';

        // Verify signature if a webhook secret is configured
        $secret = $provider->api_key; // fallback: use API key material for HMAC
        $valid = false;
        if ($signature && $secret) {
            $valid = hash_equals($signature, hash_hmac('sha256', $payload, $secret));
        }

        Log::info('SMM webhook received', ['signature_valid' => $valid, 'payload' => $payload]);

        $data = json_decode($payload, true) ?? [];

        // EasySMMPanel-style webhook: order, status
        $providerOrderId = $data['order'] ?? $data['order_id'] ?? $data['id'] ?? null;
        $status = $data['status'] ?? null;

        if ($providerOrderId && $status) {
            $order = SmmOrder::where('provider_order_id', $providerOrderId)->first();
            if ($order) {
                (new OrderService())->syncOrderStatus($order);
            }
        }

        return response('OK');
    }
}
