<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProduitsTable extends Migration {

	public function up()
	{
		Schema::create('produits', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('id_type_animal')->unsigned()->nullable();
			$table->float('prix_unitaire')->nullable();
			$table->string('nom', 255)->unique();
			$table->string('description', 255)->nullable();
			$table->boolean('statut')->default(1);
		});
	}

	public function down()
	{
		Schema::drop('produits');
	}
}