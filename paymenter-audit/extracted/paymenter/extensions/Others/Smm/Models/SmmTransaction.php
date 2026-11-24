<?php

namespace Paymenter\Extensions\Others\Smm\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class SmmTransaction extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:8',
    ];

    public const TYPE_DEBIT = 'debit';
    public const TYPE_CREDIT = 'credit';
    public const TYPE_REFUND = 'refund';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function smmOrder(): BelongsTo
    {
        return $this->belongsTo(SmmOrder::class);
    }
}
