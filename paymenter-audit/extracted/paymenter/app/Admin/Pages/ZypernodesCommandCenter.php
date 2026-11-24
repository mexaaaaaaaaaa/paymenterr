<?php

namespace App\Admin\Pages;

use App\Models\StatusIncident;
use App\Models\User;
use App\Models\Service;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Services\StatusMonitor;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ZypernodesCommandCenter extends Page
{
    protected string $view = 'admin.pages.command-center';

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    protected static ?string $title = 'ZyperNodes Command Center';

    protected static string|\BackedEnum|null $navigationIcon = 'ri-global-line';

    protected static ?int $navigationSort = -1;

    public array $kpis = [];
    public array $nodes = [];
    public array $monitoring = [];
    public array $incidents = [];
    public array $recentAudits = [];
    public array $recentOrders = [];

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user && $user->hasPermission('admin.settings.view');
    }

    public function mount(): void
    {
        $this->kpis = [
            'customers' => User::count(),
            'services_active' => Service::where('status', 'active')->count(),
            'services_total' => Service::count(),
            'orders' => Order::count(),
            'products' => Product::count(),
            'invoices_paid' => Invoice::where('status', 'paid')->count(),
            'invoices_pending' => Invoice::where('status', 'pending')->count(),
            'revenue_total' => (float) Service::where('status', '!=', 'cancelled')->sum('price'),
        ];

        $this->loadNodes();

        $rows = DB::table('status_checks')
            ->where('checked_at', '>=', now()->subDay())
            ->selectRaw('service, SUM(status = "operational") AS ok, COUNT(*) AS total, AVG(latency_ms) AS avg_latency')
            ->groupBy('service')
            ->get()
            ->keyBy('service');
        $this->monitoring = [];
        foreach (array_keys(StatusMonitor::SERVICES) as $key) {
            $row = $rows->get($key);
            $this->monitoring[$key] = [
                'name' => StatusMonitor::SERVICES[$key]['name'],
                'uptime_24h' => $row && $row->total > 0 ? round($row->ok / $row->total * 100) : null,
                'avg_latency' => $row ? (int) round((float) $row->avg_latency) : null,
                'checks' => $row ? (int) $row->total : 0,
            ];
        }

        $this->incidents = StatusIncident::orderByDesc('started_at')->limit(6)->get()->map(fn ($i) => [
            'title' => $i->title,
            'state' => $i->state,
            'started' => $i->started_at?->format('d M H:i'),
            'resolved' => $i->resolved_at?->format('d M H:i'),
        ])->toArray();

        $this->recentAudits = DB::table('audits')->orderByDesc('created_at')->limit(8)
            ->get(['event', 'auditable_type', 'created_at', 'user_id'])->map(fn ($a) => [
                'event' => $a->event,
                'type' => \Illuminate\Support\Str::afterLast($a->auditable_type, '\\'),
                'when' => \Illuminate\Support\Carbon::parse($a->created_at)->diffForHumans(),
            ])->toArray();

        $this->recentOrders = Order::with('user')->orderByDesc('id')->limit(5)->get()->map(fn ($o) => [
            'id' => $o->id,
            'user' => trim(($o->user?->first_name ?? '') . ' ' . ($o->user?->last_name ?? '')),
            'services' => $o->services()->count(),
            'when' => $o->created_at->diffForHumans(),
        ])->toArray();
    }

    private function loadNodes(): void
    {
        $server = \App\Models\Server::where('extension', 'Calagopus')->first();
        if (!$server) {
            return;
        }
        $host = rtrim($server->settings->where('key', 'host')->value('value') ?? '', '/');
        $key = $server->settings->where('key', 'api_key')->value('value');
        if (!$host || !$key) {
            return;
        }

        try {
            $resp = Http::withToken($key)->timeout(8)->get($host . '/api/admin/nodes?per_page=25');
            if ($resp->failed()) {
                return;
            }
            $this->nodes = collect($resp->json('nodes.data') ?? [])->map(function ($n) use ($host, $key) {
                $overview = null;
                try {
                    $ov = Http::withToken($key)->timeout(6)
                        ->get($host . '/api/admin/nodes/' . $n['uuid'] . '/system/overview');
                    $overview = $ov->successful() ? $ov->json() : null;
                } catch (\Throwable $e) {
                    $overview = null;
                }

                return [
                    'name' => $n['name'],
                    'uuid' => $n['uuid'],
                    'online' => $overview !== null,
                    'wings' => $overview['version'] ?? null,
                    'location' => $n['location']['name'] ?? '—',
                    'public_url' => $n['public_url'] ?? null,
                ];
            })->toArray();
        } catch (\Throwable $e) {
            // panel unreachable — leave nodes empty, UI shows honest "unavailable"
        }
    }
}
