<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'tenant_id',
        'plan_id',
        'status',
        'starts_at',
        'trial_ends_at',
        'current_period_starts_at',
        'current_period_ends_at',
        'canceled_at',
        'provider',
        'provider_reference',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            'current_period_starts_at' => 'datetime',
            'current_period_ends_at' => 'datetime',
            'canceled_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isTrialing(): bool
    {
        return $this->status === 'trialing';
    }

    public function isCanceled(): bool
    {
        return $this->status === 'canceled';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isPastDue(): bool
    {
        return $this->status === 'past_due';
    }

    public function onTrial(): bool
    {
        return $this->trial_ends_at !== null && $this->trial_ends_at->isFuture();
    }
}
