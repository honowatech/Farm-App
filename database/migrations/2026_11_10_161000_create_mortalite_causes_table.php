<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mortalite_causes', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->boolean('statut')->default(1);
            $table->timestamps();
        });

        // Causes par défaut (aviculture)
        DB::table('mortalite_causes')->insert([
            ['libelle' => 'Maladie', 'statut' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Picage / cannibalisme', 'statut' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Prédation', 'statut' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Coup de chaleur', 'statut' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Froid', 'statut' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Asphyxie', 'statut' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Accident / blessure', 'statut' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Autre', 'statut' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('mortalite_causes');
    }
};
