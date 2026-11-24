<?php

namespace Paymenter\Extensions\Others\Smm\Services;

use Paymenter\Extensions\Others\Smm\Models\SmmCategory;
use Paymenter\Extensions\Others\Smm\Models\SmmProvider;
use Paymenter\Extensions\Others\Smm\Models\SmmService;

class ServiceImporter
{
    protected EasySmmpanelApi $api;
    protected SmmProvider $provider;

    public function __construct(EasySmmpanelApi $api)
    {
        $this->api = $api;
        $this->provider = $api->getProvider() ?? SmmProvider::first();
    }

    public function import(): array
    {
        $result = $this->api->services();

        if (!$result['success']) {
            return ['success' => false, 'message' => $result['error'] ?? 'Failed to fetch services', 'imported' => 0, 'updated' => 0];
        }

        $services = $result['data'] ?? [];
        $imported = 0;
        $updated = 0;

        foreach ($services as $raw) {
            $resolved = $this->resolveServiceFields($raw);
            if (!$resolved) {
                continue;
            }

            $service = SmmService::updateOrCreate(
                ['provider_service_id' => $resolved['provider_service_id']],
                [
                    'provider_id' => $this->provider->id,
                    'category_id' => $this->resolveCategory($resolved['platform'])?->id,
                    'name' => $resolved['name'],
                    'slug' => $resolved['slug'],
                    'description' => $resolved['description'],
                    'type' => $resolved['type'],
                    'channel' => $resolved['platform'],
                    'rate' => $resolved['rate'],
                    'min_quantity' => $resolved['min_quantity'],
                    'max_quantity' => $resolved['max_quantity'],
                    'provider_cost' => $resolved['provider_cost'],
                    'provider_currency' => $resolved['provider_currency'],
                    'refill_eligible' => $resolved['refill_eligible'] ?? false,
                    'cancel_eligible' => $resolved['cancel_eligible'] ?? false,
                    'drip_feed' => $resolved['drip_feed'] ?? false,
                    'parameters' => $resolved['parameters'],
                    'raw_provider_data' => $resolved['raw'],
                    'synced_at' => now(),
                    'is_discontinued' => false,
                ]
            );

            if ($service->wasRecentlyCreated) {
                $imported++;
            } else {
                $updated++;
            }
        }

        $this->api->log('sync_services', true, "Imported {$imported} services, updated {$updated}.");

        return [
            'success' => true,
            'message' => "Imported {$imported} new services, updated {$updated}.",
            'imported' => $imported,
            'updated' => $updated,
        ];
    }

    public function markDiscontinued(array $providerServiceIds): void
    {
        SmmService::where('provider_id', $this->provider->id)
            ->whereNotIn('provider_service_id', $providerServiceIds)
            ->where('is_enabled', true)
            ->update(['is_discontinued' => true]);
    }

    protected function resolveServiceFields(array $raw): ?array
    {
        $id = $raw['service'] ?? $raw['id'] ?? $raw['service_id'] ?? null;
        if (!$id) {
            return null;
        }

        $name = $raw['name'] ?? $raw['Service'] ?? 'Unknown Service';
        $type = $raw['type'] ?? $raw['Type'] ?? null;
        $rate = $raw['rate'] ?? $raw['Rate'] ?? null;
        $min = $raw['min'] ?? $raw['minimum'] ?? $raw['Min'] ?? null;
        $max = $raw['max'] ?? $raw['maximum'] ?? $raw['Max'] ?? null;
        $dripfeed = !empty($raw['dripfeed'] ?? $raw['drip_feed'] ?? false);
        $refill = !empty($raw['refill'] ?? false);
        $cancel = !empty($raw['cancel'] ?? false);

        $platform = $this->detectPlatform($name);
        $slug = $this->makeSlug($name.'-'.$id);

        $providerCost = $rate !== null ? (string) $rate : '0';

        return [
            'provider_service_id' => (string) $id,
            'name' => $name,
            'slug' => $slug,
            'description' => $raw['description'] ?? $this->defaultDescription($name, $platform),
            'type' => $type,
            'platform' => $platform,
            'rate' => $rate,
            'min_quantity' => $min,
            'max_quantity' => $max,
            'provider_cost' => $providerCost,
            'provider_currency' => 'INR',
            'parameters' => $this->extractParameters($raw),
            'raw' => $raw,
            'refill_eligible' => $refill,
            'cancel_eligible' => $cancel,
            'drip_feed' => $dripfeed,
        ];
    }

    protected function detectPlatform(string $name): string
    {
        $nameLower = strtolower($name);
        $platforms = ['instagram', 'youtube', 'facebook', 'telegram', 'tiktok', 'twitter', 'spotify', 'discord'];
        foreach ($platforms as $p) {
            if (str_contains($nameLower, $p)) {
                return $p;
            }
        }
        return 'other';
    }

    protected function defaultDescription(string $name, string $platform): string
    {
        return ucfirst($platform).' service: '.$name;
    }

    protected function extractParameters(array $raw): array
    {
        $params = [];
        foreach (['min', 'max', 'type', 'category', 'dripfeed', 'refill', 'cancel'] as $k) {
            if (isset($raw[$k])) {
                $params[$k] = $raw[$k];
            }
        }
        return $params;
    }

    protected function resolveCategory(?string $platform): ?SmmCategory
    {
        if (!$platform) {
            return null;
        }

        $slug = strtolower(trim($platform));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');

        return SmmCategory::firstOrCreate(
            ['slug' => $slug],
            ['name' => ucfirst($platform), 'platform' => $slug, 'sort' => 0, 'is_active' => true]
        );
    }

    protected function makeSlug(string $text): string
    {
        $slug = strtolower(trim($text));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug.'-'.substr(md5(uniqid('', true)), 0, 8);
    }
}
