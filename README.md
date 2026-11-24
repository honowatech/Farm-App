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
- `RoleUsersSeeder` : crée les comptes de démonstration et rattache les profils métier au tenant `demo`.
- `Phase5DemoSeeder` : crée les données de base dans le tenant `demo` si aucune bande n'existe.
- `MultiTenantDemoSeeder` : crée des fermes de démonstration isolées, leurs membres, forfaits et données métier.
- `MonthActivitySeeder` : ancien seeder SQL non cloisonné ; il n'est plus lancé par `DatabaseSeeder` et s'arrête explicitement si la colonne `tenant_id` existe.

## Modules

- `Depenses` : dépenses et catégories.
- `Salarie` : salariés et paiements de salaires.
- `GestionCaisse` : comptes, devises, transferts.

## Multi-tenant

Les tables `tenants`, `tenant_domains`, `tenant_user`, `plans` et `subscriptions` portent les entreprises, leurs domaines vérifiés, les appartenances par profil, les abonnements, fonctionnalités et limites. Les tables métier sont cloisonnées par `tenant_id` et leur accès Eloquent est filtré par le contexte tenant.

Les routes métier et le portail client exigent une appartenance active. `tenant.resolve` utilise un domaine vérifié, ou le domaine central configuré dans `APP_URL` lorsqu'un utilisateur possède exactement une appartenance active. Les domaines inconnus sont refusés ; le repli local n'est disponible qu'avec `APP_ENV=local`. Le rôle et les permissions sont calculés dans le contexte courant depuis `tenant_user.role_key`.

Le bouton « Démo » connecte à la « Ferme de démonstration » partagée, pré-remplie avec un an de données fictives couvrant l'élevage, la production, les achats, les stocks, les ventes, les dépenses, la caisse, les salariés, la paie, l'entretien et les vaccinations, avec un suivi quotidien détaillé sur les 30 derniers jours. Tous les visiteurs partagent ce même espace de démonstration (données persistantes, non expirées). La souscription depuis la démo ouvre le parcours de création d'entreprise. Une bannière persistante dans l'espace de démonstration propose les forfaits.

L'inscription crée l'entreprise, son administrateur et son abonnement d'essai ou payé. Les vérifications de forfait couvrent les fonctionnalités ainsi que les limites de profils, utilisateurs, bandes, produits et clients. Les paiements d'inscription sont associés à un jeton secret et ne peuvent être consommés qu'une fois. Après expiration de l'essai d'une entreprise, l'accès passe en lecture seule. Son administrateur peut souscrire un forfait via MaMoNi pendant les 30 jours suivants; le même tenant et ses données sont alors conservés. Une fois cette fenêtre passée, la souscription avec conservation des données n'est plus disponible.

Après l'inscription, l'administrateur arrive sur le guide interactif. Celui-ci présente cinq exemples pédagogiques (élevage, dépenses, stocks, ventes et trésorerie) avec des données fictives rendues uniquement dans les vues du guide; aucun enregistrement métier n'est créé ou modifié. La progression est sauvegardée par utilisateur et par entreprise dans `tenant_onboarding_progress`. Le guide peut être relancé depuis le menu du profil. La table est créée par la migration `2026_11_23_000000_create_tenant_onboarding_progress_table`.

Les comptes et entreprises générés par les seeders sont réservés au développement et à la démonstration. Ne pas les utiliser en production ; configurer les domaines, secrets et forfaits de production avant le déploiement.

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
