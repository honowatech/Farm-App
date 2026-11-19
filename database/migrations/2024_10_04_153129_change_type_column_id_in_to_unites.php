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
        // La colonne `id` est déjà `bigint unsigned auto_increment` via
        // `$table->id()`. L'ancienne conversion en `unsignedBigInteger`
        // retirait l'auto-incrément : on n'applique aucun changement.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Aucune opération (voir up).
    }
};
