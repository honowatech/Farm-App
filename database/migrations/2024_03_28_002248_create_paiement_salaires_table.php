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
        Schema::create('paiement_salaires', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->enum('type', ['avance', 'paiement_complet']);
            $table->datetime('date_paiement');
            $table->string('telephone', 255)->nullable();
            $table->bigInteger('id_salarie')->constrained('salaries');
            $table->datetime('date_debut');
            $table->datetime('date_fin');
            $table->float('montant')->nullable();
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiement_salaires');
    }
};
