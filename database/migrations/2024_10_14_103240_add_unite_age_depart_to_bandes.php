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
        Schema::table('bandes', function (Blueprint $table) {
             $table->enum('unite_age_depart', ['jour', 'semaine', 'mois','annee'])->default('jour'); // spécifie une autre colonne si nécessaire
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bandes', function (Blueprint $table) {
             $table->dropColumn('unite_age_depart'); // Suppression de la colonne si rollback
        });
    }
};
