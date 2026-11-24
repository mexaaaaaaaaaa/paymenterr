<?php

namespace Paymenter\Extensions\Others\Smm\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Paymenter\Extensions\Others\Smm\Models\SmmOrder;
use Paymenter\Extensions\Others\Smm\Services\OrderService;

class SyncOrderStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(public int $smmOrderId) {}

    public function handle(OrderService $orderService): void
    {
        $order = SmmOrder::find($this->smmOrderId);
        if (!$order) {
            return;
        }

        if ($order->isTerminal() || !$order->provider_order_id) {
            return;
        }

        try {
            $orderService->syncOrderStatus($order);
        } catch (\Throwable $e) {
            Log::error('SMM status sync failed for order '.$order->order_code.': '.$e->getMessage());
        }
    }
}
