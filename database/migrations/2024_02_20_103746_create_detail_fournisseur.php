<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('detail_fournisseur', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('id_fournisseur')->constrained('fournisseurs');
            $table->bigInteger('id_matiere_premiere')->constrained('matiere_premieres');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('detail_fournisseur');
    }

};