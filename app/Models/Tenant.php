<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasUlids;

    public const TRIAL_RECOVERY_DAYS = 30;

    protected $fillable = [
        'name',
        'slug',
        'status',
        'is_demo',
        'demo_expires_at',
        'demo_claim_token_hash',
    ];

    private bool $activeSubscriptionLoaded = false;

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
                ->where(function ($query) {
                    $query->where(function ($active) {
                        $active->where('status', 'active')
                            ->where(function ($period) {
                                $period->whereNull('current_period_ends_at')
                                    ->orWhere('current_period_ends_at', '>', now());
                            });
                    })->orWhere(function ($trial) {
                        $trial->where('status', 'trialing')
                            ->where('trial_ends_at', '>', now());
                    });
                })
                ->orderByDesc('created_at')
                ->first();
        }

        return $this->activeSubscription;
    }

    public function expiredTrialSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->with('plan')
            ->where('status', 'trialing')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<=', now())
            ->orderByDesc('trial_ends_at')
            ->first();
    }

    public function recoverableTrialSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->with('plan')
            ->where('status', 'trialing')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<=', now())
            ->where('trial_ends_at', '>=', now()->subDays(self::TRIAL_RECOVERY_DAYS))
            ->orderByDesc('trial_ends_at')
            ->first();
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
