# Farm-App — Audit technique & plan de mise à jour

> Document destiné au développeur en charge des mises à jour.
> Audit réalisé le 09/11/2026 sur la branche principale (commit `9f0c547 first commit`).
> Les références sont données sous la forme `fichier:ligne`.

---

## 0. TL;DR — à faire immédiatement (avant toute autre tâche)

| # | Action | Pourquoi |
|---|--------|----------|
| 1 | **Supprimer la route `/migrate`** (`routes/web.php:145`) | N'importe quel visiteur anonyme peut lancer les migrations en production. |
| 2 | **Mettre les modules `Depenses` et `Salarie` derrière `auth` + rôle admin** | Toutes leurs routes (création/suppression de dépenses, salariés, paiements de salaires) sont **accessibles sans connexion**. |
| 3 | **Restreindre les comptes non-admin et désactiver `/register`** | Un compte « client » (créé automatiquement à chaque nouveau client) peut se connecter et voir le tableau de bord admin, les ventes, achats, réglages alimentaires… |
| 4 | **Protéger `/translations`** (`routes/web.php:143`) | Gestionnaire de traductions accessible sans authentification. |
| 5 | **Retirer `.env` du dépôt et changer tous les secrets** (`APP_KEY`, mot de passe BDD, mot de passe mail) | Le fichier `.env` réel est versionné dans Git. Les secrets doivent être considérés comme compromis. |
| 6 | En production : `APP_ENV=production`, `APP_DEBUG=false` | Avec debug actif, les erreurs exposent code, requêtes SQL et variables d'environnement (Ignition). |

Ces 6 points représentent environ **½ journée** de travail et ferment les failles les plus graves.

---

## 1. Vue d'ensemble du projet

**Fonction :** application de gestion d'élevage (avicole principalement) : bâtiments, bandes, animaux, nutrition / plans alimentaires, vaccinations, nettoyages, ramassage (œufs, fientes…), stocks de produits et matières premières, achats/fournisseurs, ventes/factures/clients, salariés & paie, dépenses, caisse.

| Élément | État constaté |
|---|---|
| Framework | Laravel **10.48** (fin du support sécurité : février 2025 → **plus maintenu**) |
| PHP | `composer.json` : `^8.1` ; `composer.lock` exige **≥ 8.2** ; l'hébergeur (cPanel, `.htaccess`) force **PHP 8.1** → crash constaté dans `error_log` |
| Modules | `nwidart/laravel-modules` **8.0.0** (très ancien) — modules `Depenses`, `Salarie`, `GestionCaisse` (+ entrée fantôme `GestionCaissse` dans `modules_statuses.json`) |
| Front | Template admin **Vuexy** (Bootstrap 4 + jQuery 3.5.1) copié en dur dans `public/app-assets` (51 Mo) et **dupliqué** dans `Modules/GestionCaisse/Resources/assets` (49 Mo). Vite/Bootstrap 5 déclarés dans `package.json` mais non utilisés. |
| Code applicatif | 33 contrôleurs (~5 200 lignes), 33 modèles, 117 vues Blade (~19 500 lignes), 2 helpers globaux |
| Tests | Uniquement les `ExampleTest` de Laravel → **0 test réel** |
| CI/CD | Aucune (pas de `.github/`) — déploiement visiblement par FTP (`.ftpquota`) |
| Documentation | `README.md` = README par défaut de Laravel |
| Taille du dépôt | **13 373 fichiers**, `.git` = 70 Mo (vendor, node_modules, zip, cache de vues versionnés) |

### Note globale

| Domaine | Note /10 | Commentaire |
|---|---|---|
| Sécurité | **2** | Routes critiques publiques, secrets versionnés, XSS, pas d'autorisation fine |
| Intégrité des données | **3** | Pas de transactions, prix envoyés par le client, calculs de stock non atomiques |
| Qualité / maintenabilité du code | **4** | Logique dans les contrôleurs, duplication, nommage incohérent, code mort |
| Hygiène du dépôt / déploiement | **2** | Pas de `.gitignore`, vendor/zip/env commités, déploiement manuel |
| Performance | **5** | N+1 dans les calculs de stock, assets lourds non minifiés/dupliqués |
| UI / UX | **5** | Template pro mais incohérences, accessibilité faible, mobile perfectible |
| Tests / CI | **0** | Rien |

---

## 2. Constats détaillés

Sévérité : 🔴 Critique · 🟠 Élevée · 🟡 Moyenne · 🟢 Faible

### 2.1 Sécurité

