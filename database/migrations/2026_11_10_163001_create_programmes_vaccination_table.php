<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programmes_vaccination', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_type_animal');
            $table->foreign('id_type_animal')->references('id')->on('type_animals')->onDelete('cascade');
            $table->integer('age_jour');
            $table->string('nom_vaccin');
            $table->integer('rappel_jours')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programmes_vaccination');
    }
};
