<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'billing_cycle',
        'status',
        'starts_at',
        'ends_at',
        'cancelled_at',
        'amount_paid',
        'payment_method',
        'transaction_id',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'amount_paid' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' &&
               ($this->ends_at === null || $this->ends_at->isFuture());
    }

    public function isExpired(): bool
    {
        return $this->ends_at && $this->ends_at->isPast();
    }
}
