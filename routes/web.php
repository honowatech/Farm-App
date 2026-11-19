<?php

use App\Http\Controllers\AchatController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AlerteController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BandeController;
use App\Http\Controllers\BatimentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Derive_produitController;
use App\Http\Controllers\Detail_fournisseurController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\Foods_settingController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Ligne_factureController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\Matiere_premiereController;
use App\Http\Controllers\MortaliteController;
use App\Http\Controllers\NutritionController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ProgrammeVaccinationController;
use App\Http\Controllers\RamassageController;
use App\Http\Controllers\RentabiliteController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Stock_matiere_premiereController;
use App\Http\Controllers\Stock_produitController;
use App\Http\Controllers\Type_animalController;
use App\Http\Controllers\UniteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\VeterinaireController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['auth', 'web', 'feature']], function () {
    // Language
    Route::post('lang/{locale}', [LanguageController::class, 'switchLang'])->name('lang.switch');

    // Home
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/home', [HomeController::class, 'index']);

    // Resources
    Route::middleware('agent_access')->group(function () {
        Route::resources([
            'user' => UserController::class,
            'batiment' => BatimentController::class,
            'type_animal' => Type_animalController::class,
            'animal' => AnimalController::class,
            'produit' => ProduitController::class,
            'client' => ClientController::class,
            'matiere_premiere' => Matiere_premiereController::class,
            'fournisseur' => FournisseurController::class,
            'detail_fournisseur' => Detail_fournisseurController::class,
            'stock_matiere_premiere' => Stock_matiere_premiereController::class,
            'achat' => AchatController::class,
            'stock_produit' => Stock_produitController::class,
            'vente' => VenteController::class,
            'facture' => FactureController::class,
            'ligne_facture' => Ligne_factureController::class,
            'unite' => UniteController::class,
            'derive_produit' => Derive_produitController::class,
            'ramassage' => RamassageController::class,
            'setting' => SettingController::class,
            'logo' => LogoController::class,
            'veterinaire' => VeterinaireController::class,
            'agent' => AgentController::class,
            'mortalite' => MortaliteController::class,
        ]);
    });

    // Business routes (admin + agent selon whitelist)
    Route::middleware('agent_access')->group(function () {
        // Bande (route spécifique ; les autres bandes sont dans le groupe dédié plus bas)
        Route::patch('/terminer-bande/{id}', [BandeController::class, 'teminer_bande'])->name('terminer_bande');

        // Nutrition (routes spécifiques ; les autres sont dans le groupe dédié plus bas)
        Route::get('/api/nutrition/{bande}/calendrier', [NutritionController::class, 'getNutritionCalendar'])->name('nutrition.calendar.data');
        Route::get('/nutrition/calendar/{bande}', [NutritionController::class, 'showCalendar'])->name('nutrition.calendar');

        // Achat
        Route::get('/search-achat', [AchatController::class, 'search'])->name('achat.search');
        Route::get('/achat_ajax', [AchatController::class, 'ajax'])->name('ajax_result_fournisseur');

        // Vente
        Route::get('/vente-search', [VenteController::class, 'search'])->name('vente.search');
        Route::get('/vente-export', [VenteController::class, 'export'])->name('vente.export');
        Route::get('/vente-export-pdf', [VenteController::class, 'exportPdf'])->name('vente.export.pdf');
        Route::get('/facture-pdf/{id}', [VenteController::class, 'downloadPdf'])->name('vente.facture.pdf');

        // Exports CSV / PDF
        Route::get('/achat-export', [AchatController::class, 'export'])->name('achat.export');
        Route::get('/achat-export-pdf', [AchatController::class, 'exportPdf'])->name('achat.export.pdf');
        Route::get('/client-export', [ClientController::class, 'export'])->name('client.export');
        Route::get('/client-export-pdf', [ClientController::class, 'exportPdf'])->name('client.export.pdf');
        Route::get('/fournisseur-export', [FournisseurController::class, 'export'])->name('fournisseur.export');
        Route::get('/fournisseur-export-pdf', [FournisseurController::class, 'exportPdf'])->name('fournisseur.export.pdf');
        Route::get('/produit-export', [ProduitController::class, 'export'])->name('produit.export');
        Route::get('/produit-export-pdf', [ProduitController::class, 'exportPdf'])->name('produit.export.pdf');

        // Journal d'audit
        Route::get('/audit', [AuditLogController::class, 'index'])->name('audit.index');

        // Alertes
        Route::get('/alertes', [AlerteController::class, 'index'])->name('alerte.index');

        // Rentabilité
        Route::get('/rentabilite', [RentabiliteController::class, 'index'])->name('rentabilite.index');

        Route::get('/ajout de client', [VenteController::class, 'get_formulaire'])->name('formulaire');
        Route::get('/selection-client', [VenteController::class, 'get_client'])->name('client');
        Route::get('/achat_ajax_result', [VenteController::class, 'get_prix'])->name('ajax_result_prix');
        Route::post('/save', [VenteController::class, 'save_client'])->name('saving');
        Route::get('/detail-vente/{id}', [VenteController::class, 'get_facture'])->name('facturation');
        Route::get('/choix-annimal', [VenteController::class, 'get_animal'])->name('get_animal');
        Route::get('/fiente', [VenteController::class, 'get_fiente'])->name('get_fiente');
        Route::get('/choix-annimal_pondeur', [VenteController::class, 'get_animal_pondeur'])->name('get_animal_pondeur');

        // Ramassage
        Route::get('/search-ramassage', [RamassageController::class, 'search'])->name('ramassage.search');
        Route::get('/ramassage_ajax', [RamassageController::class, 'ajax'])->name('ajax_result_derive_ramassage');
        Route::get('/ramassage_ajax2', [RamassageController::class, 'ajax2'])->name('ajax_result_produit');
        Route::get('/ramassage_ajax3', [RamassageController::class, 'ajax3'])->name('ajax_number_ramassage');

        // Settings
        Route::group(['prefix' => 'settings'], function () {
            Route::get('/foods-setting/{id_type_animal}/edit/{id_aliment}', [Foods_settingController::class, 'edit'])->name('foods-setting.edit');
            Route::put('/foods-setting/{id_type_animal}/{id_aliment}', [Foods_settingController::class, 'update'])->name('foods-setting.update');
            Route::get('/foods-setting/{id_type_animal}/get-foods', [Foods_settingController::class, 'getFoodsForAnimalType'])->name('foods-setting.get-foods');
            Route::resource('foods-setting', Foods_settingController::class)->except(['edit', 'show', 'update']);
            Route::get('/foods-setting/{foods_setting}', [Foods_settingController::class, 'show'])->name('foods-setting.show');
        });
    });
});

