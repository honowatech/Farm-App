<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables métier à cloisonner par entreprise (colonne tenant_id).
     *
     * Les tables globales (users, type_users, permissions, control plane,
     * translations, files d'emploi) sont volontairement exclues.
     */
    private array $tables = [
        // App
        'achats', 'agents', 'animals', 'app_settings', 'autre_depenses',
        'bande_cout_types', 'bande_couts', 'bandes', 'batiments', 'clients',
        'derive_produit', 'detail_fournisseur', 'factures', 'food_setting_versions',
        'foods_setting', 'fournisseurs', 'ligne_factures', 'logo', 'matiere_premieres',
        'mortalite_causes', 'mortalites', 'nettoyages', 'nutritions',
        'paiement_salaires', 'payment_transactions', 'produits', 'programmes_vaccination',
        'ramassages', 'salaries', 'seance_de_vaccination', 'stock_matiere_premieres',
        'stock_produits', 'type_animals', 'type_produits', 'unite', 'ventes', 'veterinaires',
        // Modules (noms effectifs des entités)
        'depense_depenses', 'depense_categorie_depenses',
        'gestion_caisse_comptes', 'gestion_caisse_devises',
        'gestion_caisse_transactions', 'gestion_caisse_transferts',
        // Anciens noms de tables (au cas où la base n'a pas été renommée)
        'depenses', 'categorie_depenses',
        // Réglages (si la table existe)
        'settings',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->foreignUlid('tenant_id')
                        ->nullable()
                        ->index()
                        ->constrained('tenants')
                        ->restrictOnDelete();
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropConstrainedForeignId('tenant_id');
                });
            }
        }
    }
};
