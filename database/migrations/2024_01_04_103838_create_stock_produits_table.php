<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockProduitsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_produits', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('id_produit')->unsigned();
            $table->enum('type_mouvement', ['approvisionnement', 'destockage'])->nullable();
            $table->float('qte')->nullable();
            $table->float('dernier_stock')->nullable();
            $table->float('nouveau_stock')->nullable();
            $table->datetime('date_entree')->nullable();
            $table->datetime('date_sortie')->nullable();
        });
    }

    public function down()
    {
        Schema::drop('stock_produits');
    }
}
