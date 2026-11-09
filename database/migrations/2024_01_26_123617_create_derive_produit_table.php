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
        Schema::create('derive_produit', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('nom', 255);
            $table->bigInteger('id_produit')->constrained('produits');
            $table->integer('min')->default(0);
            $table->float('prix_unitaire')->nullable();
            $table->integer('max')->default(0);
            $table->foreignId('id_unite')->constrained('unite');
            $table->boolean('statut')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('derive_produit');
    }
};
