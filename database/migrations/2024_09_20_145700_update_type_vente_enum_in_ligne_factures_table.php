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
        Schema::table('ligne_factures', function (Blueprint $table) {
                 // Étape 1 : Mettre à jour les anciennes valeurs de la colonne 'type_vente'
            DB::table('ligne_factures')
                ->whereNotIn('type_vente', ['animal', 'oeuf', 'fiante'])
                ->update(['type_vente' => 'animal']); // Remplace les valeurs invalides par 'animal' par exemple

            // Étape 2 : Modifier le champ ENUM
            Schema::table('ligne_factures', function (Blueprint $table) {
                $table->enum('type_vente', ['animal', 'oeuf', 'fiante'])->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ligne_factures', function (Blueprint $table) {
             $table->enum('type_vente', ['animal', 'produit'])
                  ->change();
        });
    }
};
