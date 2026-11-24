<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Le provisioning de la démo génère des références de commande du type
 * `CMD-ANNUAL-{slug}-{mois}`. Le slug de démo (préfixe + 24 caractères
 * aléatoires) dépasse les 40 caractères autorisés, ce qui faisait échouer la
 * création avec « Data too long for column 'matricule' ».
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->string('matricule', 80)->change();
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->string('matricule', 40)->change();
        });
    }
};
