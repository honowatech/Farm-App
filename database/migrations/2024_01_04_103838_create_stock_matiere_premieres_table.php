<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockMatierePremieresTable extends Migration
{
    public function up()
    {
        Schema::create('stock_matiere_premieres', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('id_matiere_premiere')->unsigned();
            $table->enum('type_mouvement', ['approvisionnement', 'destockage']);
            $table->float('quantite');
            $table->float('dernier_stock')->nullable();
            $table->string('nouveau_stock');
            $table->integer('date_entree')->nullable();
            $table->datetime('date_sortie')->nullable();
            $table->text('motif')->nullable();
        });
    }

    public function down()
    {
        Schema::drop('stock_matiere_premieres');
    }
}
