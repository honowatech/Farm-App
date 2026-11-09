<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeAndFrequenceToCategorieDepenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categorie_depenses', function (Blueprint $table) {
            $table->string('type')->after('nom')->default('ponctuel'); // Le champ "type" avec une valeur par défaut
            $table->string('frequence')->nullable()->after('type');    // Le champ "frequence" est nullable car il peut ne pas être utilisé pour certains types
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categorie_depenses', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('frequence');
        });
    }
}
