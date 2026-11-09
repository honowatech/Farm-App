<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFacturesTable extends Migration
{
    public function up()
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->datetime('date_facturation');
            $table->float('total_ht');
            $table->float('tva');
            $table->float('total_ttc');
        });
    }

    public function down()
    {
        Schema::drop('factures');
    }
}