#### SEC-01 🔴 Route `/migrate` publique
`routes/web.php:145-148` — exécute `php artisan migrate --force` pour n'importe quel visiteur.
**Correction :** supprimer la route. Les migrations doivent être lancées par le pipeline de déploiement (ou en SSH). Si l'hébergeur n'offre pas de SSH, créer une commande protégée par un token signé à usage unique, jamais une route ouverte.

#### SEC-02 🔴 Modules `Depenses` et `Salarie` sans authentification
`Modules/Depenses/Routes/web.php`, `Modules/Salarie/Routes/web.php` — seules les middlewares `web` sont appliquées. Résultat de `php artisan route:list` : `depenses/*`, `categorie/*`, `salarie/*`, `paiement_salaire/*`, `salarie_ajax*` sont accessibles anonymement (lecture **et** écriture/suppression).
**Correction :** envelopper toutes les routes de module dans `Route::middleware(['web','auth','agent_access'])` (ou mieux, un middleware `role:admin`). Faire de même pour `GestionCaisse` qui n'a que `auth` (un agent ou un client connecté peut accéder à la caisse).

#### SEC-03 🔴 Comptes « client » (et inscription publique) trop permissifs
- `VenteController::save_client` crée pour chaque client un **compte utilisateur avec mot de passe** (rôle `client`). Ce compte peut se connecter et :
  - accéder à toutes les routes du premier groupe (`routes/web.php:46-124`) qui **ne passent pas** par `agent_access` : ventes (`/save`, `/detail-vente/{id}`, `/selection-client`…), recherche d'achats, ramassage AJAX, `settings/foods-setting` (création/modification/suppression), `gestion-bande`, `terminer-bande`, calendrier nutrition, etc. ;
  - voir le **tableau de bord admin** : `HomeController.php:38` n'envoie vers la vue agent que le rôle `agent`, tous les autres rôles reçoivent la vue admin.
- `Auth::routes()` (`routes/web.php:141`) laisse `/register` ouvert. `RegisterController::create()` n'attribue pas de `id_type_user` (colonne NOT NULL + FK) → l'inscription se termine en erreur SQL 500, mais elle reste exposée et deviendrait une faille si le schéma changeait.
- `AgentAccess.php:54` et `HomeController.php:38` font `$user->type_user->libelle` sans gérer un rôle absent.

**Correction :** `Auth::routes(['register' => false])` ; tous les groupes de routes métier derrière un middleware de rôle explicite (refus par défaut) ; les clients n'ont pas besoin de compte de connexion (sauf futur espace client dédié) ; envoyer chaque rôle vers sa propre vue d'accueil et refuser l'accès si le rôle est inconnu.

#### SEC-04 🔴 Secrets versionnés
`.env` est commité (avec `APP_KEY`, mot de passe BDD et mot de passe mail renseignés). Aucun `.gitignore` à la racine.
**Correction :**
1. Ajouter un `.gitignore` Laravel standard (voir §4.1).
2. `git rm --cached .env` puis **régénérer `APP_KEY`** et **changer les mots de passe** BDD/mail.
3. Purger l'historique (`git filter-repo --path .env --invert-paths`) si le dépôt a été/sera partagé.

#### SEC-05 🔴 Gestionnaire de traductions public
`routes/web.php:143` — la route `/translations` ajoutée à la main pointe vers le contrôleur du package barryvdh/laravel-translation-manager **sans `auth`**. (Les routes propres du package sont bien protégées par `auth` dans `config/translation-manager.php`, mais pas pour les admins uniquement.)
**Correction :** supprimer la route manuelle de `web.php`, et restreindre le middleware du package à `auth` + admin — ou retirer le package en production.