Route::group(['middleware' => ['auth', 'web', 'agent_access']], function () {
    Route::resource('nutrition', 'App\Http\Controllers\NutritionController');
    Route::get('/check', [NutritionController::class, 'checkNutrition'])->name('nutrition.check');
    Route::get('/search-nutruition', [NutritionController::class, 'search'])->name('nutrition.search');
    Route::get('/get-form', [NutritionController::class, 'take_form'])->name('load_form');

    Route::resource('bande', 'App\Http\Controllers\BandeController');
    Route::get('/gestion-bande/{id}', [BandeController::class, 'gestion_bande'])->name('gestion_bande');
    Route::get('/gestion-bande-get-form/{id}', [BandeController::class, 'gestion_bande_get_form'])->name('get-form-bande');
    Route::get('/recherche-bande', [BandeController::class, 'search'])->name('bande.search');

    Route::resource('nettoyage', 'App\Http\Controllers\NettoyageController');
    Route::get('/programme-vaccination', [ProgrammeVaccinationController::class, 'index'])->name('programme_vaccination.index');
    Route::resource('seanceVaccination', 'App\Http\Controllers\SeanceVaccinationController');
});

// Espace central du super administrateur (propriétaire de la plateforme).
// Gère les entreprises, les forfaits et la plateforme, hors contexte tenant.
Route::middleware(['auth', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/tenants', [\App\Http\Controllers\Admin\TenantController::class, 'index'])->name('tenants.index');
    Route::get('/tenants/create', [\App\Http\Controllers\Admin\TenantController::class, 'create'])->name('tenants.create');
    Route::post('/tenants', [\App\Http\Controllers\Admin\TenantController::class, 'store'])->name('tenants.store');
    Route::patch('/tenants/{tenant}/toggle', [\App\Http\Controllers\Admin\TenantController::class, 'toggleStatus'])->name('tenants.toggle');

    Route::get('/plans', [\App\Http\Controllers\Admin\PlanController::class, 'index'])->name('plans.index');
    Route::get('/plans/create', [\App\Http\Controllers\Admin\PlanController::class, 'create'])->name('plans.create');
    Route::post('/plans', [\App\Http\Controllers\Admin\PlanController::class, 'store'])->name('plans.store');
    Route::get('/plans/{plan}/edit', [\App\Http\Controllers\Admin\PlanController::class, 'edit'])->name('plans.edit');
    Route::put('/plans/{plan}', [\App\Http\Controllers\Admin\PlanController::class, 'update'])->name('plans.update');

    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/ajax', [\App\Http\Controllers\Admin\UserController::class, 'ajax'])->name('users.ajax');
    Route::patch('/users/{user}/toggle', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle');

    Route::get('/roles', [\App\Http\Controllers\Admin\RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles', [\App\Http\Controllers\Admin\RoleController::class, 'store'])->name('roles.store');
    Route::post('/roles/{typeUser}/toggle-permission', [\App\Http\Controllers\Admin\RoleController::class, 'togglePermission'])->name('roles.toggle');
    Route::delete('/roles/{typeUser}', [\App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('roles.destroy');

    Route::get('/subscriptions', [\App\Http\Controllers\Admin\SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions/{subscription}/status', [\App\Http\Controllers\Admin\SubscriptionController::class, 'changeStatus'])->name('subscriptions.status');
    Route::post('/subscriptions/{subscription}/extend', [\App\Http\Controllers\Admin\SubscriptionController::class, 'extend'])->name('subscriptions.extend');
    Route::post('/subscriptions/{subscription}/plan', [\App\Http\Controllers\Admin\SubscriptionController::class, 'changePlan'])->name('subscriptions.plan');

    // Paiements MaMoNi (super admin)
    Route::get('/paiement', [\App\Http\Controllers\PaiementController::class, 'index'])->name('paiement.index');
    Route::get('/paiement/test', [\App\Http\Controllers\PaiementController::class, 'test'])->name('paiement.test');
    Route::post('/paiement/initier', [\App\Http\Controllers\PaiementController::class, 'initiate'])->name('paiement.initiate');
    Route::post('/paiement/statut/{transaction}', [\App\Http\Controllers\PaiementController::class, 'status'])->name('paiement.status');
    Route::get('/paiement/settings', [\App\Http\Controllers\PaiementController::class, 'settings'])->name('paiement.settings');
    Route::post('/paiement/settings', [\App\Http\Controllers\PaiementController::class, 'updateSettings'])->name('paiement.settings.update');
    Route::post('/paiement/settings/test-connexion', [\App\Http\Controllers\PaiementController::class, 'testConnection'])->name('paiement.settings.test');

    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});

// Espace client (portail B2B/B2C) : catalogue, commandes, factures, profil.
Route::middleware(['auth', 'role:client'])->prefix('espace-client')->name('client.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ClientPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/catalogue', [\App\Http\Controllers\ClientPortalController::class, 'catalogue'])->name('catalogue');
    Route::get('/commander', [\App\Http\Controllers\ClientPortalController::class, 'commandeCreate'])->name('commande.create');
    Route::post('/commander', [\App\Http\Controllers\ClientPortalController::class, 'commandeStore'])->name('commande.store');
    Route::post('/panier/ajouter', [\App\Http\Controllers\ClientPortalController::class, 'addToCart'])->name('panier.add');
    Route::post('/panier/mettre-a-jour', [\App\Http\Controllers\ClientPortalController::class, 'updateCart'])->name('panier.update');
    Route::post('/panier/retirer', [\App\Http\Controllers\ClientPortalController::class, 'removeFromCart'])->name('panier.remove');
    Route::get('/commandes', [\App\Http\Controllers\ClientPortalController::class, 'commandes'])->name('commandes.index');
    Route::get('/commandes/{commande}', [\App\Http\Controllers\ClientPortalController::class, 'commandeShow'])->name('commandes.show');
    Route::get('/factures', [\App\Http\Controllers\ClientPortalController::class, 'factures'])->name('factures.index');
    Route::get('/factures/{facture}/pdf', [\App\Http\Controllers\ClientPortalController::class, 'factureDownload'])->name('factures.pdf');
    Route::get('/factures/{facture}', [\App\Http\Controllers\ClientPortalController::class, 'factureShow'])->name('factures.show');
    Route::get('/profil', [\App\Http\Controllers\ClientPortalController::class, 'profil'])->name('profil');
    Route::post('/profil', [\App\Http\Controllers\ClientPortalController::class, 'profilUpdate'])->name('profil.update');
});

Auth::routes(['register' => false]);

// Inscription multi-tenant (utilisateur + entreprise) et accès démo.
Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register')->middleware('guest');
Route::post('/register/pay/init', [\App\Http\Controllers\Auth\RegisterController::class, 'initiatePayment'])->name('register.pay.init')->middleware('guest');
Route::post('/register/pay/status/{transaction}', [\App\Http\Controllers\Auth\RegisterController::class, 'paymentStatus'])->name('register.pay.status')->middleware('guest');
Route::get('/demo/login', [\App\Http\Controllers\Auth\DemoController::class, 'login'])->name('demo.login')->middleware('guest');
