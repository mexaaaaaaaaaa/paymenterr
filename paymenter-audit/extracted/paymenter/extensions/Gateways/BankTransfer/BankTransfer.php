<?php

namespace Paymenter\Extensions\Gateways\BankTransfer;

use App\Attributes\ExtensionMeta;
use App\Classes\Extension\Gateway;
use App\Helpers\ExtensionHelper;
use App\Models\Invoice;
use App\Models\Credit;
use App\Models\Service;
use Illuminate\Support\Facades\View;

#[ExtensionMeta(
    name: 'Bank Transfer Gateway',
    description: 'Payments by manual bank transfer with instructions and reference and discount system',
    version: '1.0.0',
    author: 'Fliqs',
    icon: 'data:image/svg+xml;base64,'
)]
class BankTransfer extends Gateway
{
    public function boot()
    {
        if (file_exists(__DIR__ . '/routes.php')) {
            require __DIR__ . '/routes.php';
        }
        View::addNamespace('gateways.banktransfer', __DIR__ . '/resources/views');
    }

    public function supportsBillingAgreements(): bool
    {
        // Bank transfer gateway does not support billing agreements
        // as it's a manual payment method
        return false;
    }

    public function createBillingAgreement(\App\Models\User $user)
    {
        // Bank transfer is a manual payment method and doesn't support
        // automatic billing agreements
        throw new \Exception('Billing agreement creation not supported for Bank Transfer gateway');
    }

    public function cancelBillingAgreement(\App\Models\BillingAgreement $billingAgreement): bool
    {
        // Bank transfer is a manual payment method and doesn't support
        // automatic billing agreements
        throw new \Exception('Billing agreement cancellation not supported for Bank Transfer gateway');
    }

    public function charge(Invoice $invoice, $total, \App\Models\BillingAgreement $billingAgreement): bool
    {
        // Bank transfer is a manual payment method and doesn't support
        // automatic billing agreement charges
        throw new \Exception('Billing agreement charge not supported for Bank Transfer gateway');
    }

    public function getConfig($values = [])
    {
        return [
            [
                'name' => 'account_holder',
                'label' => 'account holder',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'bank_name',
                'label' => 'bank name',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'iban',
                'label' => 'International Bank Account Number',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'bic',
                'label' => 'Bank Identifier Code',
                'type' => 'text',
                'required' => false,
            ],
            [
                'name' => 'bank_address',
                'label' => 'bank address',
                'type' => 'textarea',
                'required' => false,
            ],
            [
                'name' => 'instructions',
                'label' => 'additional instructions',
                'type' => 'textarea',
                'required' => false,
                'placeholder' => 'additional instructions',
            ],
            [
                'name' => 'days_to_pay',
                'label' => 'payment deadline (days)',
                'type' => 'number',
                'required' => false,
            ],
            [
                'name' => 'reference_prefix',
                'label' => 'reference prefix',
                'type' => 'text',
                'required' => false,
                'placeholder' => 'INV-',
            ],
            [
                'name' => 'discount_enabled',
                'label' => 'enable 5% bank transfer discount',
                'type' => 'checkbox',
                'required' => false,
                'database_type' => 'boolean',
            ],
            [
                'name' => 'discount_percentage',
                'label' => 'discount percentage',
                'type' => 'number',
                'required' => false,
            ],
            [
                'name' => 'discount_exclude_products',
                'label' => 'exclude products from discount (IDs, comma-separated)',
                'type' => 'textarea',
                'required' => false,
                'placeholder' => 'e.g. 12,34,56',
            ],
        ];
    }

    public function pay(Invoice $invoice, $total)
    {
        $prefix = $this->config('reference_prefix') ?: 'INV-';
        $reference = $invoice->number ?: ($prefix . $invoice->id);

        $discountEnabled = $this->config('discount_enabled');
        $discountEnabled = is_null($discountEnabled) ? false : (bool) $discountEnabled;
        $percentage = $this->config('discount_percentage');
        $percentage = is_null($percentage) ? 5 : (float) $percentage;
        $percentage = max(0, min(100, $percentage));
        if ($invoice->items()->where('reference_type', Credit::class)->exists()) {
            $discountEnabled = false;
        }
        $excludeRaw = (string) ($this->config('discount_exclude_products') ?? '');
        $excludeIds = collect(explode(',', $excludeRaw))
            ->map(fn ($v) => (int) trim($v))
            ->filter(fn ($v) => $v > 0)
            ->values()
            ->all();

        if (!empty($excludeIds)) {
            foreach ($invoice->items as $item) {
                if ($item->reference_type === Service::class) {
                    $service = $item->reference;
                    if ($service && $service->product && in_array($service->product->id, $excludeIds)) {
                        $discountEnabled = false;
                        break;
                    }
                }
            }
        }
        $discountAmount = $discountEnabled ? round($total * ($percentage / 100), 2) : 0;
        $discountedTotal = round($total - $discountAmount, 2);

        ExtensionHelper::addProcessingPayment($invoice->id, 'BankTransfer', $discountedTotal, null, $reference);

        return view('gateways.banktransfer::pay', [
            'invoice' => $invoice,
            'total' => $discountedTotal,
            'originalTotal' => $total,
            'discountAmount' => $discountAmount,
            'discountPercentage' => $percentage,
            'currency' => $invoice->currency_code,
            'reference' => $reference,
            'accountHolder' => $this->config('account_holder'),
            'bankName' => $this->config('bank_name'),
            'iban' => $this->config('iban'),
            'bic' => $this->config('bic'),
            'bankAddress' => $this->config('bank_address'),
            'instructions' => $this->config('instructions'),
            'daysToPay' => $this->config('days_to_pay'),
        ]);
    }
}