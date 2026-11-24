<?php

namespace Paymenter\Extensions\Others\Smm\Services;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Paymenter\Extensions\Others\Smm\Models\SmmOrder;
use Paymenter\Extensions\Others\Smm\Models\SmmOrderItem;
use Paymenter\Extensions\Others\Smm\Models\SmmProvider;
use Paymenter\Extensions\Others\Smm\Models\SmmService;
use Paymenter\Extensions\Others\Smm\Models\SmmTransaction;
use Paymenter\Extensions\Others\Smm\Models\SmmOrderStatusHistory;
use Illuminate\Support\Str;

class OrderService
{
    public function createPendingOrder(User $user, SmmService $service, int $quantity, string $targetUrl, array $params = [], ?Invoice $invoice = null): SmmOrder
    {
        $pricing = PricingEngine::calculateTotal($service, $quantity, $service->provider_currency);

        return DB::transaction(function () use ($user, $service, $quantity, $targetUrl, $params, $invoice, $pricing) {
            $order = SmmOrder::create([
                'order_code' => 'SMM'.strtoupper(Str::random(12)),
                'user_id' => $user->id,
                'invoice_id' => $invoice?->id,
                'provider_id' => $service->provider_id,
                'status' => SmmOrder::STATUS_PENDING_PAYMENT,
                'target_url' => $targetUrl,
                'quantity' => $quantity,
                'quantity_started' => 0,
                'quantity_remaining' => $quantity,
                'amount' => $pricing['total'],
                'currency_code' => $pricing['currency'],
                'request_params' => $params,
                'provider_response' => null,
            ]);

            SmmOrderItem::create([
                'smm_order_id' => $order->id,
                'smm_service_id' => $service->id,
                'target_url' => $targetUrl,
                'quantity' => $quantity,
                'provider_cost' => $service->provider_cost ?? 0,
                'amount' => $pricing['total'],
                'currency_code' => $pricing['currency'],
                'params' => $params,
            ]);

            $this->recordStatusHistory($order, null, SmmOrder::STATUS_PENDING_PAYMENT, 'create');

            return $order->fresh();
        });
    }

    public function markPaid(SmmOrder $order): void
    {
        if ($order->status === SmmOrder::STATUS_PAID) {
            return; // idempotent
        }

        $order->update(['status' => SmmOrder::STATUS_PAID]);
        $this->recordStatusHistory($order, $order->getOriginal('status'), SmmOrder::STATUS_PAID, 'payment_verified');

        // Submit to provider asynchronously
        dispatch(new \Paymenter\Extensions\Others\Smm\Jobs\SubmitSmmOrderJob($order->id));
    }

    public function submitToProvider(SmmOrder $order): array
    {
        if (!$order->canBeSubmittedToProvider()) {
            return ['success' => false, 'message' => 'Order not in a submittable status.'];
        }

        $api = EasySmmpanelApi::forDefault();
        $service = $order->items->first()->service;

        $data = $this->buildProviderPayload($order, $service);

        $result = $api->request('add', $data);

        if (!$result['success']) {
            $order->update(['status' => SmmOrder::STATUS_FAILED]);
            $this->recordStatusHistory($order, SmmOrder::STATUS_PAID, SmmOrder::STATUS_FAILED, 'provider_error', $result['error'] ?? 'unknown');
            Log::warning('SMM provider submission failed for order '.$order->order_code.': '.($result['error'] ?? 'unknown'));

            return ['success' => false, 'message' => $result['error'] ?? 'Provider API error'];
        }

        $resp = $result['data'] ?? [];
        $providerOrderId = $resp['order'] ?? $resp['order_id'] ?? $resp['id'] ?? null;

        if (!$providerOrderId) {
            $order->update(['status' => SmmOrder::STATUS_FAILED]);
            $this->recordStatusHistory($order, SmmOrder::STATUS_PAID, SmmOrder::STATUS_FAILED, 'provider_error', 'No order id returned');
            return ['success' => false, 'message' => 'Provider did not return an order ID.'];
        }

        $order->update([
            'status' => SmmOrder::STATUS_PROCESSING,
            'provider_order_id' => (string) $providerOrderId,
            'provider_response' => $resp,
            'submitted_to_provider_at' => now(),
        ]);

        $this->recordStatusHistory($order, SmmOrder::STATUS_PAID, SmmOrder::STATUS_PROCESSING, 'provider_submitted');

        Log::info('SMM order '.$order->order_code.' submitted to provider as #'.$providerOrderId);

        return ['success' => true, 'provider_order_id' => $providerOrderId, 'response' => $resp];
    }

