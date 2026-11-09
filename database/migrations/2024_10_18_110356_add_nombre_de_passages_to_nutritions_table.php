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
            $table->integer('nombre_de_passages')->default(0); // Remplacez 'colonne_existante' par la colonne après laquelle vous souhaitez ajouter celle-ci.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nutritions', function (Blueprint $table) {
            $table->dropColumn('nombre_passage');
        });
    }
};
