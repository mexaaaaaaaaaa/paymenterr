<?php

namespace App\Services;

use App\Models\StatusCheck;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/**
 * ZyperNodes status monitoring service.
 * Runs REAL health checks against configured endpoints and records history.
 * All credentials stay server-side; the public page only receives results.
 */
class StatusMonitor
{
    public const SERVICES = [
        'website' => [
            'name' => 'ZyperNodes Website',
            'description' => 'zypernodes.cloud public site',
            'type' => 'http',
            'url' => 'https://zypernodes.cloud',
        ],
        'billing' => [
            'name' => 'Billing System',
            'description' => 'billing.zypernodes.cloud — orders, invoices, checkout',
            'type' => 'billing',
            'url' => 'https://billing.zypernodes.cloud',
        ],
        'panel' => [
            'name' => 'Game Panel',
            'description' => 'panel.zypernodes.cloud — Calagopus',
            'type' => 'http_json',
            'url' => 'https://panel.zypernodes.cloud/api/settings',
        ],
        'node' => [
            'name' => 'Game Node (Intel1)',
            'description' => 'Integrated Wings node — server hosting',
            'type' => 'panel_node',
            'url' => 'https://panel.zypernodes.cloud/api/admin/nodes',
        ],
        'sftp' => [
            'name' => 'SFTP Access',
            'description' => 'intel1.zypernodes.cloud:2022 — file access',
            'type' => 'tcp',
            'host' => 'intel1.zypernodes.cloud',
            'port' => 2022,
        ],
    ];

    /**
     * Run every check and return results (used by command + cached web view).
     */
    public function runAll(): array
    {
        $results = [];
        foreach (self::SERVICES as $key => $svc) {
            $results[$key] = $this->check($key);
        }

        return $results;
    }

    /**
     * Run a single service check.
     */
    public function check(string $key): array
    {
        $svc = self::SERVICES[$key] ?? null;
        if (!$svc) {
            return ['status' => 'down', 'latency_ms' => null, 'details' => 'unknown service'];
        }

        $start = microtime(true);
        try {
            $details = match ($svc['type']) {
                'http' => $this->checkHttp($svc['url']),
                'http_json' => $this->checkHttpJson($svc['url']),
                'billing' => $this->checkBilling(),
                'panel_node' => $this->checkPanelNode($svc['url']),
                'tcp' => $this->checkTcp($svc['host'], $svc['port']),
                default => throw new \RuntimeException('unknown check type'),
            };
            $latency = (int) round((microtime(true) - $start) * 1000);
            $status = 'operational';
            // A node partially offline means degraded, not down
            if (str_contains($details, 'not reporting')) {
                $status = 'degraded';
            }
            // Slow (>5s) means degraded, not down
            if ($latency > 5000) {
                $status = 'degraded';
                $details .= ' (slow response)';
            }
        } catch (\Throwable $e) {
            return [
                'status' => 'down',
                'latency_ms' => null,
                'details' => 'health check failed',
                'checked_at' => now()->toIso8601String(),
            ];
        }

        return [
            'status' => $status,
            'latency_ms' => $latency,
            'details' => $details,
            'checked_at' => now()->toIso8601String(),
        ];
    }

    private function checkHttp(string $url): string
    {
        [$code, $err] = $this->request($url);
        if ($code === null || $code >= 500 || $code === 0) {
            throw new \RuntimeException("HTTP $code");
        }

        return "HTTP $code";
    }

    private function checkHttpJson(string $url): string
    {
        [$code, $body, $err] = $this->request($url, true);
        if ($code === null || $code >= 500) {
            throw new \RuntimeException("HTTP $code");
        }
        // Panel API answers with JSON (200 or 401) — both prove backend is up
        if (!str_contains((string) $body, '"app"') && !str_contains((string) $body, '"errors"')) {
            throw new \RuntimeException('unexpected response');
        }

        return "API responding (HTTP $code)";
    }

    private function checkBilling(): string
    {
        DB::select('SELECT 1');
        try {
            Redis::ping();
        } catch (\Throwable $e) {
            throw new \RuntimeException('cache unreachable');
        }
        [$code] = $this->request('https://billing.zypernodes.cloud');
        if ($code === null || $code >= 500) {
            throw new \RuntimeException('HTTP ' . $code);
        }

        return 'DB + cache + web OK';
    }