    public function syncOrderStatus(SmmOrder $order): array
    {
        if (!$order->provider_order_id || $order->isTerminal()) {
            return ['success' => true, 'skipped' => true];
        }

        $api = EasySmmpanelApi::forDefault();
        $result = $api->request('status', ['order' => $order->provider_order_id]);

        if (!$result['success']) {
            return $result;
        }

        $resp = $result['data'] ?? [];
        $status = $this->mapProviderStatus($resp['status'] ?? $resp['Status'] ?? null);

        if (!$status) {
            return ['success' => true, 'message' => 'Unrecognized provider status.'];
        }

        $previous = $order->status;
        if ($status !== $previous) {
            $order->update(['status' => $status]);
            $this->recordStatusHistory($order, $previous, $status, 'provider_status_sync');

            if ($status === SmmOrder::STATUS_COMPLETED) {
                $order->update(['completed_at' => now()]);
            }

            // Notify customer
            $this->notifyStatusChange($order, $status);
        }

        return ['success' => true, 'status' => $status, 'previous' => $previous, 'raw' => $resp];
    }

    public function requestRefill(SmmOrder $order): array
    {
        $service = $order->items->first()->service;
        if (!$service->refill_eligible) {
            return ['success' => false, 'message' => 'Refill not eligible for this service.'];
        }

        $api = EasySmmpanelApi::forDefault();
        $result = $api->request('refill', ['order' => $order->provider_order_id]);

        if (!$result['success']) {
            return $result;
        }

        $resp = $result['data'] ?? [];
        $refillId = $resp['refill'] ?? $resp['refill_id'] ?? null;

        \Paymenter\Extensions\Others\Smm\Models\SmmRefill::create([
            'smm_order_id' => $order->id,
            'provider_refill_id' => $refillId ? (string) $refillId : null,
            'status' => $refillId ? 'processing' : 'pending',
            'quantity' => 0,
            'reason' => 'Customer refill request',
        ]);

        return ['success' => true, 'message' => 'Refill submitted.'];
    }

    public function requestCancellation(SmmOrder $order): array
    {
        $service = $order->items->first()->service;
        if (!$service->cancel_eligible) {
            return ['success' => false, 'message' => 'Cancellation not eligible for this service.'];
        }

        $api = EasySmmpanelApi::forDefault();
        $result = $api->request('cancel', ['order' => $order->provider_order_id]);

        if (!$result['success']) {
            return $result;
        }

        $order->update(['status' => SmmOrder::STATUS_CANCELED]);
        $this->recordStatusHistory($order, $order->getOriginal('status'), SmmOrder::STATUS_CANCELED, 'customer_cancellation');

        return ['success' => true, 'message' => 'Cancellation requested.'];
    }

    protected function buildProviderPayload(SmmOrder $order, SmmService $service): array
    {
        $params = array_merge([
            'service' => $service->provider_service_id,
            'link' => $order->target_url,
            'quantity' => $order->quantity,
        ], (array) $order->request_params);

        return $params;
    }

    protected function mapProviderStatus($raw): ?string
    {
        $mapping = [
            'pending' => SmmOrder::STATUS_QUEUED,
            'processing' => SmmOrder::STATUS_PROCESSING,
            'in progress' => SmmOrder::STATUS_IN_PROGRESS,
            'completed' => SmmOrder::STATUS_COMPLETED,
            'partial' => SmmOrder::STATUS_PARTIAL,
            'canceled' => SmmOrder::STATUS_CANCELED,
            'cancelled' => SmmOrder::STATUS_CANCELED,
            'failed' => SmmOrder::STATUS_FAILED,
            'error' => SmmOrder::STATUS_FAILED,
        ];

        $key = strtolower(trim((string) $raw));
        return $mapping[$key] ?? null;
    }

    protected function recordStatusHistory(SmmOrder $order, ?string $from, string $to, string $source, ?string $note = null): void
    {
        SmmOrderStatusHistory::create([
            'smm_order_id' => $order->id,
            'from_status' => $from,
            'to_status' => $to,
            'note' => $note,
            'source' => $source,
        ]);
    }

    protected function notifyStatusChange(SmmOrder $order, string $status): void
    {
        Log::info('SMM order '.$order->order_code.' status changed to '.$status);
    }
}
