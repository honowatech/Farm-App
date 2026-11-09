<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajoute des index sur les colonnes fréquemment utilisées en recherche/filtre.
     */
    public function up(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->index('date_vente', 'ventes_date_vente_index');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->index('nom', 'clients_nom_index');
            $table->index('email', 'clients_email_index');
        });

        Schema::table('produits', function (Blueprint $table) {
            $table->index('nom', 'produits_nom_index');
        });

        Schema::table('nutritions', function (Blueprint $table) {
            $table->index('id_bande', 'nutritions_id_bande_index');
            $table->index('date', 'nutritions_date_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->dropIndex('ventes_date_vente_index');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex('clients_nom_index');
            $table->dropIndex('clients_email_index');
        });

        Schema::table('produits', function (Blueprint $table) {
            $table->dropIndex('produits_nom_index');
        });

        Schema::table('nutritions', function (Blueprint $table) {
            $table->dropIndex('nutritions_id_bande_index');
            $table->dropIndex('nutritions_date_index');
        });
    }
};
