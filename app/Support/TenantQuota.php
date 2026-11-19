<?php

namespace App\Support;

use App\Models\Tenant;
use App\Models\Typeusers;
use App\Models\User;
use Illuminate\Validation\ValidationException;

/**
 * Applique les quotas de profils définis par le forfait actif du tenant.
 *
 * - Un profil (agent, commercial, comptable…) possède un quota dans
 *   `forfait.limits.profiles.<profil>` (voir config/features.php).
 * - Le tenant courant est résolu depuis le TenantContext, ou à défaut depuis
 *   l'appartenance active de l'utilisateur connecté (mode legacy / mono-tenant).
 * - En l'absence de tenant ou de quota, aucune restriction n'est appliquée.
 */
class TenantQuota
{
    public function currentTenant(): ?Tenant
    {
        $context = app(TenantContext::class);

        if ($context->hasTenant()) {
            return $context->tenant();
        }

        $user = auth()->user();

        if (! $user) {
            return null;
        }

        return $user->tenants()
            ->wherePivot('status', 'active')
            ->first();
    }

    /**
     * Lève une ValidationException si le quota du profil est atteint.
     */
    public function assertProfileWithinLimit(string $profile): void
    {
        $tenant = $this->currentTenant();

        if (! $tenant) {
            return;
        }

        $quota = $tenant->limit('profiles.'.$profile);

        if ($quota === null) {
            return;
        }

        $typeUser = Typeusers::where('libelle', $profile)->first();

        if (! $typeUser) {
            return;
        }

        $count = User::where('id_type_user', $typeUser->id)
            ->whereHas('tenants', fn ($query) => $query->where('tenants.id', $tenant->id))
            ->count();

        if ($count >= (int) $quota) {
            throw ValidationException::withMessages([
                'type_user' => "Le quota de {$quota} profil(s) « {$profile} » est atteint. Changez de forfait pour en créer davantage.",
            ]);
        }
    }

    /**
     * Rattache un utilisateur fraîchement créé au tenant courant afin que les
     * futurs contrôles de quota reflètent la réalité.
     */
    public function attachUser(User $user, string $role): void
    {
        $tenant = $this->currentTenant();

        if (! $tenant) {
            return;
        }

        $tenant->users()->syncWithoutDetaching([
            $user->id => [
                'role_key' => $role,
                'status' => 'active',
                'accepted_at' => now(),
            ],
        ]);
    }
}
