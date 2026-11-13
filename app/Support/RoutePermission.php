<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Résout la permission nécessaire pour une route donnée.
 *
 * Les routes métier sont nommées de manière hétérogène (héritage du projet).
 * Ce résolveur centralise la correspondance route → permission afin que le
 * middleware et les menus restent synchronisés.
 */
class RoutePermission
{
    /**
     * Retourne la permission associée à une route, ou null si elle est inconnue.
     */
    public static function resolve(?string $routeName): ?string
    {
        if (blank($routeName)) {
            return null;
        }

        $config = config('permissions');

        // Routes toujours accessibles aux utilisateurs connectés (déconnexion, langue…)
        foreach ($config['always_allowed'] ?? [] as $pattern) {
            if (Str::is($pattern, $routeName)) {
                return '__always_allowed__';
            }
        }

        // Correspondances explicites
        $explicit = $config['route_map'][$routeName] ?? null;
        if ($explicit) {
            return $explicit;
        }

        // Correspondances par motif (ex. paiement.*)
        foreach ($config['route_patterns'] ?? [] as $pattern => $permission) {
            if (Str::is($pattern, $routeName)) {
                return $permission;
            }
        }

        // Repli automatique pour les routes de type resource : vente.index, vente.store…
        $parts = explode('.', $routeName);
        if (count($parts) < 2) {
            return null;
        }

        $prefix = $config['resource_prefixes'][$parts[0]] ?? null;
        if (! $prefix) {
            return null;
        }

        $action = strtolower(implode('.', array_slice($parts, 1)));
        $viewActions = $config['view_actions'] ?? [];

        foreach ($viewActions as $viewAction) {
            if ($action === $viewAction || str_contains($action, 'export')) {
                return $prefix.'.view';
            }
        }

        return $prefix.'.manage';
    }

    public static function isAlwaysAllowed(?string $routeName): bool
    {
        return self::resolve($routeName) === '__always_allowed__';
    }
}
