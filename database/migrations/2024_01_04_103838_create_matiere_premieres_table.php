<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMatierePremieresTable extends Migration {

	public function up()
	{
		Schema::create('matiere_premieres', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('nom_produit', 255);
			$table->float('prix_unitaire');
			$table->float('qte');
			$table->string('description', 255)->nullable();
		});
	}

	public function down()
	{
		Schema::drop('matiere_premieres');
	}
}