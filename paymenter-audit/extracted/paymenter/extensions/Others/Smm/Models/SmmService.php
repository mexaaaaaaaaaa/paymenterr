<?php

namespace Paymenter\Extensions\Others\Smm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class SmmService extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected $casts = [
        'min_quantity' => 'decimal:0',
        'max_quantity' => 'decimal:0',
        'provider_cost' => 'decimal:8',
        'markup_percent' => 'decimal:4',
        'fixed_markup' => 'decimal:8',
        'custom_price' => 'decimal:8',
        'min_selling_price' => 'decimal:8',
        'is_enabled' => 'boolean',
        'is_discontinued' => 'boolean',
        'refill_eligible' => 'boolean',
        'cancel_eligible' => 'boolean',
        'drip_feed' => 'boolean',
        'subscription' => 'boolean',
        'parameters' => 'array',
        'raw_provider_data' => 'array',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(SmmProvider::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SmmCategory::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(SmmOrderItem::class);
    }

    public function calculateSellingPrice(int $quantity = 1): string
    {
        $providerCost = $this->provider_cost ?? 0;
        $currency = $this->provider_currency ?? 'INR';

        // Per-unit selling price
        $unitPrice = \Paymenter\Extensions\Others\Smm\Services\PricingEngine::calculate($this, $currency);

        return \Paymenter\Extensions\Others\Smm\Services\Math::mul($unitPrice, (string) $quantity);
    }

    public function getSellingPriceAttribute(): string
    {
        return \Paymenter\Extensions\Others\Smm\Services\PricingEngine::calculate($this);
    }
}
