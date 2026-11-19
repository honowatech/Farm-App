<?php

namespace App\Support;

use App\Models\Tenant;

/**
 * Pont entre les permissions (RBAC) et les fonctionnalités des forfaits.
 *
 * Une « fonctionnalité » correspond à un groupe de permissions
 * (config/features.php → permission_group). Le catalogue de permissions
 * (config/permissions.php) associe chaque permission à un groupe. On peut donc
 * remonter d'une route → permission → groupe → fonctionnalité, puis vérifier
 * que le forfait actif du tenant inclut cette fonctionnalité.
 */
class FeatureGate
{
    /**
     * Retourne la clé de fonctionnalité associée à une permission, ou null
     * si la permission n'est pas couverte par le catalogue (ex. « Général »).
     */
    public static function forPermission(?string $permission): ?string
    {
        if (blank($permission)) {
            return null;
        }

        $group = config('permissions.permissions')[$permission]['group'] ?? null;

        if (blank($group)) {
            return null;
        }

        foreach (config('features.features', []) as $key => $meta) {
            if (($meta['permission_group'] ?? null) === $group) {
                return $key;
            }
        }

        return null;
    }

    /**
     * Retourne la clé de fonctionnalité requise par une route, ou null.
     */
    public static function forRoute(?string $routeName): ?string
    {
        return self::forPermission(RoutePermission::resolve($routeName));
    }

    /**
     * Vérifie que le tenant (si résolu) dispose de la fonctionnalité.
     * En l'absence de tenant, on n'applique aucune restriction (mode legacy
     * ou espace central du super admin).
     */
    public static function allows(?Tenant $tenant, ?string $feature): bool
    {
        if ($tenant === null || blank($feature)) {
            return true;
        }

        return $tenant->hasFeature($feature);
    }
}
