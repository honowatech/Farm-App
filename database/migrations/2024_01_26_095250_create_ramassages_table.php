<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ramassages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('id_bande')->constrained('bandes');
            $table->string('ids_derive', 255)->nullable();
            $table->string('valeurs_derive', 255)->nullable();
            $table->bigInteger('id_prestataire')->constrained('users');
            $table->bigInteger('id_saver')->constrained('users');
            $table->datetime('to_do_date')->nullable();
            $table->datetime('do_date')->nullable();
            $table->text('commentaire')->nullable();
            $table->boolean('statut')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ramassages');
    }
};
