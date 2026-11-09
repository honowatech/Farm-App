<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVentesTable extends Migration
{
    public function up()
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('id_facture')->nullable();
            $table->bigInteger('id_saver')->unsigned();
            $table->string('date_vente')->nullable();
        });
    }

    public function down()
    {
        Schema::drop('ventes');
    }
}
