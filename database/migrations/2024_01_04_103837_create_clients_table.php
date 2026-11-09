<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	public function up()
	{
		Schema::create('clients', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('nom', 255)->nullable();
			$table->string('prenom', 255)->nullable();
			$table->string('telephone', 255)->nullable();
			$table->string('adresse', 255)->nullable();
			$table->string('email', 255)->nullable();
			$table->bigInteger('id_user')->unsigned()->nullable();
		});
	}

	public function down()
	{
		Schema::drop('clients');
	}
}