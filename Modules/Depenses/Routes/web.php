<?php

use Modules\Depenses\Http\Controllers\CategorieController;
use Modules\Depenses\Http\Controllers\DepensesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'agent_access'])->group(function () {
    Route::prefix('depenses')->group(function () {
        Route::get('/', [DepensesController::class, 'index'])->name('depenses.index');
        Route::get('/create', [DepensesController::class, 'create'])->name('depenses.create');
        Route::get('/infos/{id}', [DepensesController::class, 'show'])->name('depenses.show');
        Route::get('/edit/{id}', [DepensesController::class, 'edit'])->name('depenses.edit');
        Route::patch('/update/{id}', [DepensesController::class, 'update'])->name('depenses.update');
        Route::delete('/delete/{id}', [DepensesController::class, 'destroy'])->name('depenses.destroy');
        Route::post('/store', [DepensesController::class, 'store'])->name('depenses.store');
        Route::get('/search-depenses', [DepensesController::class, 'search'])->name('depense.search');
        Route::get('/get-prix-unitaire/{id}', [DepensesController::class, 'getPrixUnitaire'])->name('depenses.getPrixUnitaire');
    });

    Route::prefix('categorie')->group(function () {
        Route::get('/', [CategorieController::class, 'index'])->name('categorie.index');
        Route::get('/create', [CategorieController::class, 'create'])->name('categorie.create');
        Route::get('/edit/{id}', [CategorieController::class, 'edit'])->name('categorie.edit');
        Route::patch('/update/{id}', [CategorieController::class, 'update'])->name('categorie.update');
        Route::get('/infos/{id}', [CategorieController::class, 'show'])->name('categorie.show');
        Route::delete('/delete/{id}', [CategorieController::class, 'destroy'])->name('categorie.destroy');
        Route::post('/store', [CategorieController::class, 'store'])->name('categorie.store');
    });
});
