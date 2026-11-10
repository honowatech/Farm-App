<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bande_couts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_bande');
            $table->foreign('id_bande')->references('id')->on('bandes')->onDelete('cascade');
            $table->foreignId('id_type_cout')->constrained('bande_cout_types')->onDelete('cascade');
            $table->float('montant');
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('id_saver')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bande_couts');
    }
};
