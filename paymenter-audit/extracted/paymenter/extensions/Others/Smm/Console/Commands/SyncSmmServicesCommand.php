<?php

namespace Paymenter\Extensions\Others\Smm\Console\Commands;

use Illuminate\Console\Command;
use Paymenter\Extensions\Others\Smm\Services\ServiceImporter;
use Paymenter\Extensions\Others\Smm\Services\EasySmmpanelApi;
use Paymenter\Extensions\Others\Smm\Models\SmmProvider;
use Paymenter\Extensions\Others\Smm\Jobs\SyncServicesJob;
use Paymenter\Extensions\Others\Smm\Jobs\SyncOrderStatusJob;
use Paymenter\Extensions\Others\Smm\Models\SmmOrder;
use Illuminate\Support\Facades\Log;

class SyncSmmServicesCommand extends Command
{
    protected $signature = 'smm:sync-services';
    protected $description = 'Synchronize SMM services from EasySMMPanel provider.';

    public function handle(): int
    {
        $provider = SmmProvider::where('is_default', true)->where('is_active', true)->first();
        if (!$provider) {
            $this->error('No active default SMM provider configured.');
            return 1;
        }

        $api = EasySmmpanelApi::forDefault();
        $importer = new ServiceImporter($api);

        $result = $importer->import();

        $this->info($result['message']);
        $this->line("Imported: {$result['imported']}, Updated: {$result['updated']}");

        return 0;
    }
}
