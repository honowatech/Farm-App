<?php

namespace Database\Seeders;

use App\Models\Achat;
use App\Models\Bande;
use App\Models\Batiment;
use App\Models\Client;
use App\Models\Facture;
use App\Models\Fournisseur;
use App\Models\Ligne_facture;
use App\Models\Matiere_premiere;
use App\Models\Mortalite;
use App\Models\Nutrition;
use App\Models\Plan;
use App\Models\Produit;
use App\Models\Ramassage;
use App\Models\Stock_matiere_premiere;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\TenantDomain;
use App\Models\Type_animal;
use App\Models\Type_produit;
use App\Models\Typeusers;
use App\Models\Unite;
use App\Models\User;
use App\Models\Vente;
use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Jeu de données multi-tenant pour les tests d'isolation.
 *
 * Crée 3 forfaits, 3 entreprises (tenants) avec domaines et abonnements,
 * des utilisateurs par entreprise (rôle via la table pivot tenant_user),
 * puis 30 jours d'activité métier cloisonnée par tenant.
 *
 * Idempotent : peut être rejoué sans dupliquer les données.
 */
class MultiTenantDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PlanSeeder::class);

        $plans = Plan::pluck('id', 'code');

        $specs = [
            [
                'name' => 'Ferme Alpha',
                'slug' => 'ferme-alpha',
                'domain' => 'alpha.farmapp.test',
                'plan' => 'essentiel',
                'users' => ['admin', 'gestionnaire', 'agent', 'veterinaire', 'commercial', 'client'],
            ],
            [
                'name' => 'Ferme Beta',
                'slug' => 'ferme-beta',
                'domain' => 'beta.farmapp.test',
                'plan' => 'pro',
                'users' => ['admin', 'gestionnaire', 'agent', 'veterinaire', 'magasinier', 'commercial', 'caissier', 'comptable', 'rh'],
            ],
            [
                'name' => 'Ferme Gamma',
                'slug' => 'ferme-gamma',
                'domain' => 'gamma.farmapp.test',
                'plan' => 'entreprise',
                'users' => ['admin', 'gestionnaire', 'agent', 'veterinaire', 'magasinier', 'commercial', 'caissier', 'comptable', 'rh', 'client'],
            ],
            [
                'name' => 'Ferme de démonstration',
                'slug' => 'demo',
                'domain' => 'demo.farmapp.test',
                'plan' => 'entreprise',
                'users' => ['admin'],
            ],
        ];

        foreach ($specs as $spec) {
            $tenant = $this->seedTenant($spec, $plans);
            $this->seedBusinessData($tenant, $spec);
        }

        $this->command?->info('MultiTenantDemoSeeder : '.count($plans).' forfaits, '.count($specs).' entreprises et 30 jours de données créés.');
    }

    /**
     * Crée l'entreprise, son domaine, son abonnement et ses utilisateurs.
     */
    private function seedTenant(array $spec, $plans): Tenant
    {
        $tenant = Tenant::firstOrCreate(
            ['slug' => $spec['slug']],
            [
                'name' => $spec['name'],
                'status' => 'active',
                'is_demo' => true,
                'demo_expires_at' => now()->addMonths(3),
            ]
        );

        TenantDomain::firstOrCreate(
            ['domain' => $spec['domain']],
            ['tenant_id' => $tenant->id, 'is_primary' => true, 'verified_at' => now()]
        );

        Subscription::firstOrCreate(
            ['tenant_id' => $tenant->id, 'plan_id' => $plans[$spec['plan']], 'status' => 'active'],
            [
                'starts_at' => now()->subMonth(),
                'current_period_starts_at' => now()->subMonth(),
                'current_period_ends_at' => now()->addMonths(11),
            ]
        );

        foreach ($spec['users'] as $role) {
            $email = $role.'@'.$spec['slug'].'.test';

            $user = User::firstOrNew(['email' => $email]);
            $user->name = ucfirst($role).' '.$spec['name'];
            $user->id_type_user = Typeusers::firstOrCreate(['libelle' => $role])->id;
            $user->statut = 1;

            if (! $user->exists) {
                $user->password = Hash::make('11111111');
            }

            $user->save();

            $tenant->users()->syncWithoutDetaching([$user->id => [
                'role_key' => $role,
                'status' => 'active',
                'invited_at' => now(),
                'accepted_at' => now(),
            ]]);
        }

        return $tenant;
    }

    /**
     * Injecte 30 jours d'activité métier pour l'entreprise donnée.
     */
    private function seedBusinessData(Tenant $tenant, array $spec): void
    {
        $context = app(TenantContext::class);
        $context->set($tenant);

        Model::unguard();

        try {
            $adminId = User::where('email', 'admin@'.$spec['slug'].'.test')->value('id');

            // Référentiels
            $typeChair = Type_animal::firstOrCreate(['nom' => 'Poulet de chair']);
            $typePonte = Type_animal::firstOrCreate(['nom' => 'Poule pondeuse']);
            $typeAnimal = Type_produit::firstOrCreate(['nom' => 'animal']);
            $typeOeuf = Type_produit::firstOrCreate(['nom' => 'oeuf']);

            $batA = Batiment::firstOrCreate(['nom' => 'Bâtiment A']);
            $batB = Batiment::firstOrCreate(['nom' => 'Bâtiment B']);

            $kg = Unite::firstOrCreate(['libelle' => 'Kilogramme', 'abrege' => 'kg']);

            $alimChair = Matiere_premiere::firstOrCreate(
                ['nom_produit' => 'Aliment chair'],
                ['prix_unitaire' => 400, 'seuil_alerte' => 100]
            );
            $alimPonte = Matiere_premiere::firstOrCreate(
                ['nom_produit' => 'Aliment ponte'],
                ['prix_unitaire' => 380, 'seuil_alerte' => 80]
            );

            $poulet = Produit::firstOrCreate(
                ['nom' => 'Poulet entier'],
                ['prix_unitaire' => 3500, 'id_type_produit' => $typeAnimal->id, 'statut' => 1]
            );
            $oeufs = Produit::firstOrCreate(
                ['nom' => 'Œufs'],
                ['prix_unitaire' => 75, 'id_type_produit' => $typeOeuf->id, 'statut' => 1]
            );

            $client = Client::firstOrCreate(['nom' => 'Client '.$spec['name']], ['telephone' => '690000000']);
            $fournisseur = Fournisseur::firstOrCreate(['nom' => 'Provenderie '.$spec['name']], ['statut' => 1]);

            // Bandes
            $bandeChair = Bande::firstOrCreate(['nom' => 'Bande chair'], [
                'date_lancement_bande' => now()->subDays(30),
                'nbre_sujet' => 500,
                'age_depart' => '1',
                'statut_bande' => 'encours',
                'id_type_animal' => $typeChair->id,
                'id_batiment' => $batA->id,
                'prix_acquisition' => 250000,
                'unite_age_depart' => 'jour',
            ]);

            $bandePonte = Bande::firstOrCreate(['nom' => 'Bande ponte'], [
                'date_lancement_bande' => now()->subDays(30),
                'nbre_sujet' => 300,
                'age_depart' => '1',
                'statut_bande' => 'encours',
                'id_type_animal' => $typePonte->id,
                'id_batiment' => $batB->id,
                'prix_acquisition' => 300000,
                'unite_age_depart' => 'jour',
            ]);

            // 30 jours d'activité : nutrition, ramassage, mortalités
            for ($i = 0; $i < 30; $i++) {
                $dateStr = now()->subDays(30 - $i)->format('Y-m-d');

                Nutrition::firstOrCreate(
                    ['id_bande' => $bandeChair->id, 'id_aliment' => $alimChair->id, 'date' => $dateStr],
                    ['qte' => 100, 'id_unite' => $kg->id, 'nombre_de_passages' => 2]
                );

                Nutrition::firstOrCreate(
                    ['id_bande' => $bandePonte->id, 'id_aliment' => $alimPonte->id, 'date' => $dateStr],
                    ['qte' => 80, 'id_unite' => $kg->id, 'nombre_de_passages' => 2]
                );

                Ramassage::firstOrCreate(
                    ['id_bande' => $bandePonte->id, 'do_date' => $dateStr, 'id_produit' => $oeufs->id],
                    [
                        'id_prestataire' => $adminId,
                        'id_saver' => $adminId,
                        'ids_derive' => '[]',
                        'valeurs_derive' => '[]',
                        'statut' => 1,
                    ]
                );

                if (in_array($i, [3, 10, 17, 24], true)) {
                    Mortalite::firstOrCreate(
                        ['id_bande' => $bandeChair->id, 'date' => $dateStr, 'nombre_morts' => 5],
                        ['id_saver' => $adminId]
                    );
                }
            }

            // Ventes (4 sur le mois)
            for ($i = 7; $i < 30; $i += 7) {
                $dateStr = now()->subDays(30 - $i)->format('Y-m-d');
                $matricule = 'FACT-'.strtoupper($spec['slug']).'-'.$i;

                $facture = Facture::firstOrCreate(['matricule' => $matricule], [
                    'date_facturation' => $dateStr,
                    'total_ht' => 350000,
                    'tva' => 0,
                    'total_ttc' => 350000,
                    'id_client' => $client->id,
                ]);

                Vente::firstOrCreate(['id_facture' => $facture->id], ['id_saver' => $adminId, 'date_vente' => $dateStr]);

                Ligne_facture::firstOrCreate(
                    ['id_facture' => $facture->id, 'id_produit' => $poulet->id],
                    ['type_vente' => 'animal', 'mode_paiement' => 'espece', 'id_bande' => $bandeChair->id, 'qte' => 100, 'prix_unitaire' => 3500, 'total_ligne' => 350000]
                );
            }

            // Achat d'aliment + mouvement de stock matière première
            Stock_matiere_premiere::firstOrCreate(
                ['id_matiere_premiere' => $alimChair->id, 'type_mouvement' => 'approvisionnement'],
                ['quantite' => 1000, 'dernier_stock' => 0, 'nouveau_stock' => '1000', 'date_entree' => now()->subDays(30)->format('Y-m-d')]
            );

            Achat::firstOrCreate(
                ['id_matiere_premiere' => $alimChair->id, 'date' => now()->subDays(30)->format('Y-m-d')],
                ['prix_unitaire' => 400, 'qte' => 1000, 'prix_total' => 400000, 'mode_de_paiement' => 'espece', 'id_fournisseur' => $fournisseur->id, 'unite_achat' => 'kg']
            );
        } finally {
            Model::reguard();
            $context->clear();
        }
    }
}
