<?php

namespace App\Livewire;

use App\Models\StatusIncident;
use App\Services\StatusMonitor;
use Livewire\Component;

class Status extends Component
{
    public function render(StatusMonitor $monitor)
    {
        $services = StatusMonitor::SERVICES;
        $live = $monitor->live();
        $history = $monitor->history();

        $incidents = StatusIncident::published()
            ->orderByDesc('started_at')
            ->limit(20)
            ->get();

        $overall = $monitor->overallStatus($live, $incidents);

        return view('status', [
            'services' => $services,
            'live' => $live,
            'history' => $history,
            'incidents' => $incidents,
            'overall' => $overall,
        ]);
    }
}
