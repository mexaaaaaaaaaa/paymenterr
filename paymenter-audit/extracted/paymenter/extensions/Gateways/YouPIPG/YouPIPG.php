<?php

namespace Paymenter\Extensions\Gateways\YouPIPG;

use App\Attributes\ExtensionMeta;
use App\Classes\Extension\Gateway;
use App\Helpers\ExtensionHelper;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

#[ExtensionMeta(
    name: 'YouPI-PG',
    description: 'UPI payments via the self-hosted YouPI-PG gateway (HMAC-SHA256 signed webhooks)',
    version: '1.0.0',
    author: 'ZyperNodes',
    icon: 'data:image/svg+xml;base64,'
)]
class YouPIPG extends Gateway
{
    /** Public payment outcomes of YouPI-PG (docs/MERCHANT_INTEGRATION.md). */
    public const EVENT_SUCCESS = 'SUCCESS';
    public const EVENT_FAILED = 'FAILED';
    public const EVENT_EXPIRED = 'EXPIRED';
    public const EVENT_CANCELLED = 'CANCELLED';

    public function boot()
    {
        if (file_exists(__DIR__ . '/routes.php')) {
            require __DIR__ . '/routes.php';
        }
        View::addNamespace('gateways.youpipg', __DIR__ . '/resources/views');
    }

    public function supportsBillingAgreements(): bool
    {
        return false;
    }

    public function createBillingAgreement(\App\Models\User $user)
    {
        throw new \Exception('Billing agreements are not supported by YouPI-PG');
    }

    public function cancelBillingAgreement(\App\Models\BillingAgreement $billingAgreement): bool
    {
        throw new \Exception('Billing agreement cancellation is not supported by YouPI-PG');
    }

    public function charge(Invoice $invoice, $total, \App\Models\BillingAgreement $billingAgreement): bool
    {
        throw new \Exception('Billing agreement charges are not supported by YouPI-PG');
    }

    /**
     * Availability: YouPI-PG settles INR UPI payments only.
     */
    public function canUseGateway($total, $currency, $type, $items = [])
    {
        $allowed = array_filter(array_map('trim', explode(',', (string) $this->config('currencies', 'INR'))));
        if (!empty($allowed) && !in_array($currency, $allowed)) {
            return false;
        }
        $max = (float) $this->config('max_amount', 0);
        if ($max > 0 && $total > $max) {
            return false;
        }

        return true;
    }

