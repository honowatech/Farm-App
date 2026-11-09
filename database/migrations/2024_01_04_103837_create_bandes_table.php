<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBandesTable extends Migration {

	public function up()
	{
		Schema::create('bandes', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('nom', 255)->unique()->nullable();
			$table->string('date_fin_bande')->nullable();
			$table->datetime('date_lancement_bande');
			$table->integer('nbre_sujet')->default('0');
			$table->string('age_depart');
			$table->enum('statut_bande', array('encours', 'terminee'));
			$table->integer('id_type_animal')->unsigned();
			$table->integer('id_batiment')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('bandes');
	}
}