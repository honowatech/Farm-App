<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('bandes', function (Blueprint $table) {
            $table->foreign('id_type_animal')->references('id')->on('type_animals')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
        Schema::table('bandes', function (Blueprint $table) {
            $table->foreign('id_batiment')->references('id')->on('batiments')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
        Schema::table('animals', function (Blueprint $table) {
            $table->foreign('id_type_animal')->references('id')->on('type_animals')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
        Schema::table('animals', function (Blueprint $table) {
            $table->foreign('id_bande')->references('id')->on('bandes')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
        Schema::table('produits', function (Blueprint $table) {
            $table->foreign('id_type_animal')->references('id')->on('type_animals')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->foreign('id_user')->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
        Schema::table('stock_matiere_premieres', function (Blueprint $table) {
            $table->foreign('id_matiere_premiere')->references('id')->on('matiere_premieres')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
        Schema::table('achats', function (Blueprint $table) {
            $table->foreign('id_stock_matiere_premiere')->references('id')->on('stock_matiere_premieres')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
        Schema::table('achats', function (Blueprint $table) {
            $table->foreign('id_matiere_premiere')->references('id')->on('matiere_premieres')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
        Schema::table('achats', function (Blueprint $table) {
            $table->foreign('id_fournisseur')->references('id')->on('fournisseurs')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
        Schema::table('stock_produits', function (Blueprint $table) {
            $table->foreign('id_produit')->references('id')->on('produits')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
        Schema::table('ventes', function (Blueprint $table) {
            $table->foreign('id_saver')->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
        Schema::table('ligne_factures', function (Blueprint $table) {
            $table->foreign('id_facture')->references('id')->on('factures')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
        Schema::table('ligne_factures', function (Blueprint $table) {
            $table->foreign('id_produit')->references('id')->on('produits')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
        Schema::table('ligne_factures', function (Blueprint $table) {
            $table->foreign('Id_stock_produit')->references('id')->on('stock_produits')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
        Schema::table('ligne_factures', function (Blueprint $table) {
            $table->foreign('id_bande')->references('id')->on('bandes')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
        Schema::table('autre_depenses', function (Blueprint $table) {
            $table->foreign('id_saver')->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }

    public function down()
    {
        Schema::table('bandes', function (Blueprint $table) {
            $table->dropForeign('bandes_id_type_animal_foreign');
        });
        Schema::table('bandes', function (Blueprint $table) {
            $table->dropForeign('bandes_id_batiment_foreign');
        });
        Schema::table('animals', function (Blueprint $table) {
            $table->dropForeign('animals_id_type_animal_foreign');
        });
        Schema::table('animals', function (Blueprint $table) {
            $table->dropForeign('animals_id_bande_foreign');
        });
        Schema::table('produits', function (Blueprint $table) {
            $table->dropForeign('produits_id_type_animal_foreign');
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign('clients_id_user_foreign');
        });
        Schema::table('stock_matiere_premieres', function (Blueprint $table) {
            $table->dropForeign('stock_matiere_premieres_id_matiere_premiere_foreign');
        });
        Schema::table('achats', function (Blueprint $table) {
            $table->dropForeign('achats_id_stock_matiere_premiere_foreign');
        });
        Schema::table('achats', function (Blueprint $table) {
            $table->dropForeign('achats_id_matiere_premiere_foreign');
        });
        Schema::table('achats', function (Blueprint $table) {
            $table->dropForeign('achats_id_fournisseur_foreign');
        });
        Schema::table('stock_produits', function (Blueprint $table) {
            $table->dropForeign('stock_produits_id_produit_foreign');
        });
        Schema::table('ventes', function (Blueprint $table) {
            $table->dropForeign('ventes_id_saver_foreign');
        });
        Schema::table('ligne_factures', function (Blueprint $table) {
            $table->dropForeign('ligne_factures_id_facture_foreign');
        });
        Schema::table('ligne_factures', function (Blueprint $table) {
            $table->dropForeign('ligne_factures_id_produit_foreign');
        });
        Schema::table('ligne_factures', function (Blueprint $table) {
            $table->dropForeign('ligne_factures_Id_stock_produit_foreign');
        });
        Schema::table('ligne_factures', function (Blueprint $table) {
            $table->dropForeign('ligne_factures_id_bande_foreign');
        });
        Schema::table('autre_depenses', function (Blueprint $table) {
            $table->dropForeign('autre_depenses_id_saver_foreign');
        });
    }
}