    private function checkPanelNode(string $url): string
    {
        $key = config('services.calagopus.api_key');
        if (!$key) {
            // Without a key fall back to plain HTTP check of the panel
            return $this->checkHttp('https://panel.zypernodes.cloud') . ' (node API not configured)';
        }
        [$code, $body] = $this->request($url, true, ['Authorization: Bearer ' . $key]);
        if ($code !== 200 || !str_contains((string) $body, '"nodes"')) {
            throw new \RuntimeException("node API HTTP $code");
        }
        $data = json_decode((string) $body, true);
        $nodes = $data['nodes']['data'] ?? [];
        $notReporting = [];
        $reporting = 0;
        foreach ($nodes as $n) {
            [$oc] = $this->request(
                rtrim($this->panelBase(), '/') . '/api/admin/nodes/' . $n['uuid'] . '/system/overview',
                true,
                ['Authorization: Bearer ' . $key]
            );
            if ($oc === 200) {
                $reporting++;
            } else {
                $notReporting[] = $n['name'];
            }
        }
        if ($notReporting) {
            $msg = count($notReporting) . ' of ' . count($nodes) . ' node(s) not reporting: ' . implode(', ', array_slice($notReporting, 0, 3));
            if ($reporting === 0) {
                throw new \RuntimeException($msg);
            }

            return $msg; // partial — treated as degraded by caller when slow? no: force degraded below
        }

        return 'node API OK — ' . $reporting . ' of ' . count($nodes) . ' node(s) reporting';
    }

    private function panelBase(): string
    {
        return 'https://panel.zypernodes.cloud';
    }

    private function checkTcp(string $host, int $port): string
    {
        $sock = @fsockopen($host, $port, $errno, $errstr, 4);
        if ($sock === false) {
            throw new \RuntimeException("tcp $host:$port failed");
        }
        // Read the SSH banner to prove it's really the SFTP server
        stream_set_timeout($sock, 3);
        $banner = (string) fread($sock, 64);
        fclose($sock);
        if (!str_contains($banner, 'SSH')) {
            throw new \RuntimeException('no SSH banner');
        }

        return 'SFTP accepting connections';
    }

    /**
     * Minimal HTTP client (curl). Returns [code, body, error].
     */
    private function request(string $url, bool $withBody = false, array $extraHeaders = []): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_TIMEOUT => 8,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT => 'ZyperNodes-StatusBot/1.0',
        ]);
        if ($extraHeaders) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $extraHeaders);
        }
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $err = curl_error($ch);
        curl_close($ch);
        if ($body === false) {
            return [null, null, $err];
        }

        return [$code, $withBody ? $body : null, null];
    }

    /**
     * Run all checks and persist to history. Called by the scheduler.
     */
    public function record(): int
    {
        $count = 0;
        foreach ($this->runAll() as $key => $result) {
            StatusCheck::create([
                'service' => $key,
                'status' => $result['status'],
                'latency_ms' => $result['latency_ms'],
                'details' => $result['details'],
                'checked_at' => now(),
            ]);
            $count++;
        }

        return $count;
    }

    /**
     * Live results for the public page (cached 60 seconds).
     */
    public function live(): array
    {
        return Cache::remember('status_live_results', 60, function () {
            return $this->runAll();
        });
    }

    /**
     * Uptime percentages + avg latency from REAL history.
     */
    public function history(): array
    {
        $since = now()->subDays(30);
        $rows = StatusCheck::where('checked_at', '>=', $since)
            ->selectRaw('service,
                COUNT(*) AS total,
                SUM(status = "operational") AS ok,
                AVG(latency_ms) AS avg_latency,
                MAX(checked_at) AS last_check')
            ->groupBy('service')
            ->get()
            ->keyBy('service');

        $out = [];
        foreach (array_keys(self::SERVICES) as $key) {
            $row = $rows->get($key);
            $out[$key] = [
                'has_data' => $row !== null,
                'uptime' => $row && $row->total > 0 ? round($row->ok / $row->total * 100, 2) : null,
                'avg_latency' => $row ? (int) round((float) $row->avg_latency) : null,
                'checks' => $row ? (int) $row->total : 0,
                'first_check' => StatusCheck::where('service', $key)->orderBy('checked_at')->value('checked_at'),
            ];
        }

        return $out;
    }

    /**
     * Derive overall system status from live checks + active incidents.
     */
    public function overallStatus(array $live, $incidents): string
    {
        if ($incidents->contains(fn ($i) => $i->state === 'maintenance')) {
            return 'maintenance';
        }
        $down = collect($live)->filter(fn ($r) => $r['status'] === 'down')->count();
        $degraded = collect($live)->filter(fn ($r) => $r['status'] === 'degraded')->count();
        if ($down >= 2) {
            return 'major';
        }
        if ($down === 1 || $degraded > 0) {
            return 'partial';
        }

        return 'operational';
    }
}
