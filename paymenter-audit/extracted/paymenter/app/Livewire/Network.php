<?php

namespace App\Livewire;

use Livewire\Component;

class Network extends Component
{
    public array $nodes = [];
    public string $query = '';
    public string $statusFilter = 'all';
    public ?string $selected = null;

    public function mount(): void
    {
        $this->nodes = $this->loadNodes();
    }

    /** Live node health re-check (real API call) */
    public function refresh(): void
    {
        $this->nodes = $this->loadNodes();
        $this->dispatch('nodes-updated');
    }

    public function select(string $uuid): void
    {
        $this->selected = $uuid;
    }

    private function loadNodes(): array
    {
        $server = \App\Models\Server::where('extension', 'Calagopus')->first();
        if (!$server) {
            return [];
        }
        $host = rtrim($server->settings->where('key', 'host')->value('value') ?? '', '/');
        $key = $server->settings->where('key', 'api_key')->value('value');
        if (!$host || !$key) {
            return [];
        }

        try {
            $resp = \Illuminate\Support\Facades\Http::withToken($key)->timeout(8)->get($host . '/api/admin/nodes?per_page=25');
            if ($resp->failed()) {
                return [];
            }
            return collect($resp->json('nodes.data') ?? [])->map(function ($n) use ($host, $key) {
                $overview = null;
                try {
                    $ov = \Illuminate\Support\Facades\Http::withToken($key)->timeout(6)->get($host . '/api/admin/nodes/' . $n['uuid'] . '/system/overview');
                    $overview = $ov->successful() ? $ov->json() : null;
                } catch (\Throwable $e) {
                    $overview = null;
                }

                // Extract location data - try to get coordinates if available
                $locationName = $n['location']['name'] ?? '—';
                $region = $n['location']['region'] ?? $n['location']['country'] ?? 'Unknown';

                // Try to get coordinates from node data if available
                $lat = $n['location']['latitude'] ?? null;
                $lng = $n['location']['longitude'] ?? null;

                // If coordinates not in location data, try top-level node data
                if ($lat === null || $lng === null) {
                    $lat = $n['latitude'] ?? null;
                    $lng = $n['longitude'] ?? null;
                }

                return [
                    'uuid' => $n['uuid'],
                    'name' => $n['name'],
                    'location' => $locationName,
                    'region' => $region,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'online' => $overview !== null,
                    'wings' => $overview['version'] ?? null,
                    'status' => $overview !== null ? 'online' : 'offline',
                    'products' => $overview ? ($overview['products'] ?? 'All game + bot plans') : 'All game + bot plans',
                    'checked_at' => now()->format('H:i'),
                ];
            })->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function getFilteredNodesProperty(): array
    {
        $nodes = $this->nodes;
        if ($this->statusFilter !== 'all') {
            $nodes = array_values(array_filter($nodes, fn ($n) => $n['status'] === $this->statusFilter));
        }
        if ($this->query !== '') {
            $q = strtolower(trim($this->query));
            $nodes = array_values(array_filter($nodes, function ($n) use ($q) {
                return str_contains(strtolower($n['name']), $q) || str_contains(strtolower($n['location']), $q) || str_contains(strtolower($n['region']), $q);
            }));
        }

        return $nodes;
    }

    public function render()
    {
        return view('network', [
            'filtered' => $this->getFilteredNodesProperty(),
            'selectedNode' => collect($this->nodes)->firstWhere('uuid', $this->selected),
        ]);
    }
}