    /**
     * Create a YouPI-PG payment session and redirect to the hosted checkout.
     * Contract (apps/payment-api/src/routes/payments.routes.js):
     *   POST {api_url}/api/v1/payments
     *   Headers: X-Merchant-Id, X-Merchant-Key (webhook secret)
     *   Body: { orderId, amount: "200.00", currency, returnUrl, metadata }
     *   Response: { paymentId, orderId, amount, amountPaise, currency, status, checkoutUrl }
     */
    public function pay(Invoice $invoice, $total)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Merchant-Id' => (string) $this->config('merchant_id'),
            'X-Merchant-Key' => (string) $this->config('webhook_secret'),
        ])->post(rtrim((string) $this->config('api_url'), '/') . '/api/v1/payments', [
            'orderId' => $this->orderId($invoice),
            'amount' => number_format((float) $total, 2, '.', ''),
            'currency' => $invoice->currency_code,
            'returnUrl' => route('invoices.show', $invoice) . '?checkPayment=true',
            'metadata' => [
                'invoice_id' => $invoice->id,
            ],
        ]);

        if (!$response->successful()) {
            Log::error('YouPI-PG: create payment failed', [
                'invoice' => $invoice->id,
                'status' => $response->status(),
                'body' => substr((string) $response->body(), 0, 300),
            ]);
            throw new \Exception('YouPI-PG payment creation failed (' . $response->status() . ').');
        }

        $data = $response->json();
        $checkoutUrl = $data['checkoutUrl'] ?? null;
        if (!$checkoutUrl) {
            Log::error('YouPI-PG: response missing checkoutUrl', ['invoice' => $invoice->id]);
            throw new \Exception('YouPI-PG did not return a checkout URL.');
        }

        return $checkoutUrl;
    }

    /**
     * Webhook — the authoritative, HMAC-SHA256 signed server-to-server event.
     * Verification invariants (docs/DEVELOPER_INTEGRATION.md):
     *   - HMAC over the RAW request body, never parsed JSON.
     *   - Constant-time comparison of "sha256=<64-hex>".
     * The webhook URL is merchant-level (configured in YouPI-PG merchant admin),
     * so the invoice is resolved from the payload orderId, not from the route.
     */
    public function webhook(Request $request)
    {
        $rawBody = (string) $request->getContent();
        $signature = (string) $request->header('X-Gateway-Signature', '');

        $expected = 'sha256=' . hash_hmac('sha256', $rawBody, (string) $this->config('webhook_secret'));
        if ($signature === '' || !hash_equals($expected, $signature)) {
            Log::warning('YouPI-PG webhook: invalid signature');

            return response()->json(['error' => 'invalid signature'], 403);
        }

        $event = json_decode($rawBody, true);
        if (!is_array($event)) {
            return response()->json(['error' => 'invalid payload'], 400);
        }

        $type = (string) ($event['event'] ?? '');
        $orderId = (string) ($event['orderId'] ?? '');
        $invoice = $this->invoiceFromOrderId($orderId);

        if (!$invoice) {
            Log::warning('YouPI-PG webhook: unknown orderId', ['orderId' => $orderId]);

            // 200 so YouPI-PG does not retry forever on an event we cannot map.
            return response()->json(['received' => true, 'ignored' => 'unknown order']);
        }

        switch ($type) {
            case self::EVENT_SUCCESS:
                $this->settle($invoice, $event);

                break;
            case self::EVENT_FAILED:
            case self::EVENT_EXPIRED:
            case self::EVENT_CANCELLED:
                Log::info('YouPI-PG webhook: non-success outcome', [
                    'invoice' => $invoice->id, 'event' => $type,
                ]);

                break;
            default:
                Log::notice('YouPI-PG webhook: unknown event type', ['event' => $type]);
        }

        return response()->json(['received' => true]);
    }

    /**
     * Mark the invoice paid — with duplicate and amount guards.
     */
    private function settle(Invoice $invoice, array $event): void
    {
        $paymentId = (string) ($event['paymentId'] ?? '');
        $utr = (string) ($event['utr'] ?? '');
        $transactionId = $utr !== '' ? $utr : ($paymentId !== '' ? $paymentId : uniqid('youpi_'));

        // Duplicate-event guard: skip if this transaction was already recorded.
        $existing = $invoice->transactions()
            ->where('transaction_id', $transactionId)
            ->first();
        if ($existing) {
            Log::info('YouPI-PG webhook: duplicate event ignored', [
                'invoice' => $invoice->id, 'transaction_id' => $transactionId,
            ]);

            return;
        }

        // Amount guard: settle at most the invoice due amount.
        $amount = (float) ($event['amountPaise'] ?? 0) / 100;
        if ($amount <= 0) {
            $amount = (float) ($event['amount'] ?? 0);
        }
        if ($amount <= 0) {
            Log::warning('YouPI-PG webhook: SUCCESS without a usable amount', ['invoice' => $invoice->id]);

            return;
        }
        if ($amount - $invoice->due > 0.01) {
            Log::warning('YouPI-PG webhook: amount exceeds invoice due — settling due only', [
                'invoice' => $invoice->id, 'amount' => $amount, 'due' => (float) $invoice->due,
            ]);
            $amount = (float) $invoice->due;
        }

        ExtensionHelper::addPayment($invoice->id, 'YouPI-PG', $amount, transactionId: $transactionId);
        Log::info('YouPI-PG webhook: invoice settled', [
            'invoice' => $invoice->id, 'amount' => $amount, 'transaction_id' => $transactionId,
        ]);
    }

    /**
     * YouPI-PG is idempotent per merchant+orderId, so the orderId embeds the invoice id.
     */
    private function orderId(Invoice $invoice): string
    {
        return 'INV-' . $invoice->id;
    }

    private function invoiceFromOrderId(string $orderId): ?Invoice
    {
        if (!preg_match('/^INV-(\d+)$/', $orderId, $m)) {
            return null;
        }

        return Invoice::find((int) $m[1]);
    }

    public function getConfig($values = [])
    {
        return [
            [
                'name' => 'api_url',
                'label' => 'YouPI-PG API URL (e.g. https://pay.yourbusiness.com)',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'merchant_id',
                'label' => 'Merchant UUID (X-Merchant-Id)',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'webhook_secret',
                'label' => 'Webhook Secret (also used as X-Merchant-Key)',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'webhook_notice',
                'label' => 'Configure this webhook URL in YouPI-PG merchant admin: '
                    . rtrim(config('app.url'), '/') . '/extensions/gateways/youpipg/webhook',
                'type' => 'success',
                'required' => false,
            ],
            [
                'name' => 'currencies',
                'label' => 'Allowed currencies (comma separated, default INR)',
                'type' => 'text',
                'required' => false,
            ],
            [
                'name' => 'max_amount',
                'label' => 'Max amount (0 = no limit)',
                'type' => 'number',
                'required' => false,
            ],
        ];
    }
}
