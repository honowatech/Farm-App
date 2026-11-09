<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Veterinaire;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seance_de_vaccination', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('id_veterinaire');
            $table->string('vaccin_attribuer');
            $table->boolean('verification');
            $table->text('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seance_de_vaccination');
    }
};
