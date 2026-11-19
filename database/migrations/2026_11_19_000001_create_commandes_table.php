<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Commandes clients (portail client + workflow d'état).
 *
 * Le statut suit un cycle : brouillon → confirmee → en_preparation → prete
 * → encaissee → livree (ou annulee à tout moment avant livraison).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('matricule', 40)->unique();
            $table->unsignedInteger('id_client');
            $table->string('statut', 30)->default('brouillon');
            $table->decimal('total_ht', 12, 2)->default(0);
            $table->decimal('tva', 12, 2)->default(0);
            $table->decimal('total_ttc', 12, 2)->default(0);
            $table->unsignedBigInteger('id_saver')->nullable();
            $table->string('date_commande')->nullable();
            $table->foreignUlid('tenant_id')->nullable()->index()->constrained('tenants')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_client')->references('id')->on('clients')->restrictOnDelete();
            $table->index(['id_client', 'statut']);
            $table->index(['tenant_id', 'statut']);
        });

        Schema::create('ligne_commandes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_commande');
            $table->unsignedInteger('id_produit');
            $table->unsignedInteger('qte');
            $table->decimal('prix_unitaire', 12, 2);
            $table->decimal('total_ligne', 12, 2);
            $table->foreignUlid('tenant_id')->nullable()->index()->constrained('tenants')->restrictOnDelete();
            $table->timestamps();

            $table->foreign('id_commande')->references('id')->on('commandes')->cascadeOnDelete();
            $table->foreign('id_produit')->references('id')->on('produits')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ligne_commandes');
        Schema::dropIfExists('commandes');
    }
};
