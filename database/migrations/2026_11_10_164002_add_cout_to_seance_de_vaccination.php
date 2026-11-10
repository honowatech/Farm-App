<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seance_de_vaccination', function (Blueprint $table) {
            $table->float('cout')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('seance_de_vaccination', function (Blueprint $table) {
            $table->dropColumn('cout');
        });
    }
};
