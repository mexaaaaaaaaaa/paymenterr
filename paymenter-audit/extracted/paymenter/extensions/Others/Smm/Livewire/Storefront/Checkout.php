<?php

namespace Paymenter\Extensions\Others\Smm\Livewire\Storefront;

use App\Livewire\Component;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Paymenter\Extensions\Others\Smm\Models\SmmOrder;
use Paymenter\Extensions\Others\Smm\Models\SmmOrderItem;
use Paymenter\Extensions\Others\Smm\Services\SmmCart;
use Paymenter\Extensions\Others\Smm\Services\OrderService;

class Checkout extends Component
{
    public $items = [];
    public $total = '0';
    public $currency = 'INR';
    public $tos = false;

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->guest('login');
        }

        $data = SmmCart::total();
        if ($data['count'] === 0) {
            return $this->redirect(route('smm.cart'), navigate: true);
        }

        $this->items = $data['items'];
        $this->total = $data['total'];
        $this->currency = !empty($this->items) ? ($this->items[array_key_first($this->items)]['currency'] ?? 'INR') : 'INR';
    }

    public function placeOrder()
    {
        if (!Auth::check()) {
            return redirect()->guest('login');
        }

        $data = SmmCart::total();
        if ($data['count'] === 0) {
            $this->notify(__('smm::messages.cart_empty'), 'error');
            return $this->redirect(route('smm.cart'), navigate: true);
        }

        if (config('settings.tos') && !$this->tos) {
            $this->notify(__('smm::messages.accept_tos'), 'error');
            return;
        }

        $user = User::find(Auth::id());

        DB::beginTransaction();
        try {
            $invoice = Invoice::create([
                'user_id' => $user->id,
                'due_at' => now()->addDays(7),
                'currency_code' => $this->currency,
                'send_create_email' => true,
            ]);

            foreach ($data['items'] as $item) {
                $invoice->items()->create([
                    'quantity' => 1,
                    'price' => $item['total'],
                    'description' => $item['name'].' ('.$item['target_url'].')',
                    'reference_type' => SmmOrder::class,
                    'reference_id' => null, // filled after order creation
                ]);
            }

            $invoice->save();

            $smmOrder = SmmOrder::create([
                'order_code' => 'SMM'.strtoupper(\Illuminate\Support\Str::random(12)),
                'user_id' => $user->id,
                'invoice_id' => $invoice->id,
                'provider_id' => null,
                'status' => SmmOrder::STATUS_PENDING_PAYMENT,
                'target_url' => $data['items'][0]['target_url'] ?? '',
                'quantity' => $data['count'],
                'quantity_started' => 0,
                'quantity_remaining' => $data['count'],
                'amount' => $data['total'],
                'currency_code' => $this->currency,
                'request_params' => [],
            ]);

            foreach ($data['items'] as $item) {
                $service = \Paymenter\Extensions\Others\Smm\Models\SmmService::find($item['service_id']);
                SmmOrderItem::create([
                    'smm_order_id' => $smmOrder->id,
                    'smm_service_id' => $item['service_id'],
                    'target_url' => $item['target_url'],
                    'quantity' => $item['quantity'],
                    'provider_cost' => $service?->provider_cost ?? 0,
                    'amount' => $item['total'],
                    'currency_code' => $item['currency'],
                    'params' => $item['params'] ?? [],
                ]);

                // Link invoice item back to smm order
                $invItem = $invoice->items()->where('description', 'like', '%'.$item['name'].'%')->whereNull('reference_id')->first();
                if ($invItem) {
                    $invItem->update(['reference_id' => $smmOrder->id]);
                }
            }

            DB::commit();

            SmmCart::clear();

            $this->notify(__('smm::messages.invoice_created'), 'success');

            return $this->redirect(route('invoices.show', [$invoice, 'pay' => true]), navigate: true);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('SMM checkout failed: '.$e->getMessage());
            $this->notify(__('smm::messages.checkout_error'), 'error');
        }
    }

    public function render()
    {
        return view('smm::storefront.checkout', [
            'items' => $this->items,
        ]);
    }
}
