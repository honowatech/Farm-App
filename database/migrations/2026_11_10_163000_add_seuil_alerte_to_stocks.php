<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matiere_premieres', function (Blueprint $table) {
            $table->float('seuil_alerte')->nullable();
        });

        Schema::table('produits', function (Blueprint $table) {
            $table->float('seuil_alerte')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('matiere_premieres', function (Blueprint $table) {
            $table->dropColumn('seuil_alerte');
        });

        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn('seuil_alerte');
        });
    }
};
