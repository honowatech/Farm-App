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
        Schema::table('stock_matiere_premieres', function (Blueprint $table) {
            $table->string('unite_principale')->default('kg');  // Ex : 'kg' ou 'sac'
            $table->decimal('conversion_kg', 8, 2)->default(1); // Conversion vers kg
            $table->decimal('conversion_gr', 8, 2)->default(1000); // Conversion vers grammes
            $table->decimal('conversion_sac', 8, 2)->nullable(); // Ex : quantité pour un sac
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_matiere_premieres', function (Blueprint $table) {
            $table->dropColumn(['unite_principale', 'conversion_kg', 'conversion_gr', 'conversion_sac']);
        });
    }
};
