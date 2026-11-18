<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasUlids;

    protected $fillable = [
        'name',
        'slug',
        'status',
        'is_demo',
        'demo_expires_at',
    ];

    /** @var bool */
    private bool $activeSubscriptionLoaded = false;

    /** @var \App\Models\Subscription|null */
    private ?Subscription $activeSubscription = null;

    protected function casts(): array
    {
        return [
            'is_demo' => 'boolean',
            'demo_expires_at' => 'datetime',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tenant_user')
            ->withPivot(['role_key', 'status', 'invited_at', 'accepted_at'])
            ->withTimestamps();
    }

    public function domains(): HasMany
    {
        return $this->hasMany(TenantDomain::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Abonnement courant (actif ou en période d'essai), avec son forfait.
     */
    public function activeSubscription(): ?Subscription
    {
        if (! $this->activeSubscriptionLoaded) {
            $this->activeSubscriptionLoaded = true;

            $this->activeSubscription = $this->subscriptions()
                ->with('plan')
                ->whereIn('status', ['active', 'trialing'])
                ->where(function ($query) {
                    $query->whereNull('current_period_ends_at')
                        ->orWhere('current_period_ends_at', '>', now());
                })
                ->orderByDesc('created_at')
                ->first();
        }

        return $this->activeSubscription;
    }

    /**
     * Indique si le forfait courant active la fonctionnalité donnée.
     */
    public function hasFeature(string $feature): bool
    {
        return $this->activeSubscription()?->plan?->hasFeature($feature) ?? false;
    }

    /**
     * Retourne la limite du forfait courant pour une clé, ou $default.
     */
    public function limit(string $key, mixed $default = null): mixed
    {
        return $this->activeSubscription()?->plan?->limit($key, $default) ?? $default;
    }
}
