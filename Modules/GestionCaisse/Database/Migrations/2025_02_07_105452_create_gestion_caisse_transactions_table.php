<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gestion_caisse_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('compte_id');
            $table->enum('transaction_type', ['entrée', 'sortie'])->default('sortie');
            $table->decimal('montant', 10, 2);
            $table->date('transaction_date');
            $table->string('description')->nullable();
            $table->string('reference_number')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('related_transaction_id')->nullable()->constrained('gestion_caisse_transactions');
            $table->foreignId('id_depense')->nullable()->constrained('depense_depenses');
            $table->foreign('compte_id')->references('id')->on('gestion_caisse_comptes')->cascadeOnDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gestion_caisse_transactions');
    }
};
