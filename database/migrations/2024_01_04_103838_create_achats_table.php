<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAchatsTable extends Migration {

	public function up()
	{
		Schema::create('achats', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('id_stock_matiere_premiere')->unsigned();
			$table->integer('id_matiere_premiere')->unsigned()->nullable();
			$table->float('prix_unitaire')->nullable();
			$table->float('qte')->nullable();
			$table->float('prix_total');
			$table->string('mode_de_paiement', 255)->nullable();
			$table->integer('id_fournisseur')->unsigned()->nullable();
		});
	}

	public function down()
	{
		Schema::drop('achats');
	}
}