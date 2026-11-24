<?php

namespace App\Console\Commands;

use App\Services\StatusMonitor;
use Illuminate\Console\Command;

class StatusCheck extends Command
{
    protected $signature = 'status:check';

    protected $description = 'Run ZyperNodes service health checks and record history';

    public function handle(StatusMonitor $monitor): int
    {
        $count = $monitor->record();
        $this->info("Recorded {$count} service checks.");

        return self::SUCCESS;
    }
}