#### SEC-06 🟠 XSS stocké via les messages flash
`resources/views/admin/templates/template.blade.php:377` et `Modules/GestionCaisse/Resources/views/template/layout.blade.php:358,367` affichent `{!! session('message') !!}` **dans une chaîne JavaScript**. Les messages contiennent des données utilisateur (ex. `UserController.php:69`, `:134`, `:153` → nom de l'utilisateur). Un nom contenant `"` ou `</script>` exécute du JS chez l'admin.
Idem `resources/views/admin/pages/nettoyage/info.blade.php:5` (`{!! $liste !!}` construit avec les noms d'agents).
**Correction :** ne jamais mettre de HTML dans les messages flash ; afficher avec `@json(session('message'))` dans le JS, ou `{{ }}` en HTML. Supprimer les `<b class='text-success'>` des contrôleurs.

#### SEC-07 🟠 Upload de logo : SVG autorisé, contrôle par extension client
`LogoController.php:31-38` — vérification sur `getClientOriginalExtension()` (falsifiable), SVG autorisé (un SVG peut contenir du JavaScript → XSS), fichier déplacé directement dans `public/`.
**Correction :** `$request->validate(['logo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'])`, stockage via `Storage::disk('public')`, pas de SVG (ou sanitation dédiée).

#### SEC-08 🟠 Autorisation trop grossière
`AgentAccess` ne gère que `admin` / `agent` via une liste blanche de noms de routes codée en dur. Le rôle `client` existe mais n'a aucune règle. Plusieurs routes sont déclarées **deux fois** (`/check`, `/search-nutruition`, `/get-form`, `/gestion-bande/*`, `/recherche-bande` : `routes/web.php:84-88` et `:128-135`) avec des middlewares différents — la dernière déclaration gagne, ce qui rend le comportement difficile à prévoir.
**Correction :** introduire des **Policies/Gates** Laravel (ou `spatie/laravel-permission`), un middleware `role:` explicite par groupe de routes, et supprimer les doublons.

#### SEC-09 🟠 Fuite du mot de passe dans les réponses JSON
`VenteController.php:187` — en cas d'erreur de validation, `'old' => $request->all()` renvoie le **mot de passe** saisi ; `:195` renvoie `$e->getMessage()` (détails internes).
**Correction :** `$request->except('password')`, message générique + `report($e)`.

#### SEC-10 🟡 Pas de limitation de tentatives / politique de mot de passe
Seeder avec un admin `test@example.com / 11111111` (`database/seeders/DatabaseSeeder.php`). Vérifier qu'il n'existe pas en production. Le champ `statut` (activé/désactivé, `UserController::destroy`) n'est **pas vérifié au login** : un utilisateur désactivé peut toujours se connecter.
**Correction :** surcharger `credentials()` dans `LoginController` pour ajouter `statut = 1` ; règle `Password::defaults()` ; supprimer le compte de test.

#### SEC-11 🟡 Divers
- Recherche `like '%'.$input.'%'` sans échappement de `%`/`_` (pas d'injection SQL, mais recherche détournable / lente).
- `destroy` sur `UserController` sert en fait à activer/désactiver (sémantique trompeuse).
- `console.log` restants dans les vues (4 occurrences).
- En-têtes de sécurité HTTP absents (CSP, X-Frame-Options, HSTS) — à ajouter via middleware ou `.htaccess`.

---

### 2.2 Intégrité des données & logique métier

#### DATA-01 🔴 Ventes sans transaction (factures orphelines, stock incohérent)
`VenteController::store` (`:55-135`) crée la **facture** et la **vente**, puis boucle sur les lignes ; si une ligne a un stock insuffisant (`:96-99`) on fait `redirect()->back()` **sans annuler** la facture, la vente, ni les mouvements de stock déjà enregistrés pour les lignes précédentes.
**Correction :** vérifier tous les stocks **avant** d'écrire, puis tout enregistrer dans `DB::transaction()` avec `lockForUpdate()` sur les stocks concernés.

#### DATA-02 🔴 Montants calculés à partir des données du navigateur
`VenteController.php:62-68,124-125` — `total`, `prix_unitaire` viennent du JSON `tableau_donnees` envoyé par le client (texte « FCFA » nettoyé à la main). Un utilisateur peut modifier les prix dans le navigateur.
**Correction :** recalculer côté serveur à partir de `Produit::prix_unitaire` ; valider `tableau_donnees` (`array`, `*.id_produit exists:produits,id`, `*.qte integer|min:1`, `*.mode_paiement in:…`, `*.type in:…`).

#### DATA-03 🟠 TVA incohérente
`VenteController.php:65-74` — `total_ttc == total_ht` mais `tva = total_ttc * 0.2`. La TVA est soit fausse, soit inutile. Le taux « 20 % » est codé en dur (« Exemple de TVA »). Au Cameroun le taux standard est 19,25 %.
**Correction :** taux paramétrable dans `settings`, `total_ttc = total_ht + tva`, arrondis en entier (FCFA sans décimales).

#### DATA-04 🟠 Colonne `prenom` supprimée mais toujours utilisée
Migration `2024_10_05_100807_delete_column_prenom_in_to_clients.php` supprime `clients.prenom`, mais `VenteController::save_client` (`:147,158,167`) la valide et l'enregistre → **erreur SQL** à chaque création de client depuis l'écran de vente.
**Correction :** aligner le formulaire `vente/create_client` et le contrôleur sur le schéma actuel (un seul champ `nom`), et factoriser avec `ClientController::store`.

#### DATA-05 🟠 Calcul de stock non atomique et dupliqué
- Ventes : `dernier_stock` = dernière ligne `Stock_produit` (`VenteController.php:89-91`).
- Ramassage : `RamassageController.php:232-235` recalcule via le helper `StockDerive()` qui additionne **tous** les mouvements.
- Helpers `app/Helpers/StocksFunctions.php` : 3 fonctions quasi identiques.
Deux méthodes de calcul différentes = risques d'écarts ; deux requêtes simultanées peuvent produire le même `dernier_stock`.
**Correction :** créer un `StockService` unique (calcul par `SUM` SQL, verrouillage, une seule source de vérité), utilisé par ventes, achats, ramassage, nutrition.

#### DATA-06 🟡 Validation absente ou partielle
82 appels `->validate()` pour 33 contrôleurs, souvent **après** usage des données (ex. `UserController::store` lit le type et fait `findOrFail` avant de valider). `email` validé sans règle `email`. Aucun `FormRequest`.
**Correction :** une `FormRequest` par action `store`/`update`.

#### DATA-07 🟡 Schéma de base de données
- 76 migrations dont beaucoup de correctifs successifs (`add_columb_…`, typos), un `database/migrations.zip` et des `.zip` de modules dans le dépôt.
- Clés étrangères créées dans une migration séparée (`create_foreign_keys`), colonnes nullable ajoutées après coup (`make_id_derive_produit_nullable…`).
- `Typeusers` / `Type_users` / `TypeUsers` utilisés indifféremment (`VenteController.php:153` utilise `TypeUsers` alors que la classe est `Typeusers` — fonctionne uniquement parce que PHP est insensible à la casse des classes ; casse sous un autoloader strict).
**Correction :** vérifier la cohérence du schéma en production, ajouter index et FK manquants (champs `id_*`), corriger les noms de classes. Ne **pas** réécrire les migrations existantes déjà jouées en production : ajouter de nouvelles migrations.

#### DATA-08 🟡 Matricule de facture
`'FACT-' . uniqid()` (`VenteController.php:76`) : non séquentiel, illisible, non conforme à une numérotation comptable.
**Correction :** numérotation séquentielle par année (`FACT-2026-000123`) générée en transaction.

---

### 2.3 Architecture & qualité du code

| ID | Sév. | Constat | Correction |
|---|---|---|---|
| ARCH-01 | 🟠 | Toute la logique métier est dans les contrôleurs (jusqu'à 370 lignes : `NutritionController`, `RamassageController`, `Foods_settingController`, `VenteController`). | Extraire en *Services* / *Actions* (`StockService`, `VenteService`, `NutritionPlanner`…). |
| ARCH-02 | 🟠 | Doublons fonctionnels : `SalariesController` & `Paiement_salairesController` (app) **et** module `Salarie` ; `Autre_depensesController` (app) **et** module `Depenses`. | Garder une seule implémentation (module ou app), supprimer l'autre. |
| ARCH-03 | 🟡 | Nommage incohérent : `Type_animalController`, `Stock_matiere_premiere`, `teminer_bande`, `search-nutruition`, `/ajout de client` (**URL avec espaces**), `choix-annimal`, `textBreack.php`, `add_columb_…`. | Convention Laravel (PascalCase pour classes, kebab-case pour URLs). Renommer progressivement avec redirections si besoin. |
| ARCH-04 | 🟡 | Helpers globaux (`app/Helpers/*.php`) chargés via `composer.json → files`. `function_exists('StockProduit')` vs fonction `Stockproduit`. | Remplacer par des classes de service / accessors de modèle. |
| ARCH-05 | 🟡 | 24 `dd()` commentés, code commenté, blocs `/////////` décoratifs, `show()` vides. | Nettoyage + `laravel/pint` (déjà en dépendance) en CI. |
| ARCH-06 | 🟡 | `modules_statuses.json` contient `GestionCaissse` (module inexistant). `nwidart/laravel-modules` 8.0 est fait pour Laravel 8. | Supprimer l'entrée ; mettre à jour le package avec la version de Laravel. |
| ARCH-07 | 🟡 | Mélange FR/EN dans le code, langue d'interface en dur dans beaucoup de vues alors que `resources/lang/{fr,en}` existe. | Choisir le français comme langue de code métier ou l'anglais, et passer tous les libellés par `__()`. |
| ARCH-08 | 🟢 | `README.md` par défaut Laravel. | Rédiger un README : installation, variables d'env, comptes, déploiement. |

---

### 2.4 Dépôt, dépendances & déploiement

| ID | Sév. | Constat | Correction |
|---|---|---|---|
| OPS-01 | 🔴 | Pas de `.gitignore` racine : `vendor/`, `node_modules/`, `.env`, `error_log`, `.ftpquota`, `storage/framework/views/*.php` (cache compilé), `*.zip` sont versionnés. | Ajouter `.gitignore`, `git rm -r --cached` sur ces chemins. |
| OPS-02 | 🔴 | Incompatibilité PHP : lock ≥ 8.2, serveur 8.1 → `error_log` montre l'application en panne (oct. 2024). | Passer l'hébergement en **PHP 8.3** (8.2 minimum) via cPanel ; fixer `"config": {"platform": {"php": "8.3"}}` dans `composer.json`. |
| OPS-03 | 🟠 | Laravel 10 hors support sécurité. | Migrer vers **Laravel 12** (PHP 8.2+), via 10 → 11 → 12 (voir §3, phase 3). Mettre à jour `laravel/ui`, `sanctum`, `nwidart/laravel-modules`, `barryvdh/laravel-translation-manager`. |
| OPS-04 | 🟠 | Webroot = racine du projet (`.htaccess` réécrit vers `public/index.php`) : `.env`, `composer.json`, `storage/` potentiellement téléchargeables selon la config Apache. | Pointer le domaine/sous-domaine sur `public/`. À défaut, `.htaccess` qui bloque explicitement tout sauf `public/`. |
| OPS-05 | 🟠 | Déploiement manuel FTP, aucune CI. | GitHub Actions : `composer install`, `pint --test`, `phpunit`, `npm run build` ; déploiement (SSH/rsync ou FTP-deploy action) avec `php artisan migrate --force`, `config:cache`, `route:cache`, `view:cache`. |
| OPS-06 | 🟡 | ~100 Mo de template Vuexy (dont 49 Mo dupliqués dans le module caisse, deux `.zip` de 19 Mo). | Un seul jeu d'assets, ne garder que les fichiers réellement utilisés. **Vérifier la licence Vuexy** (template commercial ThemeForest) ; `<meta description>` contient encore le texte marketing de Vuexy. |
| OPS-07 | 🟡 | Aucune sauvegarde automatisée documentée. | `spatie/laravel-backup` (BDD + uploads) planifié, rétention 30 jours, copie hors serveur. |
| OPS-08 | 🟢 | Logs : `LOG_LEVEL=debug`. | `LOG_CHANNEL=daily`, `LOG_LEVEL=warning` en prod ; éventuellement Sentry. |

---

### 2.5 Performance

| ID | Sév. | Constat | Correction |
|---|---|---|---|
| PERF-01 | 🟠 | Helpers de stock : boucles PHP sur tous les mouvements de chaque produit/dérivé (N+1). Appelés dans les listes → requêtes × nombre de lignes. | Agrégation SQL (`withSum` / `selectRaw('SUM(CASE …)')`) ou colonne de stock courant maintenue par le `StockService`. |
| PERF-02 | 🟡 | `VenteController::index` charge `Produit::all()` et `Client::all()` en plus de la pagination ; nombreux `::all()` dans les `create()`. | Charger uniquement le nécessaire, select2 en AJAX pour les longues listes, `with()` pour les relations. |
| PERF-03 | 🟡 | Layout : ~30 balises `<script>` et ~35 CSS, certains **chargés deux fois** (`vendors.min.css`, `toastr.min.css`, `form-pickers.js`), CSS dark/bordered/horizontal/vertical tous chargés. | Passer par Vite (déjà configuré) : un bundle CSS et un bundle JS par layout, minifiés et versionnés. |
| PERF-04 | 🟢 | Pas d'index sur les colonnes de recherche (`date_vente`, `name`, `email`…) ni sur les FK. | Migration d'index. |
| PERF-05 | 🟢 | En prod : pas de `config:cache` / `route:cache` / `optimize`. | À intégrer au script de déploiement. |

---

### 2.6 Tests

Aucun test métier. Priorité aux tests de **non-régression** sur ce qui touche à l'argent et au stock :
1. Contrôle d'accès : un invité / un agent / un client ne peut pas accéder aux routes admin (un test par groupe de routes — protège contre SEC-01/02/03).
2. Vente : stock insuffisant ⇒ rien n'est écrit ; prix recalculés côté serveur ; totaux/TVA corrects.
3. Ramassage / achat ⇒ mouvements de stock corrects.
4. Nutrition : génération du calendrier selon `food_setting_versions`.

Utiliser SQLite en mémoire dans `phpunit.xml` + factories existantes (`database/factories`).

---

### 2.7 UI / UX

#### Constat général
Le template Vuexy donne une base visuelle correcte, mais il est utilisé « tel quel » avec beaucoup de restes de démo, et l'expérience n'est pas pensée pour l'utilisateur terrain (agent d'élevage, souvent sur téléphone).

| ID | Sév. | Constat | Recommandation |
|---|---|---|---|
| UX-01 | 🟠 | **Mobile / terrain** : les agents saisissent nutrition, ramassage, vaccinations, nettoyages — probablement sur smartphone dans le bâtiment. Formulaires longs, tableaux larges, select2 peu adaptés au tactile. `user-scalable=0` empêche le zoom. | Écrans agent « mobile-first » : gros boutons, saisie en 1 écran par action (« Ramassage du jour – Bâtiment A »), clavier numérique (`inputmode="numeric"`), autoriser le zoom. Envisager une PWA avec saisie hors-ligne (réseau instable en zone rurale). |
| UX-02 | 🟠 | **Messages d'erreur invisibles ou incohérents** : succès via toastr, erreurs tantôt via `session('error')`, tantôt via `session('modal')` (`LogoController`), tantôt `withError()` (`AgentAccess`). Une création client depuis la vente échoue (DATA-04) sans retour clair. | Un seul composant de notification (succès / erreur / avertissement) + affichage des erreurs de validation sous chaque champ (`@error`). |
| UX-03 | 🟠 | **Navigation** : ~25 modules à plat dans le menu (`resources/views/admin/menu`). Termes techniques (« Dérivé produit », « Matière première », « Ligne facture »). | Regrouper par métier : **Élevage** (Bâtiments, Bandes, Nutrition, Santé/Vaccins, Hygiène), **Production** (Ramassage, Stocks), **Commerce** (Ventes, Clients, Factures), **Achats** (Fournisseurs, Matières premières), **Finances** (Caisse, Dépenses, Salaires), **Paramètres**. Fil d'Ariane sur chaque page. |
| UX-04 | 🟠 | **Tableau de bord** (`admin/pages/home/home.blade.php`, 767 lignes) : peu d'indicateurs décisionnels. | KPIs utiles à un éleveur : effectif vivant par bande, **taux de mortalité**, **taux de ponte**, **indice de consommation** (aliment/kg ou /œuf), stock d'aliment en jours, CA du mois, marge par bande, alertes (vaccins à venir, stock bas, nutrition non faite aujourd'hui). |
| UX-05 | 🟡 | **Langue & formats** : `<html lang="en">` alors que l'appli est en français ; montants en texte « FCFA » reparsés côté serveur ; formats de date mixtes. | `lang` dynamique (`app()->getLocale()`), formatage monétaire centralisé (`number_format($v, 0, ',', ' ') . ' FCFA'`), dates `d/m/Y` partout. |
| UX-06 | 🟡 | **Confirmations d'actions destructives** : `destroy` sert parfois à désactiver (utilisateur), parfois à supprimer ; libellés d'icônes uniquement (SweetAlert avec icônes ✓/✗ sans texte). | Boutons explicites (« Désactiver », « Supprimer définitivement »), texte dans les boutons de confirmation, possibilité d'annuler (soft delete déjà en place sur plusieurs modèles). |
| UX-07 | 🟡 | **Accessibilité** : contrastes non vérifiés, icônes sans libellé, champs sans `<label for>` sur certaines vues, focus clavier non géré dans les modales. | Passe WCAG AA de base : labels, `aria-label` sur boutons-icônes, contraste ≥ 4.5:1, navigation clavier. |
| UX-08 | 🟡 | **Recherche / listes** : recherche texte libre sur une seule colonne (ex. ventes par date en `LIKE`), pas de filtres (période, client, bande), pas d'export. | Filtres par période/bande/client, tri des colonnes, export **Excel/PDF** (factures, état de stock, paie). |
| UX-09 | 🟡 | **Factures** : pas de modèle imprimable propre (logo, coordonnées ferme, numéro séquentiel, mentions). | Facture PDF (`barryvdh/laravel-dompdf`) + envoi WhatsApp/e-mail. |
| UX-10 | 🟢 | Restes de démo Vuexy (meta description, avatars, pages e-commerce, `app-ecommerce-details.css`), coquilles dans le layout (`href="{{logo()}}>"`, `rel="stylesheet }}"` l. 24 et 33). | Nettoyer le layout, une charte simple (couleur primaire, logo, typographie). |
| UX-11 | 🟢 | Deux layouts quasi identiques (`admin/templates` et `agent/templates`, ~590 lignes chacun) + un troisième dans `GestionCaisse`. | Un seul layout Blade + composants (`<x-card>`, `<x-form.input>`, `<x-table>`), menu selon le rôle. |

---

## 3. Plan de mise à jour

Estimations indicatives pour un développeur Laravel confirmé (j = jour·homme).

### Phase 0 — Urgences sécurité (½ à 1 j) — **à livrer en premier**
- [ ] SEC-01 Supprimer `/migrate`.
- [ ] SEC-02 `auth` + rôle admin sur `Depenses`, `Salarie`, `GestionCaisse`.
- [ ] SEC-03 Désactiver `/register` ; middleware de rôle sur tous les groupes de routes ; page d'accueil par rôle.
- [ ] SEC-05 Protéger `/translations`.
- [ ] SEC-04 / OPS-01 `.gitignore`, retirer `.env`, `vendor`, `node_modules`, zips, `error_log`, cache de vues du dépôt ; **rotation des secrets**.
- [ ] Prod : `APP_ENV=production`, `APP_DEBUG=false`, webroot sur `public/` (OPS-04).
- [ ] OPS-02 Passer le serveur en PHP 8.3.

**Livrable :** `php artisan route:list` ne montre plus aucune route métier sans `auth`. Test automatisé qui le vérifie.

### Phase 1 — Stabilisation & intégrité des données (5 à 8 j)
- [ ] SEC-06 / SEC-07 / SEC-09 / SEC-10 (XSS, upload, fuite password, login des comptes désactivés).
- [ ] SEC-08 Policies/Gates ou `spatie/laravel-permission` ; suppression des routes en double.
- [ ] DATA-01 / DATA-02 / DATA-03 Refonte de `VenteController::store` (validation, prix serveur, transaction, TVA paramétrable).
- [ ] DATA-04 Corriger la création client depuis la vente.
- [ ] DATA-05 `StockService` unique + verrouillage.
- [ ] DATA-06 `FormRequest` pour toutes les actions d'écriture.
- [ ] DATA-08 Numérotation de factures.
- [ ] Tests (§2.6) : contrôle d'accès + ventes + stock.
- [ ] Sauvegardes automatiques (OPS-07).

### Phase 2 — Qualité du code & outillage (4 à 6 j)
- [ ] OPS-05 CI GitHub Actions (pint, phpunit, build) + script de déploiement.
- [ ] ARCH-01 Extraction des services (Vente, Stock, Nutrition, Ramassage).
- [ ] ARCH-02 Supprimer les doublons Salarié/Dépenses (app vs modules).
- [ ] ARCH-04/05/06 Helpers → services, nettoyage code mort, `pint`.
- [ ] PERF-01/02/04 N+1, `::all()`, index.
- [ ] DATA-07 Migration de cohérence du schéma (FK, index, NOT NULL).
- [ ] ARCH-08 README.

### Phase 3 — Montée de version (3 à 5 j)
- [ ] Laravel 10 → 11 → **12** (guide officiel d'upgrade ; Laravel Shift possible).
- [ ] `nwidart/laravel-modules` → version compatible Laravel 12 (attention : changements de structure `Modules/*/app`, `module.json`, Vite par module).
- [ ] `laravel/ui`, `sanctum`, `translation-manager` (ou le retirer), `phpunit` 11.
- [ ] Re-passer toute la suite de tests après chaque saut de version.

### Phase 4 — UI/UX (8 à 15 j selon ambition)
- [ ] OPS-06 / PERF-03 / UX-10 / UX-11 Assets via Vite, un seul layout, composants Blade, nettoyage Vuexy (ou migration vers un thème Bootstrap 5 / Tailwind libre de droits si la licence Vuexy n'est pas en règle).
- [ ] UX-03 Nouvelle navigation par domaine métier + fil d'Ariane.
- [ ] UX-02 / UX-06 Notifications et confirmations unifiées.
- [ ] UX-01 Écrans agent mobile-first (nutrition, ramassage, mortalité, vaccination, nettoyage).
- [ ] UX-04 Tableau de bord KPIs + alertes.
- [ ] UX-05 / UX-07 Langue, formats, accessibilité.
- [ ] UX-08 / UX-09 Filtres, exports Excel/PDF, facture imprimable.

### Phase 5 — Évolutions fonctionnelles suggérées (à chiffrer)
- Suivi de **mortalité** journalier par bande et calcul automatique de l'effectif.
- **Alertes** : rappel vaccins (selon programme par type d'animal), stock d'aliment bas, bande non nourrie.
- **Coût de revient / marge par bande** (achat poussins + aliment + vaccins + main d'œuvre vs ventes).
- **PWA hors-ligne** pour la saisie terrain.
- **Journal d'audit** (qui a modifié quoi : `spatie/laravel-activitylog`), indispensable pour le stock et la caisse.
- Paiement **Mobile Money** (Orange Money / MTN MoMo) comme mode de paiement tracé sur les factures.

### Récapitulatif

| Phase | Contenu | Estimation |
|---|---|---|
| 0 | Urgences sécurité | 0,5 – 1 j |
| 1 | Stabilisation, intégrité, tests critiques | 5 – 8 j |
| 2 | Qualité, CI, performance | 4 – 6 j |
| 3 | Laravel 12 | 3 – 5 j |
| 4 | UI/UX | 8 – 15 j |
| **Total** | | **≈ 21 – 35 j** |

---

## 4. Annexes techniques

### 4.1 `.gitignore` recommandé (racine)
```gitignore
/vendor/
/node_modules/
/public/build/
/public/hot
/public/storage
/storage/*.key
/storage/framework/cache/data/*
/storage/framework/sessions/*
/storage/framework/views/*
/storage/logs/*
!/storage/**/.gitignore
.env
.env.backup
.env.production
.phpunit.result.cache
error_log
.ftpquota
*.zip
.DS_Store
.idea/
.vscode/
```

Puis :
```bash
git rm -r --cached vendor node_modules .env error_log .ftpquota storage/framework/views Modules/GestionCaisse.zip database/GestionCaisse.zip database/migrations.zip
```

### 4.2 Exemple de correction — routes de module
```php
// Modules/Depenses/Routes/web.php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('depenses')->name('depenses.')->group(function () {
        // ...routes existantes
    });
});
```

### 4.3 Exemple de correction — vente transactionnelle (esquisse)
```php
public function store(StoreVenteRequest $request, StockService $stock)
{
    $lignes = $request->validated()['lignes'];

    return DB::transaction(function () use ($lignes, $request, $stock) {
        $produits = Produit::whereIn('id', array_column($lignes, 'id_produit'))
            ->lockForUpdate()->get()->keyBy('id');

        foreach ($lignes as $l) {
            $stock->assertDisponible($produits[$l['id_produit']], $l['qte']);
        }

        $totalHt = collect($lignes)->sum(fn ($l) => $produits[$l['id_produit']]->prix_unitaire * $l['qte']);
        $tva     = round($totalHt * setting('taux_tva', 0));

        $facture = Facture::create([
            'matricule'        => FactureNumero::next(),
            'id_client'        => $request->id_client,
            'date_facturation' => $request->date,
            'total_ht'         => $totalHt,
            'tva'              => $tva,
            'total_ttc'        => $totalHt + $tva,
        ]);
        // ... vente, lignes, mouvements de stock via $stock->sortie(...)
        return redirect()->route('vente.index')->with('message', 'Vente enregistrée.');
    });
}
```

### 4.4 Affichage sûr des messages flash
```blade
@if (session('message'))
    <script>toastr.success(@json(session('message')), 'Succès');</script>
@endif
```

### 4.5 Checklist de recette (à valider avant mise en production)
- [ ] Un visiteur non connecté est redirigé vers `/login` sur **toutes** les routes métier (test automatisé).
- [ ] `/register`, `/migrate`, `/translations` (hors admin) renvoient 404/403.
- [ ] Un compte « client » ne peut accéder à aucun écran de gestion.
- [ ] Un agent ne voit que : tableau de bord agent, bandes, nutrition, nettoyage, vaccination.
- [ ] Un utilisateur désactivé ne peut pas se connecter.
- [ ] Vente avec un produit en stock insuffisant : aucune facture ni mouvement créé.
- [ ] Modifier un prix dans le navigateur n'a aucun effet sur la facture.
- [ ] Création d'un client depuis l'écran de vente fonctionne.
- [ ] Upload d'un SVG ou d'un fichier renommé en `.png` est refusé.
- [ ] `APP_DEBUG=false` en prod : une erreur affiche la page 500 générique.
- [ ] Sauvegarde BDD du jour présente hors serveur.
- [ ] CI verte (pint + phpunit + build).

---

*Questions ouvertes à clarifier avec le propriétaire du produit avant la phase 4 :*
1. Les agents utilisent-ils l'application sur téléphone ? Avec quelle connectivité ?
2. La licence Vuexy a-t-elle été achetée (et pour quel usage) ?
3. L'entreprise est-elle assujettie à la TVA (taux à appliquer sur les factures) ?
4. Le module `GestionCaisse` est-il utilisé en production ou peut-il être retiré ?
5. Les modules `Depenses`/`Salarie` remplacent-ils les contrôleurs équivalents de `app/` ?
