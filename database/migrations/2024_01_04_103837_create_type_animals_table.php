<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTypeAnimalsTable extends Migration {

	public function up()
	{
		Schema::create('type_animals', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('nom', 255)->unique();
			$table->string('description', 255)->nullable();
		});
	}

	public function down()
	{
		Schema::drop('type_animals');
	}
}