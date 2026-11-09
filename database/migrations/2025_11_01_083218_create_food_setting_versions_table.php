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
    Schema::create('food_setting_versions', function (Blueprint $table) {
        $table->id();
        
        // Clé étrangère vers le "planning" parent dans foods_setting
        $table->foreignId('food_setting_id')->constrained('foods_setting')->onDelete('cascade');
        
        // ✨ MODIFICATION ICI: Utilisation de unsignedBigInteger pour garantir l'UNSIGNED
        $table->unsignedBigInteger('id_unite'); 
        
        // Les données "snapshot" du planning
        $table->unsignedInteger('age_min');
        $table->unsignedInteger('age_max');
        $table->decimal('quantite_aliment', 8, 2);
        $table->unsignedInteger('frequency')->default(1);
        $table->string('period', 20)->default('day');

        // Les dates de validité de cette version
        $table->timestamp('activated_at')->comment('Date à laquelle cette version est devenue active');
        $table->timestamp('ended_at')->nullable()->comment('Date à laquelle cette version a été remplacée par une nouvelle');

        $table->timestamps(); // created_at, updated_at
        
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_setting_versions');
    }
};
