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

## Comptes par défaut (seed)

| Rôle | Email | Mot de passe |
|---|---|---|
| Administrateur | test@example.com | 11111111 |

> ⚠️ Supprimer ou modifier ce compte de test avant toute mise en production.

## Rôles

- `admin` : accès complet à l'application.
- `agent` : bandes, nutrition, nettoyage, vaccination (accès restreint).
- `client` : pas d'espace de gestion (accès refusé aux écrans internes).

## Modules

- `Depenses` : dépenses et catégories.
- `Salarie` : salariés et paiements de salaires.
- `GestionCaisse` : comptes, devises, transferts.

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
