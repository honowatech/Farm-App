<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateComptesTable extends Migration {

	public function up()
	{
		Schema::create('gestion_caisse_comptes', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('nom', 255);
			$table->enum('type_account', array('caisse', 'banque'));
			$table->string('nom_banque', 255)->nullable();
			$table->integer('initial_balance')->unsigned();
			$table->integer('current_balance')->nullable();
			$table->integer('currency_id')->unsigned();
			$table->foreign('currency_id')->references('id')->on('gestion_caisse_devises')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
	}

	public function down()
	{
		Schema::drop('gestion_caisse_Comptes');
	}
}
