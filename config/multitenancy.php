<?php

/*
|--------------------------------------------------------------------------
| Multi-tenant
|--------------------------------------------------------------------------
|
| Le cloisonnement des données métier est assuré par le scope global
| TenantScope et le trait BelongsToTenant (colonne `tenant_id`).
|
| `enforce` contrôle le comportement en l'absence de contexte tenant :
|   - false (défaut) : mode « legacy » — aucune requête n'est filtrée tant
|     que les middlewares tenant ne sont pas appliqués aux routes métier.
|   - true : toute lecture/écriture sans contexte tenant lève une exception.
|     À activer une fois le cloisonnement complet et les middlewares appliqués.
|
*/

return [
    'enforce' => env('MULTITENANCY_ENFORCE', false),
];
