<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ligne_factures', function (Blueprint $table) {
             DB::statement("ALTER TABLE ligne_factures MODIFY type_vente ENUM('animal', 'produit') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ligne_factures', function (Blueprint $table) {
            //
        });
    }
};
