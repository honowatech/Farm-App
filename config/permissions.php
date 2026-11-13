<?php

/*
|--------------------------------------------------------------------------
| Profils, permissions et correspondance route → permission
|--------------------------------------------------------------------------
|
| Source de vérité pour l'ACL native. Le seeder RolePermissionSeeder lit
| "permissions" et "roles" pour alimenter la base (tables permissions et
| permission_type_user). Le middleware agent_access et les menus utilisent
| "route_map", "route_patterns" et "resource_prefixes" via RoutePermission.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Catalogue des permissions
    |--------------------------------------------------------------------------
    */
    'permissions' => [
        // Général
        'dashboard.view' => ['label' => 'Voir le tableau de bord', 'group' => 'Général'],
        'alerte.view' => ['label' => 'Consulter les alertes', 'group' => 'Général'],
        'audit.view' => ['label' => "Consulter le journal d'audit", 'group' => 'Général'],

        // Élevage
        'bande.view' => ['label' => 'Consulter les bandes', 'group' => 'Élevage'],
        'bande.manage' => ['label' => 'Gérer les bandes', 'group' => 'Élevage'],
        'batiment.view' => ['label' => 'Consulter les bâtiments', 'group' => 'Élevage'],
        'batiment.manage' => ['label' => 'Gérer les bâtiments', 'group' => 'Élevage'],
        'type_animal.view' => ['label' => "Consulter les types d'animaux", 'group' => 'Élevage'],
        'type_animal.manage' => ['label' => "Gérer les types d'animaux", 'group' => 'Élevage'],
        'animal.view' => ['label' => 'Consulter les animaux', 'group' => 'Élevage'],
        'animal.manage' => ['label' => 'Gérer les animaux', 'group' => 'Élevage'],
        'nutrition.view' => ['label' => 'Consulter la nutrition', 'group' => 'Élevage'],
        'nutrition.manage' => ['label' => 'Enregistrer la nutrition', 'group' => 'Élevage'],
        'vaccination.view' => ['label' => 'Consulter les vaccinations', 'group' => 'Élevage'],
        'vaccination.manage' => ['label' => 'Gérer les vaccinations', 'group' => 'Élevage'],
        'nettoyage.view' => ['label' => 'Consulter les nettoyages', 'group' => 'Élevage'],
        'nettoyage.manage' => ['label' => 'Gérer les nettoyages', 'group' => 'Élevage'],
        'mortalite.view' => ['label' => 'Consulter la mortalité', 'group' => 'Élevage'],
        'mortalite.manage' => ['label' => 'Enregistrer la mortalité', 'group' => 'Élevage'],
        'veterinaire.view' => ['label' => 'Consulter les vétérinaires', 'group' => 'Élevage'],
        'veterinaire.manage' => ['label' => 'Gérer les vétérinaires', 'group' => 'Élevage'],
        'programme_vaccination.view' => ['label' => 'Consulter les programmes vaccinaux', 'group' => 'Élevage'],
        'programme_vaccination.manage' => ['label' => 'Gérer les programmes vaccinaux', 'group' => 'Élevage'],

        // Production
        'ramassage.view' => ['label' => 'Consulter les ramassages', 'group' => 'Production'],
        'ramassage.manage' => ['label' => 'Enregistrer les ramassages', 'group' => 'Production'],
        'produit.view' => ['label' => 'Consulter les produits', 'group' => 'Production'],
        'produit.manage' => ['label' => 'Gérer les produits', 'group' => 'Production'],
        'derive_produit.view' => ['label' => 'Consulter les produits dérivés', 'group' => 'Production'],
        'derive_produit.manage' => ['label' => 'Gérer les produits dérivés', 'group' => 'Production'],
        'stock_produit.view' => ['label' => 'Consulter le stock produits', 'group' => 'Production'],
        'stock_produit.manage' => ['label' => 'Gérer le stock produits', 'group' => 'Production'],

        // Achats
        'matiere_premiere.view' => ['label' => 'Consulter les matières premières', 'group' => 'Achats'],
        'matiere_premiere.manage' => ['label' => 'Gérer les matières premières', 'group' => 'Achats'],
        'stock_matiere_premiere.view' => ['label' => 'Consulter le stock matières premières', 'group' => 'Achats'],
        'stock_matiere_premiere.manage' => ['label' => 'Gérer le stock matières premières', 'group' => 'Achats'],
        'achat.view' => ['label' => 'Consulter les achats', 'group' => 'Achats'],
        'achat.manage' => ['label' => 'Gérer les achats', 'group' => 'Achats'],
        'fournisseur.view' => ['label' => 'Consulter les fournisseurs', 'group' => 'Achats'],
        'fournisseur.manage' => ['label' => 'Gérer les fournisseurs', 'group' => 'Achats'],
        'unite.view' => ['label' => 'Consulter les unités', 'group' => 'Achats'],
        'unite.manage' => ['label' => 'Gérer les unités', 'group' => 'Achats'],

        // Commerce
        'vente.view' => ['label' => 'Consulter les ventes', 'group' => 'Commerce'],
        'vente.manage' => ['label' => 'Gérer les ventes', 'group' => 'Commerce'],
        'facture.view' => ['label' => 'Consulter les factures', 'group' => 'Commerce'],
        'facture.manage' => ['label' => 'Gérer les factures', 'group' => 'Commerce'],
        'ligne_facture.view' => ['label' => 'Consulter les lignes de facture', 'group' => 'Commerce'],
        'ligne_facture.manage' => ['label' => 'Gérer les lignes de facture', 'group' => 'Commerce'],
        'client.view' => ['label' => 'Consulter les clients', 'group' => 'Commerce'],
        'client.manage' => ['label' => 'Gérer les clients', 'group' => 'Commerce'],
        'paiement.view' => ['label' => 'Consulter les paiements', 'group' => 'Commerce'],
        'paiement.manage' => ['label' => 'Gérer les paiements', 'group' => 'Commerce'],
        'paiement.settings' => ['label' => 'Configurer les paiements', 'group' => 'Commerce'],

        // Finances
        'depense.view' => ['label' => 'Consulter les dépenses', 'group' => 'Finances'],
        'depense.manage' => ['label' => 'Gérer les dépenses', 'group' => 'Finances'],
        'categorie_depense.view' => ['label' => 'Consulter les catégories de dépenses', 'group' => 'Finances'],
        'categorie_depense.manage' => ['label' => 'Gérer les catégories de dépenses', 'group' => 'Finances'],
        'caisse.view' => ['label' => 'Consulter la caisse', 'group' => 'Finances'],
        'caisse.manage' => ['label' => 'Gérer la caisse', 'group' => 'Finances'],
        'salarie.view' => ['label' => 'Consulter les salariés', 'group' => 'Finances'],
        'salarie.manage' => ['label' => 'Gérer les salariés', 'group' => 'Finances'],
        'paie.view' => ['label' => 'Consulter la paie', 'group' => 'Finances'],
        'paie.manage' => ['label' => 'Gérer la paie', 'group' => 'Finances'],
        'rentabilite.view' => ['label' => 'Consulter la rentabilité', 'group' => 'Finances'],

        // Paramètres
        'user.view' => ['label' => 'Consulter les utilisateurs', 'group' => 'Paramètres'],
        'user.manage' => ['label' => 'Gérer les utilisateurs', 'group' => 'Paramètres'],
        'agent.view' => ['label' => 'Consulter les agents', 'group' => 'Paramètres'],
        'agent.manage' => ['label' => 'Gérer les agents', 'group' => 'Paramètres'],
        'setting.view' => ['label' => 'Consulter les réglages', 'group' => 'Paramètres'],
        'setting.manage' => ['label' => 'Gérer les réglages', 'group' => 'Paramètres'],
        'foods_setting.view' => ['label' => 'Consulter les plans alimentaires', 'group' => 'Paramètres'],
        'foods_setting.manage' => ['label' => 'Gérer les plans alimentaires', 'group' => 'Paramètres'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Permissions par profil
    |--------------------------------------------------------------------------
    | "admin" reçoit toutes les permissions automatiquement.
    */
    'roles' => [
        'admin' => ['*'],

        'gestionnaire' => [
            'dashboard.view', 'alerte.view', 'audit.view',
            'bande.view', 'bande.manage', 'batiment.view', 'batiment.manage',
            'type_animal.view', 'animal.view', 'animal.manage',
            'nutrition.view', 'nutrition.manage', 'vaccination.view', 'vaccination.manage',
            'nettoyage.view', 'nettoyage.manage', 'mortalite.view', 'mortalite.manage',
            'veterinaire.view', 'programme_vaccination.view',
            'ramassage.view', 'ramassage.manage', 'produit.view', 'derive_produit.view', 'stock_produit.view',
            'matiere_premiere.view', 'stock_matiere_premiere.view', 'achat.view', 'fournisseur.view', 'unite.view',
            'vente.view', 'facture.view', 'ligne_facture.view', 'client.view', 'paiement.view',
            'depense.view', 'categorie_depense.view', 'caisse.view', 'salarie.view', 'paie.view', 'rentabilite.view',
            'setting.view', 'foods_setting.view',
        ],

        'agent' => [
            'dashboard.view', 'alerte.view',
            'bande.view', 'batiment.view', 'animal.view',
            'nutrition.view', 'nutrition.manage',
            'vaccination.view', 'vaccination.manage',
            'nettoyage.view', 'nettoyage.manage',
            'mortalite.view', 'mortalite.manage',
            'ramassage.view', 'ramassage.manage',
            'produit.view', 'stock_produit.view', 'stock_matiere_premiere.view',
        ],

        'veterinaire' => [
            'dashboard.view', 'alerte.view',
            'bande.view', 'animal.view', 'nutrition.view',
            'vaccination.view', 'vaccination.manage',
            'mortalite.view', 'mortalite.manage',
            'veterinaire.view', 'veterinaire.manage',
            'programme_vaccination.view', 'programme_vaccination.manage',
        ],

        'magasinier' => [
            'dashboard.view', 'alerte.view',
            'bande.view', 'produit.view', 'produit.manage',
            'derive_produit.view', 'derive_produit.manage',
            'stock_produit.view', 'stock_produit.manage',
            'matiere_premiere.view', 'matiere_premiere.manage',
            'stock_matiere_premiere.view', 'stock_matiere_premiere.manage',
            'achat.view', 'achat.manage', 'fournisseur.view', 'fournisseur.manage',
            'unite.view', 'unite.manage', 'ramassage.view',
        ],

        'commercial' => [
            'dashboard.view', 'alerte.view',
            'bande.view', 'produit.view', 'stock_produit.view', 'ramassage.view',
            'vente.view', 'vente.manage', 'facture.view', 'facture.manage',
            'ligne_facture.view', 'ligne_facture.manage', 'client.view', 'client.manage',
            'paiement.view', 'paiement.manage',
        ],

        'comptable' => [
            'dashboard.view', 'alerte.view', 'audit.view',
            'bande.view', 'rentabilite.view',
            'vente.view', 'facture.view', 'ligne_facture.view', 'client.view',
            'achat.view', 'fournisseur.view', 'produit.view', 'stock_produit.view',
            'matiere_premiere.view', 'stock_matiere_premiere.view',
            'paiement.view', 'paiement.manage',
            'depense.view', 'depense.manage', 'categorie_depense.view', 'categorie_depense.manage',
            'caisse.view', 'caisse.manage', 'salarie.view', 'paie.view', 'paie.manage',
        ],

        'caissier' => [
            'dashboard.view', 'alerte.view',
            'vente.view', 'facture.view', 'client.view',
            'paiement.view', 'paiement.manage',
            'depense.view', 'depense.manage', 'categorie_depense.view',
            'caisse.view', 'caisse.manage', 'rentabilite.view',
        ],

        'rh' => [
            'dashboard.view', 'alerte.view',
            'bande.view', 'salarie.view', 'salarie.manage',
            'paie.view', 'paie.manage', 'agent.view', 'agent.manage',
        ],

        'client' => [
            'dashboard.view',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes accessibles à tout utilisateur connecté
    |--------------------------------------------------------------------------
    */
    'always_allowed' => [
        'logout',
        'lang.switch',
        'password.*',
        'login',
        'sanctum.csrf-cookie',
        'ignition.*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Correspondances explicites route → permission
    |--------------------------------------------------------------------------
    */
    'route_map' => [
        'home' => 'dashboard.view',
        'alerte.index' => 'alerte.view',
        'audit.index' => 'audit.view',
        'rentabilite.index' => 'rentabilite.view',

        // Bandes
        'bande.search' => 'bande.view',
        'gestion_bande' => 'bande.manage',
        'get-form-bande' => 'bande.manage',
        'terminer_bande' => 'bande.manage',

        // Nutrition
        'nutrition.check' => 'nutrition.view',
        'nutrition.search' => 'nutrition.view',
        'nutrition.calendar' => 'nutrition.view',
        'nutrition.calendar.data' => 'nutrition.view',
        'load_form' => 'nutrition.view',

        // Clients / ventes
        'formulaire' => 'client.manage',
        'saving' => 'client.manage',
        'client' => 'client.view',
        'facturation' => 'vente.view',
        'get_animal' => 'vente.view',
        'get_animal_pondeur' => 'vente.view',
        'get_fiente' => 'vente.view',
        'ajax_result_prix' => 'vente.view',
        'vente.search' => 'vente.view',
        'vente.export' => 'vente.view',
        'vente.export.pdf' => 'vente.view',
        'vente.facture.pdf' => 'vente.view',
        'client.export' => 'client.view',
        'client.export.pdf' => 'client.view',

        // Achats
        'ajax_result_fournisseur' => 'achat.view',
        'achat.search' => 'achat.view',
        'achat.export' => 'achat.view',
        'achat.export.pdf' => 'achat.view',
        'fournisseur.export' => 'fournisseur.view',
        'fournisseur.export.pdf' => 'fournisseur.view',
        'produit.export' => 'produit.view',
        'produit.export.pdf' => 'produit.view',

        // Ramassage
        'ramassage.search' => 'ramassage.view',
        'ajax_result_derive_ramassage' => 'ramassage.view',
        'ajax_result_produit' => 'ramassage.view',
        'ajax_number_ramassage' => 'ramassage.view',

        // Paiements
        'paiement.index' => 'paiement.view',
        'paiement.test' => 'paiement.manage',
        'paiement.initiate' => 'paiement.manage',
        'paiement.status' => 'paiement.manage',
        'paiement.settings' => 'paiement.settings',
        'paiement.settings.update' => 'paiement.settings',
        'paiement.settings.test' => 'paiement.settings',

        // Modules
        'depenses.getPrixUnitaire' => 'depense.view',
        'depense.search' => 'depense.view',
        'salarie.search' => 'salarie.view',
        'ajax_recap_salarie' => 'salarie.view',
        'ajax_recap_salarie_edit' => 'salarie.view',

        // Plans alimentaires
        'foods-setting.get-foods' => 'foods_setting.view',
    ],

    /*
    |--------------------------------------------------------------------------
    | Correspondances par motif
    |--------------------------------------------------------------------------
    */
    'route_patterns' => [
        'paiement.settings.*' => 'paiement.settings',
    ],

    /*
    |--------------------------------------------------------------------------
    | Préfixes de routes "resource" → préfixe de permission
    |--------------------------------------------------------------------------
    */
    'resource_prefixes' => [
        'bande' => 'bande',
        'batiment' => 'batiment',
        'type_animal' => 'type_animal',
        'animal' => 'animal',
        'nutrition' => 'nutrition',
        'nettoyage' => 'nettoyage',
        'mortalite' => 'mortalite',
        'veterinaire' => 'veterinaire',
        'seanceVaccination' => 'vaccination',
        'programme_vaccination' => 'programme_vaccination',
        'ramassage' => 'ramassage',
        'produit' => 'produit',
        'derive_produit' => 'derive_produit',
        'stock_produit' => 'stock_produit',
        'matiere_premiere' => 'matiere_premiere',
        'stock_matiere_premiere' => 'stock_matiere_premiere',
        'achat' => 'achat',
        'fournisseur' => 'fournisseur',
        'unite' => 'unite',
        'vente' => 'vente',
        'facture' => 'facture',
        'ligne_facture' => 'ligne_facture',
        'client' => 'client',
        'paiement' => 'paiement',
        'depenses' => 'depense',
        'depense' => 'depense',
        'categorie' => 'categorie_depense',
        'compte' => 'caisse',
        'devise' => 'caisse',
        'transfert' => 'caisse',
        'salarie' => 'salarie',
        'paiement_salaire' => 'paie',
        'user' => 'user',
        'agent' => 'agent',
        'setting' => 'setting',
        'foods-setting' => 'foods_setting',
    ],

    /*
    |--------------------------------------------------------------------------
    | Actions de consultation (repli resource)
    |--------------------------------------------------------------------------
    */
    'view_actions' => [
        'index',
        'show',
        'search',
        'export',
        'export.pdf',
        'calendar',
        'calendar.data',
        'getfoodsforanimaltype',
        'getprixunitaire',
        'ajax',
        'check',
        'get-foods',
    ],
];
