<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('depenses', 'id_compte')) {
            Schema::table('depenses', function (Blueprint $table) {
                $table->unsignedInteger('id_compte')->nullable()->after('id_categorie');
                $table->foreign('id_compte')
                    ->references('id')
                    ->on('gestion_caisse_comptes')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('depenses') || ! Schema::hasColumn('depenses', 'id_compte')) {
            return;
        }

        Schema::table('depenses', function (Blueprint $table) {
            $table->dropForeign(['id_compte']);
            $table->dropColumn('id_compte');
        });
    }
};
