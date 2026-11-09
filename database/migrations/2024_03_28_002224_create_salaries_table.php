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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('nom', 255)->nullable();
            $table->string('adresse', 255)->nullable();
            $table->string('telephone', 255)->nullable();
            $table->string('cni', 255)->nullable();
            $table->string('telephone_secondaire', 255)->nullable();
            $table->bigInteger('id_saver')->constrained('users');
            $table->integer('accumulateur')->unsigned()->default('1');
            $table->enum('type_periode', ['heure', 'jour', 'semaine', 'mois', 'annee']);
            $table->float('salaire')->nullable();
            $table->datetime('date_debut_contrat')->nullable();
            $table->datetime('date_dernier_paiement')->nullable();
            $table->boolean('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
