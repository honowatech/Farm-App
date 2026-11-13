<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    /**
     * Cache des permissions du rôle pour éviter une requête par entrée de menu.
     *
     * @var array<int, string>|null
     */
    protected ?array $permissionCache = null;

    public function isAdmin(): bool
    {
        return $this->type_user?->libelle === 'admin';
    }

    public function roleName(): ?string
    {
        return $this->type_user?->libelle;
    }

    /**
     * @return array<int, string>
     */
    public function permissionKeys(): array
    {
        if ($this->permissionCache === null) {
            $this->permissionCache = $this->type_user
                ? $this->type_user->permissions()->pluck('name')->all()
                : [];
        }

        return $this->permissionCache;
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
