<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdCompteInToDepenseDepensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Le module Depenses présente un décalage de nommage de tables
        // (entités `depense_depenses` vs migration `depenses`). On rend la
        // migration non-destructive : on n'ajoute la colonne que si les deux
        // tables attendues existent réellement.
        if (! Schema::hasTable('depense_depenses') || ! Schema::hasTable('gestion_caisse_comptes')) {
            return;
        }

        Schema::table('depense_depenses', function (Blueprint $table) {
            $table->unsignedInteger('id_compte')->nullable()->after('id');
            $table->foreign('id_compte')->references('id')->on('gestion_caisse_comptes')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasTable('depense_depenses') || ! Schema::hasColumn('depense_depenses', 'id_compte')) {
            return;
        }

        Schema::table('depense_depenses', function (Blueprint $table) {
            $table->dropForeign(['id_compte']);
            $table->dropColumn('id_compte');
        });
    }
}
