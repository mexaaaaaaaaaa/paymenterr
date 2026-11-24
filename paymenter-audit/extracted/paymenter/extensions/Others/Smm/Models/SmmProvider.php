<?php

namespace Paymenter\Extensions\Others\Smm\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class SmmProvider extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'balance' => 'decimal:8',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(SmmService::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(SmmOrder::class);
    }
}
