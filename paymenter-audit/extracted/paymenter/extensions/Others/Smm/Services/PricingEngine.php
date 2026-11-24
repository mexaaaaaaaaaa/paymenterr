<?php

namespace Paymenter\Extensions\Others\Smm\Services;

use Paymenter\Extensions\Others\Smm\Models\SmmCategory;
use Paymenter\Extensions\Others\Smm\Models\SmmService;
use Paymenter\Extensions\Others\Smm\Models\SmmSetting;

class PricingEngine
{
    public const DEFAULT_CURRENCY = 'INR';

    public static function calculate(SmmService $service, ?string $customerCurrency = null): string
    {
        $customerCurrency = $customerCurrency ?: self::defaultCurrency();

        $providerCost = Math::toDecimal($service->provider_cost ?? '0');
        $providerCurrency = $service->provider_currency ?? self::defaultCurrency();

        // Convert provider cost to customer currency
        $convertedCost = self::convertCurrency($providerCost, $providerCurrency, $customerCurrency);

        // Apply exchange-rate buffer
        $bufferPercent = self::setting('exchange_rate_buffer', '2');
        if (Math::comp($bufferPercent, '0') > 0) {
            $convertedCost = Math::mul($convertedCost, Math::add('1', Math::div($bufferPercent, '100')));
        }

        // Determine markup
        $markupPercent = $service->markup_percent ?? self::setting('global_markup_percent', '50');
        $fixedMarkup = $service->fixed_markup ?? null;
        $customPrice = $service->custom_price ?? null;
        $minSellingPrice = $service->min_selling_price ?? null;

        $unitPrice = null;

        if ($customPrice !== null && Math::comp($customPrice, '0') > 0) {
            $unitPrice = $customPrice;
        } elseif ($fixedMarkup !== null && Math::comp($fixedMarkup, '0') > 0) {
            $unitPrice = Math::add($convertedCost, $fixedMarkup);
        } else {
            // percentage markup
            $unitPrice = Math::mul($convertedCost, Math::add('1', Math::div($markupPercent, '100')));
        }

        // Apply minimum selling price
        if ($minSellingPrice !== null && Math::comp($minSellingPrice, '0') > 0 && Math::comp($unitPrice, $minSellingPrice) < 0) {
            $unitPrice = $minSellingPrice;
        }

        return Math::round($unitPrice, 2);
    }

    public static function calculateTotal(SmmService $service, int $quantity, ?string $customerCurrency = null): array
    {
        $unitPrice = self::calculate($service, $customerCurrency);
        $total = Math::mul($unitPrice, (string) $quantity);

        return [
            'unit_price' => $unitPrice,
            'total' => Math::round($total, 2),
            'currency' => $customerCurrency ?: self::defaultCurrency(),
        ];
    }

    public static function defaultCurrency(): string
    {
        return self::setting('default_currency', self::DEFAULT_CURRENCY);
    }

    public static function setting(string $key, $default = null)
    {
        return SmmSetting::get($key, $default);
    }

    protected static function convertDecimal(string $value): string
    {
        return Math::toDecimal($value);
    }

    protected static function convertCurrency(string $amount, string $from, string $to): string
    {
        if ($from === $to) {
            return $amount;
        }

        $rate = self::exchangeRate($from, $to);
        return Math::mul($amount, $rate);
    }

    protected static function exchangeRate(string $from, string $to): string
    {
        $key = 'exchange_rate_'.$from.'_'.$to;
        $rate = SmmSetting::get($key);
        if ($rate === null) {
            // Default: treat as 1:1 when no rate configured (INR primary)
            return '1';
        }
        return $rate;
    }
}
