<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Server;
use Illuminate\Support\Facades\Http;

class Home extends Component
{
    public function render()
    {
        // Get limited node data for Global Presence preview
        $previewNodes = $this->getPreviewNodes();

        // Get hosting category products
        $gameHostingCategory = \App\Models\Category::where('slug', 'game-hosting')->first();
        $botHostingCategory = \App\Models\Category::where('slug', 'bot-hosting')->first();

        return view('home', [
            // Counts only — avoid hydrating thousands of plan/price models on every request
            'categories' => Category::whereNull('parent_id')
                ->where(function ($query) {
                    $query->whereHas('children')
                        ->orWhereHas('products', function ($query) {
                            $query->where('hidden', false);
                        });
                })
                ->withCount(['products', 'children'])
                ->orderBy('sort')
                ->get(),
            'mcPlanCount' => \App\Models\Category::where('slug', 'game-minecraft')->withCount('products')->value('products_count') ?? 0,
            'previewNodes' => $previewNodes,
            'minecraftProduct' => \App\Models\Product::where('slug', 'mc-dirt')->first(),
            'gameHostingProduct' => $gameHostingCategory ? \App\Models\Product::where('category_id', $gameHostingCategory->id)->where('hidden', false)->first() : null,
            'botHostingProduct' => $botHostingCategory ? \App\Models\Product::where('category_id', $botHostingCategory->id)->where('hidden', false)->first() : null,
            'title' => 'Home',
        ]);
    }

    /**
     * Get preview node data for Global Presence section
     * Similar to Network::loadNodes() but limited for preview
     */
    private function getPreviewNodes(): array
    {
        $server = Server::where('extension', 'Calagopus')->first();
        if (!$server) {
            return [];
        }

        $host = rtrim($server->settings->where('key', 'host')->value('value') ?? '', '/');
        $key = $server->settings->where('key', 'api_key')->value('value');
        if (!$host || !$key) {
            return [];
        }

        try {
            $resp = Http::withToken($key)->timeout(5)->get($host . '/api/admin/nodes?per_page=6');
            if ($resp->failed()) {
                return [];
            }

            return collect($resp->json('nodes.data') ?? [])->map(function ($n) use ($host, $key) {
                $overview = null;
                try {
                    $ov = Http::withToken($key)->timeout(4)->get($host . '/api/admin/nodes/' . $n['uuid'] . '/system/overview');
                    $overview = $ov->successful() ? $ov->json() : null;
                } catch (\Throwable $e) {
                    $overview = null;
                }

                return [
                    'uuid' => $n['uuid'],
                    'name' => $n['name'],
                    'location' => $n['location']['name'] ?? '—',
                    'online' => $overview !== null,
                    'region' => $overview ? ($overview['region'] ?? 'Mumbai, India') : 'Mumbai, India',
                    'products' => $overview ? ($overview['products'] ?? 'All game + bot plans') : 'All game + bot plans',
                    'checked_at' => now()->format('H:i'),
                    // Real node telemetry from the panel API (percentages only)
                    'mem_pct' => $overview ? (int) round((float) ($overview['memory']['percentage'] ?? $overview['memory']['utilization'] ?? 0)) : null,
                    'disk_pct' => $overview ? (int) round((float) ($overview['disk']['percentage'] ?? $overview['disk']['utilization'] ?? 0)) : null,
                ];
            })->take(3)->toArray(); // Limit to 3 nodes for preview
        } catch (\Throwable $e) {
            return [];
        }
    }
}
