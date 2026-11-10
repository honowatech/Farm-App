<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mortalites', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_bande');
            $table->foreign('id_bande')->references('id')->on('bandes')->onDelete('cascade');
            $table->date('date');
            $table->integer('nombre_morts')->default(0);
            $table->foreignId('id_cause')->nullable()->constrained('mortalite_causes')->nullOnDelete();
            $table->text('observation')->nullable();
            $table->foreignId('id_saver')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mortalites');
    }
};
