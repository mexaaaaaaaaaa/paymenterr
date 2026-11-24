<?php

namespace Paymenter\Extensions\Others\Smm\Listeners;

use App\Events\Invoice\Paid as InvoicePaid;
use Illuminate\Support\Facades\Log;
use Paymenter\Extensions\Others\Smm\Models\SmmOrder;
use Paymenter\Extensions\Others\Smm\Services\OrderService;

class SubmitOrderOnInvoicePaid
{
    public function handle(InvoicePaid $event): void
    {
        $invoice = $event->invoice;

        // Find SMM orders linked to this invoice that are still pending payment
        $orders = SmmOrder::where('invoice_id', $invoice->id)
            ->where('status', SmmOrder::STATUS_PENDING_PAYMENT)
            ->get();

        foreach ($orders as $order) {
            try {
                (new OrderService())->markPaid($order);
                Log::info('SMM order '.$order->order_code.' marked paid and queued for provider submission.');
            } catch (\Throwable $e) {
                Log::error('Failed to mark SMM order '.$order->order_code.' paid: '.$e->getMessage());
            }
        }
    }
}
