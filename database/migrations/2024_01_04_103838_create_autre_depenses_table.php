<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAutreDepensesTable extends Migration
{
    public function up()
    {
        Schema::create('autre_depenses', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->float('montant');
            $table->text('description');
            $table->datetime('date_depense');
            $table->bigInteger('id_saver')->unsigned();
        });
    }

    public function down()
    {
        Schema::drop('autre_depenses');
    }
}
