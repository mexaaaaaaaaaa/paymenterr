<?php

namespace Paymenter\Extensions\Gateways\Mollie;

use App\Classes\Extension\Gateway;
use App\Helpers\ExtensionHelper;
use App\Models\Invoice;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Mollie extends Gateway
{
    public function boot()
    {
        require __DIR__ . '/routes.php';
        // Register webhook route
    }

    public function supportsBillingAgreements(): bool
    {
        // Mollie does support payment methods that can be used for recurring payments
        // but for simplicity, we'll return false unless specifically configured
        return false;
    }

    public function createBillingAgreement(User $user)
    {
        // Mollie requires creating a payment method first, then creating a mandate
        // For now, we'll return a placeholder or throw an exception indicating
        // that this needs to be implemented based on specific business requirements
        throw new \Exception('Billing agreement creation not implemented for Mollie gateway');
    }

    public function cancelBillingAgreement(BillingAgreement $billingAgreement): bool
    {
        // Mollie mandates can be revoked
        // For now, we'll return a placeholder or throw an exception indicating
        // that this needs to be implemented based on specific business requirements
        throw new \Exception('Billing agreement cancellation not implemented for Mollie gateway');
    }

    public function charge(Invoice $invoice, $total, BillingAgreement $billingAgreement): bool
    {
        // Mollie can charge using mandates (payment methods)
        // For now, we'll return a placeholder or throw an exception indicating
        // that this needs to be implemented based on specific business requirements
        throw new \Exception('Billing agreement charge not implemented for Mollie gateway');
    }

    private function request($url, $method = 'get', $data = [])
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->config('api_key'),
            'Content-Type' => 'application/json',
        ])->$method('https://api.mollie.com' . $url, $data);

        if (!$response->successful()) {
            throw new Exception('Mollie API error: ' . $response->json()['detail']);
        }

        return $response->json();
    }

    /**
     * Get all the configuration for the extension
     *
     * @param  array  $values
     * @return array
     */
    public function getConfig($values = [])
    {
        return [
            [
                'name' => 'api_key',
                'label' => 'API Key',
                'type' => 'text',
                'encrypted' => true,
                'required' => true,
            ],
        ];
    }

    /**
     * Return a view or a url to redirect to
     *
     * @param  float  $total
     * @return string
     */
    public function pay(Invoice $invoice, $total)
    {
        $response = $this->request('/v2/payments', 'post', [
            'amount' => [
                'currency' => $invoice->currency_code,
                'value' => number_format($total, 2, '.', ''),
            ],
            'description' => 'Invoice #' . $invoice->id,
            'redirectUrl' => route('invoices.show', $invoice) . '?checkPayment=true',
            'cancelUrl' => route('invoices.show', $invoice),
            'webhookUrl' => route('extensions.gateways.mollie.webhook', $invoice),
            'metadata' => [
                'invoice_id' => $invoice->id,
            ],
        ]);

        return $response['_links']['checkout']['href'];
    }

    public function webhook(Request $request)
    {
        $payment = $this->request('/v2/payments/' . $request->input('id'));

        if ($payment['status'] == 'paid') {
            ExtensionHelper::addPayment($payment['metadata']['invoice_id'], 'Mollie', $payment['amount']['value'], transactionId: $payment['id']);
        }
    }
}
