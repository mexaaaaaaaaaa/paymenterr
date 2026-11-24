<?php

namespace Paymenter\Extensions\Others\Smm\Console\Commands;

use Illuminate\Console\Command;
use Paymenter\Extensions\Others\Smm\Models\SmmOrder;
use Paymenter\Extensions\Others\Smm\Jobs\SyncOrderStatusJob;

class SyncSmmOrdersCommand extends Command
{
    protected $signature = 'smm:sync-orders';
    protected $description = 'Synchronize SMM order statuses with the provider.';

    public function handle(): int
    {
        $orders = SmmOrder::where('provider_order_id', '!=', null)
            ->whereNotIn('status', [
                SmmOrder::STATUS_COMPLETED,
                SmmOrder::STATUS_CANCELED,
                SmmOrder::STATUS_FAILED,
                SmmOrder::STATUS_REFUNDED,
            ])
            ->get();

        foreach ($orders as $order) {
            dispatch(new SyncOrderStatusJob($order->id))->onConnection('database');
        }

        $this->info("Dispatched status sync for {$orders->count()} orders.");
        return 0;
    }
}
