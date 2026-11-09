<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Routes autorisées pour le rôle "agent"
    |--------------------------------------------------------------------------
    |
    | Ces noms de routes sont accessibles aux utilisateurs ayant le rôle
    | "agent". Les administrateurs accèdent à tout ; les autres rôles
    | (client, etc.) sont refusés par défaut.
    |
    */
    'allowed_routes' => [
        'nutrition.index',
        'nutrition.calendar',
        'nutrition.calendar.data',
        'nutrition.check',
        'nutrition.search',
        'load_form',
        'bande.index',
        'bande.create',
        'bande.store',
        'bande.show',
        'bande.edit',
        'bande.update',
        'bande.destroy',
        'nutrition.create',
        'nutrition.edit',
        'nutrition.update',
        'gestion_bande',
        'get-form-bande',
        'bande.search',
        'nettoyage.index',
        'seanceVaccination.index',
    ],
];
