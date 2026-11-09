<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLigneFacturesTable extends Migration {

	public function up()
	{
		Schema::create('ligne_factures', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('id_facture')->unsigned();
			$table->integer('id_produit')->unsigned();
			$table->enum('type_vente', array('anamal', 'produit'))->nullable();
			$table->integer('Id_stock_produit')->unsigned()->nullable();
			$table->integer('id_bande')->unsigned()->nullable();
			$table->float('qte');
			$table->float('prix_unitaire')->nullable();
			$table->float('total_ligne');
		});
	}

	public function down()
	{
		Schema::drop('ligne_factures');
	}
}