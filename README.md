# Farm-App

Application de gestion d'élevage avicole : bâtiments, bandes, animaux, nutrition, vaccinations, nettoyages, ramassage (œufs, fientes…), stocks de produits et matières premières, achats/fournisseurs, ventes/factures/clients, salariés & paie, dépenses et caisse.

## Prérequis

- PHP >= 8.2 (recommandé 8.3)
- Composer
- MySQL / MariaDB
- Node.js + npm (si les assets doivent être recompilés)

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
# Renseigner DB_DATABASE, DB_USERNAME, DB_PASSWORD dans .env
php artisan migrate --seed
php artisan serve
```

## Comptes de démonstration

Le seeder crée un compte par profil. Mot de passe commun : **`11111111`**.

| Profil | Email | Périmètre principal |
|---|---|---|
| Administrateur | `admin@farmapp.test` | Tous les modules |
| Gestionnaire | `gestionnaire@farmapp.test` | Élevage, production, achats, ventes, finances (lecture), réglages |
| Agent de terrain | `agent@farmapp.test` | Bandes, nutrition, vaccination, nettoyage, mortalité, ramassage, stocks (lecture) |
| Vétérinaire | `veterinaire@farmapp.test` | Bandes, nutrition, vaccinations, mortalité, fiches vétérinaires |
| Magasinier | `magasinier@farmapp.test` | Produits, stocks, matières premières, achats, fournisseurs, unités |
| Commercial | `commercial@farmapp.test` | Ventes, factures, clients, paiements, produits/stocks (lecture) |
| Comptable | `comptable@farmapp.test` | Dépenses, caisse, rentabilité, achats, salaires & paie, ventes (lecture) |
| Caissier | `caissier@farmapp.test` | Encaissements, caisse, dépenses, ventes/clients (lecture) |
| RH | `rh@farmapp.test` | Salariés, paie, agents |
| Client | `client@farmapp.test` | Tableau de bord et ses factures uniquement |

> ⚠️ Ces comptes sont destinés à la démonstration. Les supprimer ou changer les mots de passe avant toute mise en production.

## Rôles et permissions (ACL)

L'ACL est native (sans package externe) et stockée en base :

- `permissions` : catalogue des 71 permissions (`bande.view`, `vente.manage`, `caisse.view`, …).
- `permission_type_user` : table pivot entre les profils (`type_users`) et les permissions.
- `config/permissions.php` : source de vérité (catalogue, affectation des profils, correspondance route → permission).
- `app/Http/Middleware/AgentAccess.php` : vérifie la permission associée à la route courante. En cas de refus, redirection vers la page précédente (ou le tableau de bord) avec un toast d'erreur ; les appels AJAX/JSON reçoivent une réponse 403.
- `app/Models/User.php` : helpers `hasPermission()`, `hasAnyPermission()`, `canAccessRoute()`, `roleName()`, `isAdmin()`.
- `resources/views/shared/menu.blade.php` : menu unique filtré par permissions (`@canpermission`, `@cananypermission`, `@canroute`).

Ajouter ou modifier un profil :

1. Éditer `config/permissions.php` (`roles`).
2. Relancer `php artisan db:seed --class=RolePermissionSeeder`.
3. Vider les caches : `php artisan optimize:clear`.

## Données de démonstration

```bash
php artisan db:seed
```

Le seed est **additif et idempotent** : il conserve les données existantes et peut être rejoué sans créer de doublons.

- `RolePermissionSeeder` : crée les 10 profils et synchronise leurs permissions.
- `RoleUsersSeeder` : crée les 10 comptes de démonstration (mot de passe défini uniquement à la création).
- `MonthActivitySeeder` : génère 30 jours d'activité sur les modules accessibles aux profils (nutrition, ramassages, vaccinations, nettoyages, mortalité, achats/stocks, ventes/factures/paiements, dépenses, caisse, salaires/paie, coûts de bande).
- `Phase5DemoSeeder` : ne s'exécute que si la table `bandes` est vide (référentiels de base).

## Modules

- `Depenses` : dépenses et catégories.
- `Salarie` : salariés et paiements de salaires.
- `GestionCaisse` : comptes, devises, transferts.

## Pagination AJAX

- Les tableaux de liste sont paginés côté serveur (10 à 30 lignes par page selon l'écran).
- La navigation entre les pages se fait sans rechargement complet :
  - les liens `.pagination a[href]` sont interceptés dans `layouts/app.blade.php` ;
  - les tableaux pilotés par la fonction `mettre(page)` (formulaire `#search`) sont également chargés en AJAX.
- Seuls le tableau et le bloc de pagination sont remplacés ; les filtres et le reste de la page restent en place.
- En cas d'échec de la requête, la navigation classique prend le relais.

## Tests

```bash
php artisan test
```

## Déploiement

- Pointer le domaine / sous-domaine sur `public/` (pas sur la racine du projet).
- `APP_ENV=production`, `APP_DEBUG=false`.
- Après déploiement :
  ```bash
  php artisan migrate --force
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```
