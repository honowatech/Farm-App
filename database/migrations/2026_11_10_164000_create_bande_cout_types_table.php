<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bande_cout_types', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->boolean('statut')->default(1);
            $table->timestamps();
        });

        DB::table('bande_cout_types')->insert([
            ['libelle' => 'Main d\'œuvre', 'statut' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Électricité', 'statut' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Eau', 'statut' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Autre', 'statut' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('bande_cout_types');
    }
};
