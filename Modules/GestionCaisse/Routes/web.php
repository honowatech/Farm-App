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

Route::middleware(['web', 'auth', 'role:admin'])->prefix('gestion-caisse')->group(function () {
    Route::get('/', 'Modules\GestionCaisse\Http\Controllers\CompteController@index');
    Route::resource('compte', 'Modules\GestionCaisse\Http\Controllers\CompteController');
    Route::resource('devise', 'Modules\GestionCaisse\Http\Controllers\DeviseController');
    Route::resource('transfert', 'Modules\GestionCaisse\Http\Controllers\TransfertController');
});
