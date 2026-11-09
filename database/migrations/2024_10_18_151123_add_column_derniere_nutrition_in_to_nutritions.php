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
        Schema::table('nutritions', function (Blueprint $table) {
            // Ajouter les colonnes si elles n'existent pas déjà
            $table->datetime('derniere_nutrition')->nullable(); // Remplacez 'colonne_precedente' par le nom de la colonne précédente
            $table->integer('compteur_nutrition')->default(0)->after('derniere_nutrition'); // Définit une valeur par défaut
            $table->datetime('prochaine_nutrition')->nullable()->after('compteur_nutrition'); // Rendre nullable si nécessaire
            $table->boolean('statut_nutrition')->default(0)->after('prochaine_nutrition'); // Définit une valeur par défaut
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nutritions', function (Blueprint $table) {
            //
        });
    }
};
