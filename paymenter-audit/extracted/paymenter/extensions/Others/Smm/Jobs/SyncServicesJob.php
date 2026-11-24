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

class SyncServicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(\Paymenter\Extensions\Others\Smm\Services\ServiceImporter $importer): void
    {
        try {
            $result = $importer->import();
            Log::info('SMM service sync completed: '.$result['message']);
        } catch (\Throwable $e) {
            Log::error('SMM service sync job failed: '.$e->getMessage());
        }
    }
}
