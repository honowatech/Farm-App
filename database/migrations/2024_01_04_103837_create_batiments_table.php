<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBatimentsTable extends Migration {

	public function up()
	{
		Schema::create('batiments', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('nom', 255);
			$table->string('description', 255)->nullable();
		});
	}

	public function down()
	{
		Schema::drop('batiments');
	}
}