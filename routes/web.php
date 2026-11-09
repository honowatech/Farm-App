<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BatimentController;
use App\Http\Controllers\Type_animalController;
use App\Http\Controllers\BandeController;
use App\Http\Controllers\NutritionController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Matiere_premiereController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\Detail_fournisseurController;
use App\Http\Controllers\Stock_matiere_premiereController;
use App\Http\Controllers\AchatController;
use App\Http\Controllers\Stock_produitController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\Ligne_factureController;
use App\Http\Controllers\UniteController;
use App\Http\Controllers\Derive_produitController;
use App\Http\Controllers\RamassageController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\Foods_settingController;
use App\Http\Controllers\SeanceVaccinationController;
use App\Http\Controllers\VeterinaireController;
use App\Http\Controllers\NettoyageController;
use App\Http\Controllers\AgentController;

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

Route::group(['middleware' => ['auth', 'web',]], function() {
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
    ]);
});

    // Business routes (admin + agent selon whitelist)
    Route::middleware('agent_access')->group(function () {
    // Bande
    Route::get('/gestion-bande/{id}', [BandeController::class, 'gestion_bande'])->name('gestion_bande');
    Route::get('/gestion-bande-get-form/{id}', [BandeController::class, 'gestion_bande_get_form'])->name('get-form-bande');
    Route::patch('/terminer-bande/{id}', [BandeController::class, 'teminer_bande'])->name('terminer_bande');
    Route::get('/recherche-bande', [BandeController::class, 'search'])->name('bande.search');

    // Nutrition
    Route::get('/api/nutrition/{bande}/calendrier', [NutritionController::class, 'getNutritionCalendar'])->name('nutrition.calendar.data');
    Route::get('/nutrition/calendar/{bande}', [NutritionController::class, 'showCalendar'])->name('nutrition.calendar');
    Route::get('/check', [NutritionController::class, 'checkNutrition'])->name('nutrition.check');
    Route::get('/search-nutruition', [NutritionController::class, 'search'])->name('nutrition.search');
    Route::get('/get-form', [NutritionController::class, 'take_form'])->name('load_form');

    // Achat
    Route::get('/search-achat', [AchatController::class, 'search'])->name('achat.search');
    Route::get('/achat_ajax', [AchatController::class, 'ajax'])->name('ajax_result_fournisseur');

    // Vente
    Route::get('/vente-search', [VenteController::class, 'search'])->name('vente.search');
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
    Route::group(['prefix' => 'settings'], function() {
        Route::get('/foods-setting/{id_type_animal}/edit/{id_aliment}', [Foods_settingController::class, 'edit'])->name('foods-setting.edit');
        Route::put('/foods-setting/{id_type_animal}/{id_aliment}', [Foods_settingController::class, 'update'])->name('foods-setting.update');
        Route::get('/foods-setting/{id_type_animal}/get-foods', [Foods_settingController::class, 'getFoodsForAnimalType'])->name('foods-setting.get-foods');
        Route::resource('foods-setting', Foods_settingController::class)->except(['edit', 'show','update']);
        Route::get('/foods-setting/{foods_setting}', [Foods_settingController::class, 'show'])->name('foods-setting.show');
    });
    });
});

Route::group(['middleware' => ['auth', 'web', 'agent_access']], function() {
    Route::resource('nutrition','App\Http\Controllers\NutritionController');
    Route::get('/check', [App\Http\Controllers\NutritionController::class, 'checkNutrition'])->name('nutrition.check');
    Route::get('/search-nutruition',[App\Http\Controllers\NutritionController::class, 'search'])->name('nutrition.search');
    Route::get('/get-form',[App\Http\Controllers\NutritionController::class, 'take_form'])->name('load_form');

    Route::resource('bande', 'App\Http\Controllers\BandeController');
    Route::get('/gestion-bande/{id}', [App\Http\Controllers\BandeController::class, 'gestion_bande'])->name('gestion_bande');
    Route::get('/gestion-bande-get-form/{id}', [App\Http\Controllers\BandeController::class, 'gestion_bande_get_form'])->name('get-form-bande');
    Route::get('/recherche-bande', [App\Http\Controllers\BandeController::class, 'search'])->name('bande.search');

    Route::resource('nettoyage', 'App\Http\Controllers\NettoyageController');
    Route::resource('seanceVaccination', 'App\Http\Controllers\SeanceVaccinationController');
});

Auth::routes(['register' => false]);
