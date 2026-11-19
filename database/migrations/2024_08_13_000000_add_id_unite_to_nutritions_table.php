<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute la colonne `id_unite` manquante à la table `nutritions`.
 *
 * La colonne est référencée par des migrations ultérieures et les seeders,
 * mais n'était jamais créée. Migration corrective non destructrice.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('nutritions', 'id_unite')) {
            Schema::table('nutritions', function (Blueprint $table) {
                $table->unsignedBigInteger('id_unite')->nullable()->after('id_aliment');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('nutritions', 'id_unite')) {
            Schema::table('nutritions', function (Blueprint $table) {
                $table->dropColumn('id_unite');
            });
        }
    }
};
