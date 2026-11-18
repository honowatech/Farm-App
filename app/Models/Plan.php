<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'price',
        'currency',
        'billing_interval',
        'trial_days',
        'features',
        'limits',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'trial_days' => 'integer',
            'features' => 'array',
            'limits' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Indique si le forfait active la fonctionnalité donnée.
     * Accepte les deux formes : ['nutrition' => true] et ['nutrition'].
     */
    public function hasFeature(string $feature): bool
    {
        $features = $this->features ?? [];

        if (array_key_exists($feature, $features)) {
            return (bool) $features[$feature];
        }

        return in_array($feature, $features, true);
    }

    /**
     * Retourne la limite configurée pour une clé, ou $default.
     */
    public function limit(string $key, mixed $default = null): mixed
    {
        $limits = $this->limits ?? [];

        return $limits[$key] ?? $default;
    }
}
