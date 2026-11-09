<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDevisesTable extends Migration {

	public function up()
	{
		Schema::create('gestion_caisse_devises', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('code', 255);
			$table->string('symbole', 255);
			$table->string('nom', 255);
		});
	}

	public function down()
	{
		Schema::drop('devises');
	}
}