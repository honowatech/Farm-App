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
        Schema::table('nutritions', function (Blueprint $table) {
            $table->unsignedBigInteger('id_aliment')->nullable()->change();
            $table->unsignedBigInteger('id_unite')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nutritions', function (Blueprint $table) {
            $table->unsignedBigInteger('id_aliment')->nullable(false)->change();
            $table->unsignedBigInteger('id_unite')->nullable(false)->change();
        });
    }
};
