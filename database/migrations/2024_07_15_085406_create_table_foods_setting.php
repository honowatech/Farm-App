<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('foods_setting', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_type_animal');
            $table->string('aliment');
            $table->float('quantite_aliment');
            $table->integer('age_min');
            $table->integer('age_max');
            $table->foreignId('id_unite');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('foods_setting');
    }
};
