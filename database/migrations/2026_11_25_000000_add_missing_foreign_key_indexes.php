<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Index sur les colonnes de relation qui n'ont pas de contrainte de clé
     * étrangère (et donc pas d'index implicite). Elles sont utilisées par
     * l'eager loading et les agrégations de stock.
     */
    public function up(): void
    {
        if (
            Schema::hasTable('ventes')
            && Schema::hasColumn('ventes', 'id_facture')
            && ! Schema::hasIndex('ventes', 'ventes_id_facture_index')
        ) {
            Schema::table('ventes', function (Blueprint $table) {
                $table->index('id_facture', 'ventes_id_facture_index');
            });
        }

        if (
            Schema::hasTable('stock_produits')
            && Schema::hasColumn('stock_produits', 'id_derive_produit')
            && ! Schema::hasIndex('stock_produits', 'stock_produits_id_derive_produit_index')
        ) {
            Schema::table('stock_produits', function (Blueprint $table) {
                $table->index('id_derive_produit', 'stock_produits_id_derive_produit_index');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ventes') && Schema::hasIndex('ventes', 'ventes_id_facture_index')) {
            Schema::table('ventes', function (Blueprint $table) {
                $table->dropIndex('ventes_id_facture_index');
            });
        }

        if (Schema::hasTable('stock_produits') && Schema::hasIndex('stock_produits', 'stock_produits_id_derive_produit_index')) {
            Schema::table('stock_produits', function (Blueprint $table) {
                $table->dropIndex('stock_produits_id_derive_produit_index');
            });
        }
    }
};
