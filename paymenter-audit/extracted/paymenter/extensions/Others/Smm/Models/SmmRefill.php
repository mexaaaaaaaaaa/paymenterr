<?php

namespace Paymenter\Extensions\Others\Smm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class SmmRefill extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected $casts = [
        'quantity' => 'decimal:0',
    ];

    public function smmOrder(): BelongsTo
    {
        return $this->belongsTo(SmmOrder::class);
    }
}
