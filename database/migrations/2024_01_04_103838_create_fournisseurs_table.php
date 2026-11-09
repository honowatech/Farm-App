<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFournisseursTable extends Migration {

	public function up()
	{
		Schema::create('fournisseurs', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('nom', 255)->nullable();
			$table->string('adresse', 255)->nullable();
			$table->string('telephone', 255)->nullable();
			$table->string('telephone_secondaire', 255)->nullable();
			$table->boolean('statut');
		});
	}

	public function down()
	{
		Schema::drop('fournisseurs');
	}
}