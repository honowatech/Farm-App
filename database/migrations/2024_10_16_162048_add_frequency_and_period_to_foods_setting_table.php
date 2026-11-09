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
        Schema::table('foods_setting', function (Blueprint $table) {
             $table->integer('frequency')->default(1); // Nombre de fois de nutrition par période
             $table->string('period')->default('day'); // Période de nutrition (day, week, month)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('foods_setting', function (Blueprint $table) {
            $table->dropColumn('frequency');
            $table->dropColumn('period');
        });
    }
};
