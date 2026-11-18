<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class TenantDomain extends Model
{
    protected $fillable = [
        'tenant_id',
        'domain',
        'is_primary',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        // Invalide le cache de résolution du domaine (ResolveTenantByDomain)
        // à chaque création, modification ou suppression d'un domaine.
        static::saved(function (TenantDomain $domain) {
            Cache::forget('tenant_domain:'.$domain->domain);

            $original = $domain->getOriginal('domain');
            if ($original && $original !== $domain->domain) {
                Cache::forget('tenant_domain:'.$original);
            }
        });

        static::deleted(function (TenantDomain $domain) {
            Cache::forget('tenant_domain:'.$domain->domain);
        });
    }

    public function setDomainAttribute(string $domain): void
    {
        $this->attributes['domain'] = strtolower(rtrim(trim($domain), '.'));
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
