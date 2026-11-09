<?php

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

    Route::resource('/salarie', 'Modules\Salarie\Http\Controllers\SalarieController');
    Route::resource('paiement_salaire', 'Modules\Salarie\Http\Controllers\PaiementSalariesController');
    Route::post('/salarie_ajax', [Modules\Salarie\Http\Controllers\SalarieController::class, 'ajax'])->name('ajax_recap_salarie');
    Route::post('/salarie_ajax_edit', [Modules\Salarie\Http\Controllers\SalarieController::class, 'ajax_edit'])->name('ajax_recap_salarie_edit');
    Route::get('/salarie-search/{id}',[Modules\Salarie\Http\Controllers\SalarieController::class, 'search'])->name('salarie.search');

