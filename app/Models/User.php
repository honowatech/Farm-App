<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_type_user',
        'statut',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function type_user()
    {
        return $this->belongsTo('App\Models\Typeusers', 'id_type_user');
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'tenant_user')
            ->withPivot(['role_key', 'status', 'invited_at', 'accepted_at'])
            ->withTimestamps();
    }

    /**
     * Cache des permissions calculé par rôle/contexte pour éviter une requête par entrée de menu.
     *
     * @var array<string, array<int, string>>
     */
    protected array $permissionCache = [];

    /**
     * Cache du rôle de l'utilisateur dans chaque tenant visité pendant la requête.
     *
     * @var array<string, string|null>
     */
    protected array $tenantRoleCache = [];

    public function isAdmin(): bool
    {
        return $this->roleName() === 'admin';
    }

    /**
     * Super administrateur de la plateforme (propriétaire de l'application).
     * Rôle global, indépendant de tout tenant.
     */
    public function isSuperAdmin(): bool
    {
        return $this->type_user?->libelle === 'superadmin';
    }

    public function roleName(): ?string
    {
        $context = app(\App\Support\TenantContext::class);

        if ($context->hasTenant()) {
            $tenantId = $context->id();

            if (! array_key_exists($tenantId, $this->tenantRoleCache)) {
                $this->tenantRoleCache[$tenantId] = $context->membershipRole((int) $this->getKey())
                    ?? $this->tenants()
                        ->where('tenants.id', $tenantId)
                        ->wherePivot('status', 'active')
                        ->first()
                        ?->pivot
                        ?->role_key;
            }

            return $this->tenantRoleCache[$tenantId];
        }

        return $this->type_user?->libelle;
    }

    /**
     * @return array<int, string>
     */
    public function permissionKeys(): array
    {
        $role = $this->roleName();
        $context = app(\App\Support\TenantContext::class);
        $cacheKey = $context->hasTenant()
            ? $context->id().':'.($role ?? '__no_active_membership__')
            : 'legacy:'.($role ?? '__no_role__');

        if (! array_key_exists($cacheKey, $this->permissionCache)) {
            if ($context->hasTenant()) {
                $permissions = $role === 'admin'
                    ? array_keys(config('permissions.permissions', []))
                    : ($role === null ? [] : config('permissions.roles.'.$role, []));

                $this->permissionCache[$cacheKey] = array_values(array_filter(
                    $permissions,
                    fn ($permission) => is_string($permission) && $permission !== '*'
                ));
            } else {
                $this->permissionCache[$cacheKey] = $this->type_user
                    ? $this->type_user->permissions()->pluck('name')->all()
                    : [];
            }
        }

        return $this->permissionCache[$cacheKey];
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return in_array($permission, $this->permissionKeys(), true);
    }

    /**
     * @param  array<int, string>  $permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function canAccessRoute(?string $routeName): bool
    {
        $permission = \App\Support\RoutePermission::resolve($routeName);

        if ($permission === '__always_allowed__') {
            return true;
        }

        if (blank($permission)) {
            return $this->isAdmin();
        }

        return $this->hasPermission($permission);
    }
}
