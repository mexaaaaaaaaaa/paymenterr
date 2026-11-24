<?php

namespace Paymenter\Extensions\Others\Smm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class SmmOrderItem extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected $casts = [
        'quantity' => 'decimal:0',
        'provider_cost' => 'decimal:8',
        'amount' => 'decimal:8',
        'params' => 'array',
    ];

    public function smmOrder(): BelongsTo
    {
        return $this->belongsTo(SmmOrder::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(SmmService::class, 'smm_service_id');
    }
}
