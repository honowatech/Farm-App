<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnimalsTable extends Migration {

	public function up()
	{
		Schema::create('animals', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('id_type_animal')->unsigned();
			$table->integer('id_bande')->unsigned();
			$table->integer('age')->nullable();
			$table->float('poids')->nullable();
			$table->float('prix_dachat')->nullable();
			$table->float('prix_de_vente')->nullable();
			$table->enum('statut_animal', array('enelevage', 'mort', 'vendu'));
			$table->string('numero_animal', 255)->unique();
		});
	}

	public function down()
	{
		Schema::drop('animals');
	}
}