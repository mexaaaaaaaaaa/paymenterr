<?php

namespace Paymenter\Extensions\Others\Smm\Models;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class SmmOrder extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected $casts = [
        'quantity' => 'decimal:0',
        'quantity_started' => 'decimal:0',
        'quantity_remaining' => 'decimal:0',
        'amount' => 'decimal:8',
        'request_params' => 'array',
        'provider_response' => 'array',
        'submitted_to_provider_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_PAID = 'paid';
    public const STATUS_QUEUED = 'queued';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_PARTIAL = 'partial';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUND_PENDING = 'refund_pending';
    public const STATUS_REFUNDED = 'refunded';

    public static $statuses = [
        self::STATUS_PENDING_PAYMENT => 'Pending Payment',
        self::STATUS_PAID => 'Paid',
        self::STATUS_QUEUED => 'Queued',
        self::STATUS_PROCESSING => 'Processing',
        self::STATUS_IN_PROGRESS => 'In Progress',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_PARTIAL => 'Partially Completed',
        self::STATUS_CANCELED => 'Canceled',
        self::STATUS_FAILED => 'Failed',
        self::STATUS_REFUND_PENDING => 'Refund Pending',
        self::STATUS_REFUNDED => 'Refunded',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(SmmProvider::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SmmOrderItem::class);
    }

    public function refills(): HasMany
    {
        return $this->hasMany(SmmRefill::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(SmmOrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function canBeSubmittedToProvider(): bool
    {
        return in_array($this->status, [self::STATUS_PAID, self::STATUS_FAILED]);
    }

    public function isTerminal(): bool
    {
        return in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_CANCELED, self::STATUS_FAILED, self::STATUS_REFUNDED]);
    }
}
