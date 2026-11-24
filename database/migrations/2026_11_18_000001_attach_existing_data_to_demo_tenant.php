<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Tables métier rattachées au tenant de démonstration.
     * Doit rester aligné avec la migration d'ajout de tenant_id.
     */
    private array $tables = [
        'achats', 'agents', 'animals', 'app_settings', 'autre_depenses',
        'bande_cout_types', 'bande_couts', 'bandes', 'batiments', 'clients',
        'derive_produit', 'detail_fournisseur', 'factures', 'food_setting_versions',
        'foods_setting', 'fournisseurs', 'ligne_factures', 'logo', 'matiere_premieres',
        'mortalite_causes', 'mortalites', 'nettoyages', 'nutritions',
        'paiement_salaires', 'produits', 'programmes_vaccination',
        'ramassages', 'salaries', 'seance_de_vaccination', 'stock_matiere_premieres',
        'stock_produits', 'type_animals', 'type_produits', 'unite', 'ventes', 'veterinaires',
        'depense_depenses', 'depense_categorie_depenses',
        'gestion_caisse_comptes', 'gestion_caisse_devises',
        'gestion_caisse_transactions', 'gestion_caisse_transferts',
        'depenses', 'categorie_depenses', 'settings',
    ];

    public function up(): void
    {
        // Tenant de démonstration : réutilisé s'il existe déjà, sinon créé.
        $demoId = DB::table('tenants')->where('slug', 'demo')->value('id');

        if (! $demoId) {
            $demoId = (string) Str::ulid();

            DB::table('tenants')->insert([
                'id' => $demoId,
                'name' => 'Ferme de démonstration',
                'slug' => 'demo',
                'status' => 'active',
                'is_demo' => true,
                'demo_expires_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Rattache toutes les données métier non encore attribuées au tenant de démo.
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'tenant_id')) {
                DB::table($table)->whereNull('tenant_id')->update(['tenant_id' => $demoId]);
            }
        }
    }

    public function down(): void
    {
        $demoId = DB::table('tenants')->where('slug', 'demo')->value('id');

        if ($demoId) {
            foreach ($this->tables as $table) {
                if (Schema::hasTable($table) && Schema::hasColumn($table, 'tenant_id')) {
                    DB::table($table)->where('tenant_id', $demoId)->update(['tenant_id' => null]);
                }
            }
        }

        // The demo tenant may have existed before this migration or may now be
        // referenced by subscriptions and memberships; do not delete it here.
    }
};
