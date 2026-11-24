<?php

/*
|--------------------------------------------------------------------------
| Tours interactifs (spotlight) de l'application
|--------------------------------------------------------------------------
|
| - `welcome`   : tour de bienvenue, lancé automatiquement au premier accès.
| - `tours`     : surcharge spécifique par nom de route (libellés métier fins).
| - `entities`  : génération automatique des tours index / create / edit pour
|                 toutes les entités. Chaque étape pointe vers un élément
|                 portant l'attribut `data-tour-step="..."`.
|
| Un tour est résolu par `App\Support\FeatureTour::forRoute()` : d'abord la
| surcharge `tours`, sinon la génération générique à partir de `entities`.
|
*/

return [
    'welcome' => [
        'steps' => [
            ['target' => '[data-tour-step="hero"]', 'title' => 'Votre espace entreprise', 'text' => 'Ce guide vous aide à découvrir FarmApp. Vous pouvez le relancer depuis le menu de votre profil.'],
            ['target' => '[data-tour-step="modules"]', 'title' => 'Parcours par module', 'text' => 'Choisissez un exemple guidé pour comprendre les principales étapes d’un module.'],
            ['target' => '[data-tour-step="sandbox"]', 'title' => 'Données fictives isolées', 'text' => 'Les chiffres des exemples ne sont jamais enregistrés dans votre entreprise.'],
            ['target' => '[data-tour-step="progress"]', 'title' => 'Votre progression', 'text' => 'Les parcours terminés sont enregistrés pour votre compte dans cette entreprise.'],
        ],
    ],

    'tours' => [
        'bande.index' => [
            'title' => 'Bandes',
            'steps' => [
                ['target' => '[data-tour-step="create"]', 'title' => 'Créer une bande', 'text' => 'Lancez un nouveau lot d’animaux avec sa date, son effectif et son bâtiment.'],
                ['target' => '[data-tour-step="search"]', 'title' => 'Rechercher et filtrer', 'text' => 'Retrouvez rapidement une bande par son nom ou son statut.'],
                ['target' => '[data-tour-step="table"]', 'title' => 'Liste des bandes', 'text' => 'Consultez l’effectif, le statut et accédez au détail de chaque bande.'],
            ],
        ],
        'vente.index' => [
            'title' => 'Ventes',
            'steps' => [
                ['target' => '[data-tour-step="create"]', 'title' => 'Enregistrer une vente', 'text' => 'Créez une vente pour un client et générez sa facture.'],
                ['target' => '[data-tour-step="search"]', 'title' => 'Rechercher une vente', 'text' => 'Filtrez les ventes par client ou période.'],
                ['target' => '[data-tour-step="table"]', 'title' => 'Liste des ventes', 'text' => 'Suivez les ventes et leur encaissement.'],
            ],
        ],
    ],

    'entities' => [
        'achat' => ['title' => 'Achats', 'noun' => 'un achat', 'create' => 'Enregistrer un achat'],
        'agent' => ['title' => 'Agents', 'noun' => 'un agent', 'create' => 'Créer un agent'],
        'bande' => ['title' => 'Bandes', 'noun' => 'une bande', 'create' => 'Créer une bande'],
        'batiment' => ['title' => 'Bâtiments', 'noun' => 'un bâtiment', 'create' => 'Créer un bâtiment'],
        'client' => ['title' => 'Clients', 'noun' => 'un client', 'create' => 'Créer un client'],
        'derive_produit' => ['title' => 'Produits dérivés', 'noun' => 'un produit dérivé', 'create' => 'Créer un produit dérivé'],
        'detail_fournisseur' => ['title' => 'Fournitures par fournisseur', 'noun' => 'une fourniture', 'create' => 'Associer une matière première'],
        'fournisseur' => ['title' => 'Fournisseurs', 'noun' => 'un fournisseur', 'create' => 'Créer un fournisseur'],
        'matiere_premiere' => ['title' => 'Matières premières', 'noun' => 'une matière première', 'create' => 'Créer une matière première'],
        'mortalite' => ['title' => 'Mortalités', 'noun' => 'une mortalité', 'create' => 'Déclarer une mortalité'],
        'nettoyage' => ['title' => 'Nettoyages', 'noun' => 'un nettoyage', 'create' => 'Planifier un nettoyage'],
        'nutrition' => ['title' => 'Nutritions', 'noun' => 'une nutrition', 'create' => 'Enregistrer une nutrition'],
        'produit' => ['title' => 'Produits', 'noun' => 'un produit', 'create' => 'Créer un produit'],
        'ramassage' => ['title' => 'Ramassages', 'noun' => 'un ramassage', 'create' => 'Enregistrer un ramassage'],
        'seanceVaccination' => ['title' => 'Séances de vaccination', 'noun' => 'une séance de vaccination', 'create' => 'Planifier une séance'],
        'type_animal' => ['title' => 'Types d’animaux', 'noun' => 'un type d’animal', 'create' => 'Créer un type d’animal'],
        'unite' => ['title' => 'Unités de mesure', 'noun' => 'une unité', 'create' => 'Créer une unité'],
        'veterinaire' => ['title' => 'Vétérinaires', 'noun' => 'un vétérinaire', 'create' => 'Créer un vétérinaire'],
        'stock_matiere_premiere' => ['title' => 'Stock de matières premières', 'noun' => 'un mouvement de stock', 'create' => 'Ajouter un mouvement'],
        'stock_produit' => ['title' => 'Stock de produits', 'noun' => 'un mouvement de stock', 'create' => 'Ajouter un mouvement'],
        'vente' => ['title' => 'Ventes', 'noun' => 'une vente', 'create' => 'Enregistrer une vente'],
        'user' => ['title' => 'Utilisateurs', 'noun' => 'un utilisateur', 'create' => 'Créer un utilisateur'],
        'facture' => ['title' => 'Factures', 'noun' => 'une facture', 'create' => null],
        'alerte' => ['title' => 'Alertes', 'noun' => 'une alerte', 'create' => null],
        'rentabilite' => ['title' => 'Rentabilité', 'noun' => 'une bande', 'create' => null],
        'audit' => ['title' => 'Journal d’audit', 'noun' => 'une action', 'create' => null],
        'paiement' => ['title' => 'Paiements', 'noun' => 'un paiement', 'create' => null],
        'programme_vaccination' => ['title' => 'Programme de vaccination', 'noun' => 'un programme', 'create' => null],
        'depenses' => ['title' => 'Dépenses', 'noun' => 'une dépense', 'create' => 'Enregistrer une dépense'],
        'categorie' => ['title' => 'Catégories de dépenses', 'noun' => 'une catégorie', 'create' => 'Créer une catégorie'],
        'compte' => ['title' => 'Comptes', 'noun' => 'un compte', 'create' => 'Créer un compte'],
        'devise' => ['title' => 'Devises', 'noun' => 'une devise', 'create' => 'Créer une devise'],
        'transfert' => ['title' => 'Transferts', 'noun' => 'un transfert', 'create' => 'Créer un transfert'],
        'salarie' => ['title' => 'Salariés', 'noun' => 'un salarié', 'create' => 'Créer un salarié'],
        'paiement_salaire' => ['title' => 'Paiements de salaires', 'noun' => 'un paiement de salaire', 'create' => null],
        'animal' => ['title' => 'Animaux', 'noun' => 'un animal', 'create' => 'Créer un animal'],
    ],
];
