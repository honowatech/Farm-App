<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class AddIdCompteInToDepenseDepensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('depense_depenses', function (Blueprint $table) {
            $table->unsignedInteger('id_compte')->nullable()->after('id');
            $table->foreign('id_compte')->references('id')->on('gestion_caisse_comptes')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('depense_depenses', function (Blueprint $table) {
            $table->dropForeign(['id_compte']);
            $table->dropColumn('id_compte');
        });
    }
}
