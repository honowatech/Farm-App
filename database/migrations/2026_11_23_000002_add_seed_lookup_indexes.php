<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Index les colonnes utilisées en look-up (firstOrCreate / recherche) par le
 * seed de démonstration et par les écrans de liste. Sans index, chaque look-up
 * balayait toute la table qui grossissait au fil du seed (coût quadratique).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->index('matricule', 'factures_matricule_index');
        });

        Schema::table('depenses', function (Blueprint $table) {
            $table->index('motif_depense', 'depenses_motif_depense_index');
        });

        Schema::table('gestion_caisse_transactions', function (Blueprint $table) {
            $table->index('reference_number', 'gct_reference_number_index');
        });

        Schema::table('stock_produits', function (Blueprint $table) {
            $table->index('id_produit', 'stock_produits_id_produit_index');
            $table->index('type_mouvement', 'stock_produits_type_mouvement_index');
        });

        Schema::table('bandes', function (Blueprint $table) {
            $table->index('nom', 'bandes_nom_index');
        });

        Schema::table('batiments', function (Blueprint $table) {
            $table->index('nom', 'batiments_nom_index');
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->index('nom', 'agents_nom_index');
        });

        Schema::table('veterinaires', function (Blueprint $table) {
            $table->index('nom', 'veterinaires_nom_index');
        });

        Schema::table('fournisseurs', function (Blueprint $table) {
            $table->index('nom', 'fournisseurs_nom_index');
        });
    }

    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropIndex('factures_matricule_index');
        });

        Schema::table('depenses', function (Blueprint $table) {
            $table->dropIndex('depenses_motif_depense_index');
        });

        Schema::table('gestion_caisse_transactions', function (Blueprint $table) {
            $table->dropIndex('gct_reference_number_index');
        });

        Schema::table('stock_produits', function (Blueprint $table) {
            $table->dropIndex('stock_produits_id_produit_index');
            $table->dropIndex('stock_produits_type_mouvement_index');
        });

        Schema::table('bandes', function (Blueprint $table) {
            $table->dropIndex('bandes_nom_index');
        });

        Schema::table('batiments', function (Blueprint $table) {
            $table->dropIndex('batiments_nom_index');
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->dropIndex('agents_nom_index');
        });

        Schema::table('veterinaires', function (Blueprint $table) {
            $table->dropIndex('veterinaires_nom_index');
        });

        Schema::table('fournisseurs', function (Blueprint $table) {
            $table->dropIndex('fournisseurs_nom_index');
        });
    }
};
