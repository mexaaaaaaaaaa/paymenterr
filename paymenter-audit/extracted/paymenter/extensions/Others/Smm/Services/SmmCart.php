<?php

namespace Paymenter\Extensions\Others\Smm\Services;

use Illuminate\Support\Facades\Session;
use Paymenter\Extensions\Others\Smm\Models\SmmService;

class SmmCart
{
    const SESSION_KEY = 'smm_cart';

    public static function all(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public static function add(SmmService $service, int $quantity, string $targetUrl, array $params = []): void
    {
        $items = self::all();
        $unitPrice = PricingEngine::calculate($service);
        $total = Math::mul($unitPrice, (string) $quantity);

        $item = [
            'service_id' => $service->id,
            'provider_service_id' => $service->provider_service_id,
            'name' => $service->name,
            'channel' => $service->channel,
            'target_url' => $targetUrl,
            'quantity' => (int) $quantity,
            'unit_price' => $unitPrice,
            'total' => $total,
            'currency' => $unitPrice ? PricingEngine::defaultCurrency() : 'INR',
            'params' => $params,
            'added_at' => now()->toISOString(),
        ];

        // Duplicate protection: replace identical item
        $key = $service->id.'|'.md5($targetUrl);
        $items[$key] = $item;

        Session::put(self::SESSION_KEY, $items);
    }

    public static function remove(string $key): void
    {
        $items = self::all();
        unset($items[$key]);
        Session::put(self::SESSION_KEY, $items);
    }

    public static function update(string $key, array $updates): void
    {
        $items = self::all();
        if (!isset($items[$key])) {
            return;
        }
        $items[$key] = array_merge($items[$key], $updates);
        // Recalculate total if quantity changed
        if (isset($updates['quantity'])) {
            $service = SmmService::find($items[$key]['service_id']);
            if ($service) {
                $unitPrice = PricingEngine::calculate($service);
                $items[$key]['unit_price'] = $unitPrice;
                $items[$key]['total'] = Math::mul($unitPrice, (string) $updates['quantity']);
                $items[$key]['quantity'] = (int) $updates['quantity'];
            }
        }
        Session::put(self::SESSION_KEY, $items);
    }

    public static function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public static function total(): array
    {
        $items = self::all();
        $total = '0';
        $count = 0;
        foreach ($items as $item) {
            $total = Math::add($total, $item['total'] ?? '0');
            $count += (int) ($item['quantity'] ?? 0);
        }
        return ['total' => Math::round($total, 2), 'count' => $count, 'items' => $items];
    }
}
