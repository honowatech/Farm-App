<?php

/*
|--------------------------------------------------------------------------
| Fonctionnalités & limites des forfaits
|--------------------------------------------------------------------------
|
| Source de vérité du constructeur d'abonnement par fonctionnalité.
|
| - "features" : les modules métier activables par forfait. Chaque entrée
|   référence un groupe de permissions (config/permissions.php) : activer une
|   fonctionnalité débloque toutes les permissions de ce groupe pour le tenant.
|   Le groupe "Général" (tableau de bord, alertes, profil) est toujours inclus.
|
| - "limits" : les plafonds numériques associés à un forfait (utilisateurs,
|   bandes, produits, clients…). Ils sont exposés via Tenant::limit() et
|   contrôlés à la création/modification des enregistrements concernés.
|
*/

return [

    'features' => [
        'elevage' => [
            'label' => 'Élevage',
            'permission_group' => 'Élevage',
            'icon' => 'columns',
            'description' => 'Bandes, bâtiments, animaux, nutrition, santé, hygiène.',
        ],
        'production' => [
            'label' => 'Production',
            'permission_group' => 'Production',
            'icon' => 'box',
            'description' => 'Ramassages, produits, produits dérivés, stocks produits.',
        ],
        'achats' => [
            'label' => 'Achats',
            'permission_group' => 'Achats',
            'icon' => 'shopping-cart',
            'description' => 'Matières premières, fournisseurs, achats, unités.',
        ],
        'commerce' => [
            'label' => 'Commerce',
            'permission_group' => 'Commerce',
            'icon' => 'shopping-bag',
            'description' => 'Ventes, factures, clients, paiements.',
        ],
        'finances' => [
            'label' => 'Finances',
            'permission_group' => 'Finances',
            'icon' => 'dollar-sign',
            'description' => 'Caisse, dépenses, salaires, paie, rentabilité.',
        ],
        'parametres' => [
            'label' => 'Paramètres',
            'permission_group' => 'Paramètres',
            'icon' => 'settings',
            'description' => 'Utilisateurs, agents, réglages, plans alimentaires.',
        ],
    ],

    'limits' => [
        'utilisateurs' => [
            'label' => 'Utilisateurs',
            'description' => 'Membres de l\'entreprise (hors clients).',
            'default' => 10,
            'min' => 1,
            'max' => 10000,
        ],
        'bandes' => [
            'label' => 'Bandes actives',
            'description' => 'Bandes simultanément actives.',
            'default' => 5,
            'min' => 1,
            'max' => 1000,
        ],
        'produits' => [
            'label' => 'Produits',
            'description' => 'Produits et produits dérivés.',
            'default' => 100,
            'min' => 0,
            'max' => 100000,
        ],
        'clients' => [
            'label' => 'Clients',
            'description' => 'Clients enregistrés.',
            'default' => 200,
            'min' => 0,
            'max' => 100000,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Quotas par profil (liés à une fonctionnalité)
    |--------------------------------------------------------------------------
    | Chaque profil (rôle métier) est rattaché à une fonctionnalité principale.
    | Désactiver cette fonctionnalité masque le quota du profil dans le
    | constructeur de forfait. Le quota limite le nombre d'utilisateurs de ce
    | profil au sein d'une entreprise. "admin" et "superadmin" ne sont pas
    | plafonnés (admin = toutes les permissions, superadmin = propriétaire).
    */
    'profiles' => [
        'gestionnaire' => ['label' => 'Gestionnaire', 'feature' => 'elevage', 'default' => 1],
        'agent' => ['label' => 'Agent', 'feature' => 'elevage', 'default' => 5],
        'veterinaire' => ['label' => 'Vétérinaire', 'feature' => 'elevage', 'default' => 2],
        'magasinier' => ['label' => 'Magasinier', 'feature' => 'production', 'default' => 2],
        'commercial' => ['label' => 'Commercial', 'feature' => 'commerce', 'default' => 2],
        'caissier' => ['label' => 'Caissier', 'feature' => 'commerce', 'default' => 2],
        'client' => ['label' => 'Client', 'feature' => 'commerce', 'default' => 50],
        'comptable' => ['label' => 'Comptable', 'feature' => 'finances', 'default' => 1],
        'rh' => ['label' => 'RH', 'feature' => 'finances', 'default' => 1],
    ],
];
