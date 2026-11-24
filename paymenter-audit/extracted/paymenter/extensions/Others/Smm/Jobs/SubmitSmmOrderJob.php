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

class SubmitSmmOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public int $smmOrderId) {}

    public function handle(OrderService $orderService): void
    {
        $order = SmmOrder::find($this->smmOrderId);
        if (!$order) {
            Log::warning('SMM order #'.$this->smmOrderId.' not found for submission job.');
            return;
        }

        if (!$order->canBeSubmittedToProvider()) {
            Log::info('SMM order '.$order->order_code.' not submittable (status: '.$order->status.').');
            return;
        }

        try {
            $orderService->submitToProvider($order);
        } catch (\Throwable $e) {
            Log::error('SMM submission job failed for order '.$order->order_code.': '.$e->getMessage());
            $order->update(['status' => SmmOrder::STATUS_FAILED]);
            $orderService->recordStatusHistory($order, $order->getOriginal('status'), SmmOrder::STATUS_FAILED, 'job_exception', $e->getMessage());
            throw $e;
        }
    }
}
