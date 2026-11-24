<?php

namespace App\Services;

use App\Models\Achat;
use App\Models\Agent;
use App\Models\Animal;
use App\Models\Bande;
use App\Models\BandeCout;
use App\Models\BandeCoutType;
use App\Models\Batiment;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Derive_produit;
use App\Models\Detail_fournisseur;
use App\Models\Facture;
use App\Models\Food_Setting;
use App\Models\FoodSettingVersion;
use App\Models\Fournisseur;
use App\Models\Ligne_facture;
use App\Models\LigneCommande;
use App\Models\Matiere_premiere;
use App\Models\Mortalite;
use App\Models\MortaliteCause;
use App\Models\Nettoyage;
use App\Models\Nutrition;
use App\Models\Plan;
use App\Models\Produit;
use App\Models\ProgrammeVaccination;
use App\Models\Ramassage;
use App\Models\Seance_de_vaccination;
use App\Models\Stock_matiere_premiere;
use App\Models\Stock_produit;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\TenantDomain;
use App\Models\Type_animal;
use App\Models\Type_produit;
use App\Models\Typeusers;
use App\Models\Unite;
use App\Models\User;
use App\Models\Vente;
use App\Models\Veterinaire;
use App\Support\TenantContext;
use Database\Seeders\PlanSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Activitylog\ActivityLogStatus;
use Modules\Depenses\Entities\Categorie;
use Modules\Depenses\Entities\Depense;
use Modules\GestionCaisse\Entities\Compte as CaisseCompte;
use Modules\GestionCaisse\Entities\Devise as CaisseDevise;
use Modules\GestionCaisse\Entities\Transaction as CaisseTransaction;
use Modules\GestionCaisse\Entities\Transfert as CaisseTransfert;
use Modules\Salarie\Entities\PaiementSalaires;
use Modules\Salarie\Entities\Salaries;

class DemoWorkspaceService
{
    public const ACCESS_HOURS = 24;

    /**
     * Stock produit courant suivi en mémoire pendant le provisioning, pour
     * éviter une requête de lecture par mouvement (la table grossit au fil du
     * seed et chaque lecture sans index ralentit l'ensemble).
     *
     * @var array<int, float>
     */
    private array $productStockLevels = [];

    /**
     * Crée un espace de démonstration isolé, valable 24 heures.
     *
     * @return array{user: User, tenant: Tenant, access_token: string}
     */
    public function provisionVisitorWorkspace(): array
    {
        // Le provisioning injecte une année de données métier : lever la
        // limite d'exécution PHP (60 s par défaut) pour ne pas être interrompu.
        @set_time_limit(0);

        $this->ensurePlans();

        $plans = Plan::pluck('id', 'code');
        $slug = 'demo-'.Str::lower(Str::random(24));
        $accessToken = Str::random(64);
        $roles = array_values(array_diff(array_keys(config('permissions.roles', [])), ['superadmin']));
        $spec = [
            'name' => 'Espace de démonstration',
            'slug' => $slug,
            'domain' => $slug.'.demo.farmapp.test',
            'plan' => 'entreprise',
            'users' => $roles,
        ];

        return DB::transaction(function () use ($plans, $spec, $accessToken) {
            app(ActivityLogStatus::class)->disable();

            try {
                $tenant = $this->seedTenant($spec, $plans);
                $tenant->demo_expires_at = now()->addHours(self::ACCESS_HOURS);
                $tenant->demo_claim_token_hash = hash('sha256', $accessToken);
                $tenant->save();

                $this->seedBusinessData($tenant, $spec);

                $user = User::where('email', 'admin@'.$spec['slug'].'.test')->firstOrFail();
                $context = app(TenantContext::class);
                $context->set($tenant);

                try {
                    Client::firstOrCreate(
                        ['id_user' => $user->id],
                        [
                            'nom' => $user->name,
                            'email' => $user->email,
                            'telephone' => '690000000',
                            'adresse' => 'Espace de démonstration',
                        ]
                    );
                } finally {
                    $context->clear();
                }

                $user->password = Str::random(64);
                $user->save();

                return [
                    'user' => $user,
                    'tenant' => $tenant,
                    'access_token' => $accessToken,
                ];
            } finally {
                app(ActivityLogStatus::class)->enable();
            }
        });
    }

    /**
     * Prolonge uniquement une démo encore active; une démo expirée doit repartir de zéro.
     *
     * @return array{user: User, tenant: Tenant, access_token: string}|null
     */
    public function resumeActiveVisitorWorkspace(?string $token): ?array
    {
        if (! is_string($token) || strlen($token) !== 64) {
            return null;
        }

        return DB::transaction(function () use ($token): ?array {
            $tenant = Tenant::query()
                ->where('demo_claim_token_hash', hash('sha256', $token))
                ->where('is_demo', true)
                ->where('status', 'active')
                ->where('demo_expires_at', '>', now())
                ->lockForUpdate()
                ->first();

            if (! $tenant) {
                return null;
            }

            $tenant->demo_expires_at = now()->addHours(self::ACCESS_HOURS);
            $tenant->save();

            return [
                'tenant' => $tenant,
                'user' => $this->demoAdministrator($tenant),
                'access_token' => $token,
            ];
        });
    }

    public function demoAdministrator(Tenant $tenant): User
    {
        return $tenant->users()
            ->wherePivot('role_key', 'admin')
            ->wherePivot('status', 'active')
            ->orderBy('users.id')
            ->firstOrFail();
    }

    /**
     * Démo partagée : retourne la ferme de démonstration commune et garantit
     * qu'elle est active, non expirée et dotée de tous les profils métier.
     *
     * @return array{tenant: Tenant, user: User}
     */
    public function ensureSharedDemo(): array
    {
        // L'injection d'une année de données peut dépasser la limite d'exécution
        // PHP par défaut (60 s) lors du premier accès : on la lève.
        @set_time_limit(0);

        $this->ensurePlans();
        $plans = Plan::pluck('id', 'code');
        $roles = array_values(array_diff(array_keys(config('permissions.roles', [])), ['superadmin']));
        $spec = [
            'name' => 'Ferme de démonstration',
            'slug' => 'demo',
            'domain' => 'demo.farmapp.test',
            'plan' => 'entreprise',
            'users' => $roles,
        ];

        $tenant = $this->seedTenant($spec, $plans);
        $tenant->status = 'active';
        $tenant->demo_expires_at = now()->addYears(10);
        $tenant->save();

        $context = app(TenantContext::class);
        $context->set($tenant);

        try {
            // Sur une base fraîchement installée, la démo partagée n'a pas encore
            // reçu son année d'activité métier : on l'injecte une seule fois.
            if (! Produit::query()->exists()) {
                app(ActivityLogStatus::class)->disable();

                try {
                    $this->seedBusinessData($tenant, $spec);
                } finally {
                    app(ActivityLogStatus::class)->enable();
                }
            }
        } finally {
            $context->clear();
        }

        // Le profil « client » doit ouvrir le portail sur une fiche alimentée :
        // on relie la fiche « Client {nom} » du seeder annuel au compte client de
        // la démo (plutôt que de laisser une fiche vide à côté).
        $context = app(TenantContext::class);
        $context->set($tenant);

        try {
            $clientUser = User::where('email', 'client@'.$spec['slug'].'.test')->first();

            if ($clientUser) {
                $seeded = Client::query()
                    ->where('nom', 'Client '.$spec['name'])
                    ->whereNull('id_user')
                    ->orderBy('id')
                    ->first();

                if ($seeded) {
                    $seeded->id_user = $clientUser->id;
                    $seeded->email = $clientUser->email;
                    $seeded->save();

                    // Purge les doublons vides créés par d'anciennes versions.
                    Client::where('id_user', $clientUser->id)
                        ->where('id', '!=', $seeded->id)
                        ->forceDelete();
                } else {
                    Client::firstOrCreate(
                        ['id_user' => $clientUser->id],
                        [
                            'nom' => 'Client '.$spec['name'],
                            'email' => $clientUser->email,
                            'telephone' => '690000000',
                            'adresse' => $spec['name'],
                            'statut' => 1,
                        ]
                    );
                }
            }
        } finally {
            $context->clear();
        }

        return [
            'tenant' => $tenant,
            'user' => $this->demoAdministrator($tenant),
        ];
    }

    /**
     * Crée les entreprises d'exemple gérées par le seeder multi-tenant.
     */
    public function seedPreconfiguredTenants($plans): int
    {
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

        return count($specs);
    }

    private function ensurePlans(): void
    {
        if (Plan::where('code', 'entreprise')->exists()) {
            return;
        }

        app(PlanSeeder::class)->run();
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

        if ($tenant->is_demo && $tenant->demo_expires_at === null) {
            $tenant->demo_expires_at = now()->addMonths(3);
            $tenant->save();
        }

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
     * Injecte une année d'activité métier pour l'entreprise donnée.
     */
    private function seedBusinessData(Tenant $tenant, array $spec): void
    {
        $context = app(TenantContext::class);
        $context->set($tenant);

        Model::unguard();

        try {
            $adminId = User::where('email', 'admin@'.$spec['slug'].'.test')->value('id');
            $actorEmails = array_map(fn (string $role) => $role.'@'.$spec['slug'].'.test', $spec['users']);
            $actors = User::query()
                ->whereIn('email', $actorEmails)
                ->get(['id', 'email'])
                ->mapWithKeys(fn (User $user) => [Str::before($user->email, '@') => (int) $user->id])
                ->all();
            $actors['admin'] = (int) $adminId;
            $seedAnnualProductStock = ! Stock_produit::query()->exists();

            $typeChair = Type_animal::firstOrCreate(['nom' => 'Poulet de chair']);
            $typePonte = Type_animal::firstOrCreate(['nom' => 'Poule pondeuse']);
            $typeAnimal = Type_produit::firstOrCreate(['nom' => 'animal']);
            $typeOeuf = Type_produit::firstOrCreate(['nom' => 'oeuf']);
            $typeFiente = Type_produit::firstOrCreate(['nom' => 'fiente']);

            $batA = Batiment::firstOrCreate(['nom' => 'Bâtiment A']);
            $batB = Batiment::firstOrCreate(['nom' => 'Bâtiment B']);

            $kg = Unite::firstOrCreate(['libelle' => 'Kilogramme', 'abrege' => 'kg']);
            Unite::firstOrCreate(['libelle' => 'Litre', 'abrege' => 'L']);
            Unite::firstOrCreate(['libelle' => 'Pièce', 'abrege' => 'pc']);
            Unite::firstOrCreate(['libelle' => 'Plateau', 'abrege' => 'plt']);
            Unite::firstOrCreate(['libelle' => 'Sac de 50 kg', 'abrege' => 'sac']);

            $alimChair = Matiere_premiere::firstOrCreate(
                ['nom_produit' => 'Aliment chair'],
                ['prix_unitaire' => 400, 'seuil_alerte' => 100]
            );
            $alimPonte = Matiere_premiere::firstOrCreate(
                ['nom_produit' => 'Aliment ponte'],
                ['prix_unitaire' => 380, 'seuil_alerte' => 80]
            );
            $alimCroissance = Matiere_premiere::firstOrCreate(
                ['nom_produit' => 'Aliment croissance'],
                ['prix_unitaire' => 390, 'seuil_alerte' => 120]
            );
            $mais = Matiere_premiere::firstOrCreate(
                ['nom_produit' => 'Maïs concassé'],
                ['prix_unitaire' => 250, 'seuil_alerte' => 75]
            );
            $vaccin = Matiere_premiere::firstOrCreate(
                ['nom_produit' => 'Vaccin Newcastle'],
                ['prix_unitaire' => 15000, 'seuil_alerte' => 2]
            );

            $this->seedFoodSettings($typeChair, $typePonte, $alimChair, $alimPonte, $kg);

            $poulet = Produit::firstOrCreate(
                ['nom' => 'Poulet entier'],
                ['prix_unitaire' => 3500, 'id_type_produit' => $typeAnimal->id, 'statut' => 1]
            );
            $oeufs = Produit::firstOrCreate(
                ['nom' => 'Œufs'],
                ['prix_unitaire' => 75, 'id_type_produit' => $typeOeuf->id, 'statut' => 1]
            );
            $fiente = Produit::firstOrCreate(
                ['nom' => 'Fiente'],
                ['prix_unitaire' => 500, 'id_type_produit' => $typeFiente->id, 'statut' => 1]
            );
            Derive_produit::firstOrCreate(
                ['nom' => 'Poulet découpé', 'id_produit' => $poulet->id],
                [
                    'min' => 1,
                    'max' => 10,
                    'prix_unitaire' => 4200,
                    'id_unite' => $kg->id,
                    'statut' => 1,
                ]
            );

            $client = Client::firstOrCreate(['nom' => 'Client '.$spec['name']], ['telephone' => '690000000']);
            $fournisseur = Fournisseur::firstOrCreate(['nom' => 'Provenderie '.$spec['name']], ['statut' => 1]);
            $secondaryClients = [
                Client::firstOrCreate(['nom' => 'Restaurant Le Marché '.$spec['slug']], ['telephone' => '690000031', 'adresse' => 'Douala']),
                Client::firstOrCreate(['nom' => 'Supermarché Central '.$spec['slug']], ['telephone' => '690000032', 'adresse' => 'Yaoundé']),
                Client::firstOrCreate(['nom' => 'Hôtel du Littoral '.$spec['slug']], ['telephone' => '690000033', 'adresse' => 'Kribi']),
            ];
            $secondarySuppliers = [
                Fournisseur::firstOrCreate(['nom' => 'Couvoir du Centre '.$spec['slug']], ['statut' => 1, 'telephone' => '690000041']),
                Fournisseur::firstOrCreate(['nom' => 'Vétérinaire & Co '.$spec['slug']], ['statut' => 1, 'telephone' => '690000042']),
            ];
            foreach ([$alimChair, $alimPonte, $alimCroissance, $mais, $vaccin] as $matiere) {
                Detail_fournisseur::firstOrCreate([
                    'id_fournisseur' => $fournisseur->id,
                    'id_matiere_premiere' => $matiere->id,
                ]);
            }
            foreach ([$alimCroissance, $mais] as $matiere) {
                Detail_fournisseur::firstOrCreate([
                    'id_fournisseur' => $secondarySuppliers[0]->id,
                    'id_matiere_premiere' => $matiere->id,
                ]);
            }
            Detail_fournisseur::firstOrCreate([
                'id_fournisseur' => $secondarySuppliers[1]->id,
                'id_matiere_premiere' => $vaccin->id,
            ]);

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
                'date_lancement_bande' => now()->subYear(),
                'nbre_sujet' => 300,
                'age_depart' => '1',
                'statut_bande' => 'encours',
                'id_type_animal' => $typePonte->id,
                'id_batiment' => $batB->id,
                'prix_acquisition' => 300000,
                'unite_age_depart' => 'jour',
            ]);

            $causeMaladie = MortaliteCause::firstOrCreate(['libelle' => 'Maladie respiratoire'], ['statut' => 1]);
            $causeChaleur = MortaliteCause::firstOrCreate(['libelle' => 'Stress thermique'], ['statut' => 1]);
            $historicalBands = $this->seedHistoricalBroilerBands($spec, $typeChair->id, $batA->id);
            $this->seedHistoricalStockPurchases(
                $alimChair,
                $alimPonte,
                $alimCroissance,
                $mais,
                $vaccin,
                $fournisseur,
                $secondarySuppliers,
                $poulet,
                $oeufs,
                $fiente,
                $seedAnnualProductStock
            );

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
                        'id_prestataire' => $actors['agent'] ?? $adminId,
                        'id_saver' => $actors['agent'] ?? $adminId,
                        'ids_derive' => '[]',
                        'valeurs_derive' => '[]',
                        'statut' => 1,
                    ]
                );

                if ($i % 7 === 0) {
                    Ramassage::firstOrCreate(
                        ['id_bande' => $bandeChair->id, 'do_date' => $dateStr, 'id_produit' => $fiente->id],
                        [
                            'id_prestataire' => $actors['agent'] ?? $adminId,
                            'id_saver' => $actors['agent'] ?? $adminId,
                            'ids_derive' => '[]',
                            'valeurs_derive' => '[]',
                            'statut' => 1,
                        ]
                    );
                }

                if ($i % 5 === 0) {
                    $day = Carbon::parse($dateStr)->setTime(8, 0);
                    $this->recordProductStockMovement($oeufs, 'approvisionnement', 220 + (($i * 13) % 70), $day);
                }

                if ($i % 7 === 0) {
                    $this->recordProductStockMovement($fiente, 'approvisionnement', 25, Carbon::parse($dateStr)->setTime(8, 15));
                }

                if (in_array($i, [3, 10, 17, 24], true)) {
                    Mortalite::firstOrCreate(
                        ['id_bande' => $bandeChair->id, 'date' => $dateStr, 'nombre_morts' => 5],
                        [
                            'id_cause' => $causeMaladie->id,
                            'observation' => 'Suivi sanitaire de la bande',
                            'id_saver' => $actors['agent'] ?? $adminId,
                        ]
                    );
                }
            }

            $this->recordProductStockMovement(
                $poulet,
                'approvisionnement',
                600,
                now()->subDays(30)->startOfDay()
            );

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

                Vente::firstOrCreate(['id_facture' => $facture->id], ['id_saver' => $actors['commercial'] ?? $adminId, 'date_vente' => $dateStr]);

                $stockMovement = $this->recordProductStockMovement(
                    $poulet,
                    'destockage',
                    100,
                    Carbon::parse($dateStr)->setTime(11, 0)
                );

                $line = Ligne_facture::firstOrCreate(
                    ['id_facture' => $facture->id, 'id_produit' => $poulet->id],
                    [
                        'type_vente' => 'animal',
                        'Id_stock_produit' => $stockMovement->id,
                        'mode_paiement' => 'espece',
                        'id_bande' => $bandeChair->id,
                        'qte' => 100,
                        'prix_unitaire' => 3500,
                        'total_ligne' => 350000,
                    ]
                );

                if (! $line->id_stock_produit) {
                    $line->id_stock_produit = $stockMovement->id;
                    $line->save();
                }
            }

            Stock_matiere_premiere::firstOrCreate(
                ['id_matiere_premiere' => $alimChair->id, 'type_mouvement' => 'approvisionnement'],
                ['quantite' => 1000, 'dernier_stock' => 0, 'nouveau_stock' => '1000', 'date_entree' => now()->subDays(30)->format('Y-m-d')]
            );

            Achat::firstOrCreate(
                ['id_matiere_premiere' => $alimChair->id, 'date' => now()->subDays(30)->format('Y-m-d')],
                ['prix_unitaire' => 400, 'qte' => 1000, 'prix_total' => 400000, 'mode_de_paiement' => 'espece', 'id_fournisseur' => $fournisseur->id, 'unite_achat' => 'kg']
            );

            $this->seedExtendedDemoData(
                $spec,
                $adminId,
                $actors,
                $bandeChair,
                $bandePonte,
                $historicalBands,
                $causeMaladie,
                $causeChaleur,
                $batA,
                $batB,
                $alimChair,
                $alimPonte,
                $alimCroissance,
                $mais,
                $vaccin,
                $fournisseur,
                $secondarySuppliers,
                $client,
                $secondaryClients,
                $poulet,
                $oeufs,
                $fiente,
                $seedAnnualProductStock
            );
        } finally {
            Model::reguard();
            $context->clear();
        }
    }

    /**
     * Reconstitue les lots de poulets vendus pendant l'exercice précédent.
     *
     * @return array<int, Bande>
     */
    private function seedHistoricalBroilerBands(array $spec, int $animalTypeId, int $buildingId): array
    {
        $bands = [];

        for ($cycle = 0; $cycle < 6; $cycle++) {
            $launchDate = now()->subMonths(14 - ($cycle * 2))->startOfMonth();
            $bands[] = Bande::firstOrCreate(
                ['nom' => 'Bande chair '.$spec['slug'].' '.$launchDate->format('Y-m')],
                [
                    'date_lancement_bande' => $launchDate,
                    'date_fin_bande' => $launchDate->copy()->addDays(56),
                    'nbre_sujet' => 1000,
                    'age_depart' => '1',
                    'statut_bande' => 'terminee',
                    'id_type_animal' => $animalTypeId,
                    'id_batiment' => $buildingId,
                    'prix_acquisition' => 500000,
                    'unite_age_depart' => 'jour',
                ]
            );
        }

        return $bands;
    }

    /**
     * Ajoute achats et mouvements de stock datés avant les opérations récentes.
     *
     * @param  array<int, Fournisseur>  $secondarySuppliers
     */
    private function seedHistoricalStockPurchases(
        Matiere_premiere $alimChair,
        Matiere_premiere $alimPonte,
        Matiere_premiere $alimCroissance,
        Matiere_premiere $mais,
        Matiere_premiere $vaccin,
        Fournisseur $fournisseur,
        array $secondarySuppliers,
        Produit $poulet,
        Produit $oeufs,
        Produit $fiente,
        bool $seedAnnualProductStock
    ): void {
        $feeds = [$alimChair, $alimPonte, $alimCroissance, $mais];
        $suppliers = [$fournisseur, ...$secondarySuppliers];

        for ($monthsAgo = 12; $monthsAgo >= 1; $monthsAgo--) {
            $month = now()->subMonths($monthsAgo)->startOfMonth();
            $monthKey = $month->format('Ym');
            $feed = $feeds[(12 - $monthsAgo) % count($feeds)];
            $supplier = $suppliers[(12 - $monthsAgo) % count($suppliers)];
            $purchaseDate = $month->copy()->addDays(4)->setTime(8, 0);
            $quantity = 600;
            $previousStock = (float) (Stock_matiere_premiere::query()
                ->where('id_matiere_premiere', $feed->id)
                ->whereDate('date_entree', '<=', $purchaseDate->toDateString())
                ->orderByDesc('date_entree')
                ->orderByDesc('id')
                ->value('nouveau_stock') ?? 0);
            $movement = Stock_matiere_premiere::firstOrCreate(
                ['id_matiere_premiere' => $feed->id, 'motif' => 'DEMO-ANNUAL-MP-'.$monthKey],
                [
                    'type_mouvement' => 'approvisionnement',
                    'quantite' => $quantity,
                    'dernier_stock' => $previousStock,
                    'nouveau_stock' => (string) ($previousStock + $quantity),
                    'date_entree' => $purchaseDate,
                    'created_at' => $purchaseDate,
                    'updated_at' => $purchaseDate,
                ]
            );

            Achat::firstOrCreate(
                ['id_matiere_premiere' => $feed->id, 'date' => $purchaseDate->toDateString()],
                [
                    'id_stock_matiere_premiere' => $movement->id,
                    'prix_unitaire' => $feed->prix_unitaire,
                    'qte' => $quantity,
                    'prix_total' => $quantity * (float) $feed->prix_unitaire,
                    'mode_de_paiement' => 'espece',
                    'id_fournisseur' => $supplier->id,
                    'unite_achat' => 'kg',
                ]
            );

            if (! $seedAnnualProductStock) {
                continue;
            }

            $this->recordProductStockMovement(
                $poulet,
                'approvisionnement',
                300,
                $month->copy()->addDays(1)->setTime(7, 0)
            );
            $this->recordProductStockMovement(
                $poulet,
                'destockage',
                150,
                $month->copy()->addDays(11)->setTime(11, 0)
            );
            $this->recordProductStockMovement(
                $poulet,
                'destockage',
                150,
                $month->copy()->addDays(23)->setTime(11, 0)
            );
            $this->recordProductStockMovement(
                $oeufs,
                'approvisionnement',
                4000,
                $month->copy()->addDays(1)->setTime(7, 30)
            );
            $this->recordProductStockMovement(
                $oeufs,
                'destockage',
                2500,
                $month->copy()->addDays(23)->setTime(11, 30)
            );

            if ($monthsAgo % 3 === 0) {
                $this->recordProductStockMovement(
                    $fiente,
                    'approvisionnement',
                    100,
                    $month->copy()->addDays(5)->setTime(8, 0)
                );
            }
        }

        $this->seedAnnualVaccinePurchases($vaccin, $secondarySuppliers[1]);
    }

    private function seedAnnualVaccinePurchases(Matiere_premiere $vaccin, Fournisseur $supplier): void
    {
        for ($monthsAgo = 12; $monthsAgo >= 1; $monthsAgo -= 3) {
            $purchaseDate = now()->subMonths($monthsAgo)->startOfMonth()->addDays(8);
            $quantity = 5;
            $motif = 'DEMO-ANNUAL-VACCIN-'.$purchaseDate->format('Ym');
            $movement = Stock_matiere_premiere::firstOrCreate(
                ['id_matiere_premiere' => $vaccin->id, 'motif' => $motif],
                [
                    'type_mouvement' => 'approvisionnement',
                    'quantite' => $quantity,
                    'dernier_stock' => 0,
                    'nouveau_stock' => (string) $quantity,
                    'date_entree' => $purchaseDate,
                    'created_at' => $purchaseDate,
                    'updated_at' => $purchaseDate,
                ]
            );

            Achat::firstOrCreate(
                ['id_matiere_premiere' => $vaccin->id, 'date' => $purchaseDate->toDateString()],
                [
                    'id_stock_matiere_premiere' => $movement->id,
                    'prix_unitaire' => $vaccin->prix_unitaire,
                    'qte' => $quantity,
                    'prix_total' => $quantity * (float) $vaccin->prix_unitaire,
                    'mode_de_paiement' => 'espece',
                    'id_fournisseur' => $supplier->id,
                    'unite_achat' => 'unité',
                ]
            );
        }
    }

    private function seedExtendedDemoData(
        array $spec,
        int $adminId,
        array $actors,
        Bande $bandeChair,
        Bande $bandePonte,
        array $historicalBands,
        MortaliteCause $causeMaladie,
        MortaliteCause $causeChaleur,
        Batiment $batA,
        Batiment $batB,
        Matiere_premiere $alimChair,
        Matiere_premiere $alimPonte,
        Matiere_premiere $alimCroissance,
        Matiere_premiere $mais,
        Matiere_premiere $vaccin,
        Fournisseur $fournisseur,
        array $secondarySuppliers,
        Client $client,
        array $secondaryClients,
        Produit $poulet,
        Produit $oeufs,
        Produit $fiente,
        bool $seedAnnualProductStock
    ): void {
        $start = now()->subDays(29)->startOfDay();
        $agent = Agent::firstOrCreate(
            ['nom' => 'Agent terrain '.$spec['name']],
            ['adresse' => 'Ferme de démonstration', 'telephone' => '690000011', 'statut' => 1]
        );
        $veterinaire = Veterinaire::firstOrCreate(
            ['nom' => 'Dr. Amadou '.$spec['name']],
            ['adresse' => 'Centre vétérinaire', 'telephone' => '690000012', 'statut' => 1]
        );

        foreach ([
            [$bandeChair->id_type_animal, 7, 'Newcastle', 14],
            [$bandeChair->id_type_animal, 14, 'Gumboro', 14],
            [$bandePonte->id_type_animal, 14, 'Newcastle', 30],
            [$bandePonte->id_type_animal, 21, 'Bronchite infectieuse', 30],
        ] as [$animalTypeId, $age, $vaccine, $recall]) {
            ProgrammeVaccination::firstOrCreate(
                ['id_type_animal' => $animalTypeId, 'age_jour' => $age, 'nom_vaccin' => $vaccine],
                ['rappel_jours' => $recall, 'description' => 'Programme sanitaire de démonstration']
            );
        }

        foreach ([
            ['band' => $bandeChair, 'prefix' => 'CH', 'type' => $bandeChair->id_type_animal],
            ['band' => $bandePonte, 'prefix' => 'PO', 'type' => $bandePonte->id_type_animal],
        ] as $group) {
            for ($number = 1; $number <= 5; $number++) {
                Animal::firstOrCreate(
                    ['numero_animal' => strtoupper($spec['slug']).'-'.$group['prefix'].'-'.str_pad((string) $number, 3, '0', STR_PAD_LEFT)],
                    [
                        'id_type_animal' => $group['type'],
                        'id_bande' => $group['band']->id,
                        'age' => 30,
                        'poids' => $group['prefix'] === 'CH' ? 1.8 : 1.6,
                        'prix_dachat' => 500,
                        'prix_de_vente' => 3500,
                        'statut_animal' => $number === 5 ? 'vendu' : 'enelevage',
                    ]
                );
            }
        }

        $cashCurrency = CaisseDevise::firstOrCreate(
            ['code' => 'XAF'],
            ['symbole' => 'FCFA', 'nom' => 'Franc CFA']
        );
        $cash = CaisseCompte::firstOrCreate(
            ['nom' => 'Caisse principale'],
            [
                'type_account' => 'caisse',
                'nom_banque' => null,
                'initial_balance' => 2500000,
                'current_balance' => 2500000,
                'currency_id' => $cashCurrency->id,
            ]
        );
        $bank = CaisseCompte::firstOrCreate(
            ['nom' => 'Compte bancaire'],
            [
                'type_account' => 'banque',
                'nom_banque' => 'Afriland First Bank',
                'initial_balance' => 5000000,
                'current_balance' => 5000000,
                'currency_id' => $cashCurrency->id,
            ]
        );

        $categories = [];
        foreach ([
            'Alimentation' => 'Achats d’aliments pour les bandes',
            'Vétérinaire' => 'Soins et vaccinations',
            'Transport' => 'Livraisons et déplacements',
            'Électricité' => 'Énergie de la ferme',
            'Eau' => 'Consommation d’eau',
            'Loyer' => 'Loyer des bâtiments',
            'Salaires' => 'Rémunérations mensuelles du personnel',
        ] as $name => $description) {
            $categories[$name] = Categorie::firstOrCreate(
                ['nom' => $name],
                ['description' => $description, 'frequence' => 'mensuelle']
            );
        }

        $staff = [
            ['nom' => 'Amina - Responsable élevage', 'telephone' => '690000021', 'salaire' => 180000],
            ['nom' => 'Paul - Agent de ferme', 'telephone' => '690000022', 'salaire' => 125000],
            ['nom' => 'Mireille - Magasinière', 'telephone' => '690000023', 'salaire' => 145000],
        ];
        $employees = [];
        $payday = now()->startOfMonth()->subDay()->setTime(16, 0);
        foreach ($staff as $employeeData) {
            $employee = Salaries::firstOrCreate(
                ['nom' => $employeeData['nom'].' '.$spec['slug']],
                [
                    'adresse' => 'Ferme de démonstration',
                    'telephone' => $employeeData['telephone'],
                    'id_saver' => $actors['rh'] ?? $adminId,
                    'accumulateur' => 1,
                    'type_periode' => 'mois',
                    'salaire' => $employeeData['salaire'],
                    'date_debut_contrat' => $start->copy()->subYear(),
                    'date_dernier_paiement' => $payday,
                    'statut' => 1,
                ]
            );
            $employees[] = $employee;
        }

        $laborCost = BandeCoutType::firstOrCreate(['libelle' => "Main d'œuvre"], ['statut' => 1]);
        $energyCost = BandeCoutType::firstOrCreate(['libelle' => 'Électricité'], ['statut' => 1]);
        $buildings = [$batA, $batB];
        $bands = [$bandeChair, $bandePonte];

        for ($index = 0; $index < 30; $index++) {
            $day = $start->copy()->addDays($index);
            $date = $day->toDateString();

            if ($index % 4 === 0) {
                foreach ($buildings as $building) {
                    Nettoyage::firstOrCreate(
                        ['id_agent' => $agent->id, 'id_batiment' => $building->id, 'date' => $date],
                        [
                            'verif' => $index % 2 === 0,
                            'description' => 'Nettoyage et désinfection du bâtiment',
                        ]
                    );
                }
            }

            if (in_array($index, [4, 11, 18, 25], true)) {
                $band = $index % 2 === 0 ? $bandeChair : $bandePonte;
                $vaccine = $index % 2 === 0 ? 'Gumboro' : 'Newcastle';
                $appointmentAt = $day->copy()->setTime(10, 0);
                $alreadyRecorded = Seance_de_vaccination::query()
                    ->where('id_bande', $band->id)
                    ->where('vaccin_attribuer', $vaccine)
                    ->whereDate('created_at', $date)
                    ->exists();

                if (! $alreadyRecorded) {
                    Seance_de_vaccination::create([
                        'id_veterinaire' => $veterinaire->id,
                        'id_bande' => $band->id,
                        'vaccin_attribuer' => $vaccine,
                        'verification' => true,
                        'description' => 'Séance réalisée par le vétérinaire de la ferme',
                        'cout' => 25000,
                        'created_at' => $appointmentAt,
                        'updated_at' => $appointmentAt,
                    ]);
                }

                $this->recordDemoExpense(
                    $categories['Vétérinaire'],
                    $cash,
                    $actors['caissier'] ?? $adminId,
                    25000,
                    $appointmentAt,
                    'DEMO-VETO-'.$date,
                    'Intervention vétérinaire - '.$vaccine
                );
            }

            if ($index === 5) {
                $this->recordDemoExpense(
                    $categories['Loyer'],
                    $cash,
                    $actors['caissier'] ?? $adminId,
                    150000,
                    $day->copy()->setTime(15, 0),
                    'DEMO-LOYER-'.$date,
                    'Paiement du loyer mensuel'
                );
            } elseif ($index % 7 === 2) {
                $this->recordDemoExpense(
                    $categories['Transport'],
                    $cash,
                    $actors['caissier'] ?? $adminId,
                    15000,
                    $day->copy()->setTime(14, 0),
                    'DEMO-TRANSPORT-'.$date,
                    'Livraison des produits au marché'
                );
                $this->recordDemoExpense(
                    $categories['Eau'],
                    $cash,
                    $actors['caissier'] ?? $adminId,
                    8000,
                    $day->copy()->setTime(14, 30),
                    'DEMO-EAU-'.$date,
                    'Consommation d’eau de la semaine'
                );
            }

            if ($index === 20) {
                $this->recordDemoExpense(
                    $categories['Électricité'],
                    $cash,
                    $actors['caissier'] ?? $adminId,
                    45000,
                    $day->copy()->setTime(15, 0),
                    'DEMO-ELECTRICITE-'.$date,
                    'Facture d’électricité'
                );
            }

            if ($index % 7 === 3) {
                foreach ($bands as $position => $band) {
                    BandeCout::firstOrCreate(
                        [
                            'id_bande' => $band->id,
                            'id_type_cout' => $position === 0 ? $laborCost->id : $energyCost->id,
                            'date_debut' => $day->toDateString(),
                        ],
                        [
                            'montant' => $position === 0 ? 35000 : 12000,
                            'date_fin' => $day->copy()->addDays(6)->toDateString(),
                            'description' => 'Coût de fonctionnement de la bande',
                            'id_saver' => $actors['gestionnaire'] ?? $adminId,
                        ]
                    );
                }
            }

            if (in_array($index, [3, 10, 17, 24], true)) {
                $feed = $index % 2 === 0 ? $alimChair : $alimPonte;
                $quantity = 200;
                $purchaseDate = $date;
                $motif = 'Réassort de démonstration - '.$purchaseDate.' - '.$feed->id;
                $previousStock = (float) (Stock_matiere_premiere::query()
                    ->where('id_matiere_premiere', $feed->id)
                    ->orderByDesc('id')
                    ->value('nouveau_stock') ?? 0);
                $movement = Stock_matiere_premiere::firstOrCreate(
                    ['id_matiere_premiere' => $feed->id, 'motif' => $motif],
                    [
                        'type_mouvement' => 'approvisionnement',
                        'quantite' => $quantity,
                        'dernier_stock' => $previousStock,
                        'nouveau_stock' => (string) ($previousStock + $quantity),
                        'date_entree' => $day->copy()->setTime(8, 0),
                    ]
                );

                Achat::firstOrCreate(
                    ['id_matiere_premiere' => $feed->id, 'date' => $purchaseDate],
                    [
                        'id_stock_matiere_premiere' => $movement->id,
                        'prix_unitaire' => $feed->prix_unitaire,
                        'qte' => $quantity,
                        'prix_total' => $quantity * $feed->prix_unitaire,
                        'mode_de_paiement' => 'espece',
                        'id_fournisseur' => $fournisseur->id,
                        'unite_achat' => 'kg',
                    ]
                );
                $this->recordDemoExpense(
                    $categories['Alimentation'],
                    $cash,
                    $actors['caissier'] ?? $adminId,
                    $quantity * (float) $feed->prix_unitaire,
                    $day->copy()->setTime(8, 30),
                    'DEMO-ACHAT-'.$date.'-'.$feed->id,
                    'Achat d’aliment pour les bandes'
                );
            }
        }

        $this->seedAnnualBusinessHistory(
            $spec,
            $actors,
            $historicalBands,
            $bandePonte,
            $causeMaladie,
            $causeChaleur,
            $agent,
            $veterinaire,
            $buildings,
            $alimChair,
            $alimPonte,
            $cash,
            $categories,
            $laborCost,
            $energyCost,
            $employees,
            $client,
            $secondaryClients,
            $poulet,
            $oeufs,
            $fiente,
            $seedAnnualProductStock
        );

        $transferAt = $start->copy()->addDays(14)->setTime(12, 0);
        CaisseTransfert::firstOrCreate(
            ['description' => 'Alimentation de la caisse de démonstration'],
            [
                'from_account_id' => $bank->id,
                'to_account_id' => $cash->id,
                'amount' => 300000,
                'transfer_date' => $transferAt,
                'user_id' => $actors['comptable'] ?? $adminId,
            ]
        );
        $this->recordDemoCashTransaction($bank, $actors['comptable'] ?? $adminId, 'sortie', 300000, $transferAt, 'DEMO-TRANSFERT-SORTIE', 'Transfert vers la caisse');
        $this->recordDemoCashTransaction($cash, $actors['comptable'] ?? $adminId, 'entrée', 300000, $transferAt, 'DEMO-TRANSFERT-ENTREE', 'Transfert depuis la banque');

        Facture::query()
            ->where('matricule', 'like', 'FACT-'.strtoupper($spec['slug']).'-%')
            ->get()
            ->each(function (Facture $facture) use ($cash, $actors, $adminId) {
                $this->recordDemoCashTransaction(
                    $cash,
                    $actors['caissier'] ?? $adminId,
                    'entrée',
                    (float) $facture->total_ttc,
                    Carbon::parse($facture->date_facturation),
                    'DEMO-VENTE-'.$facture->matricule,
                    'Encaissement de la facture '.$facture->matricule
                );
            });

        foreach ([
            ['offset' => 6, 'quantity' => 60, 'status' => Commande::STATUT_LIVREE],
            ['offset' => 15, 'quantity' => 100, 'status' => Commande::STATUT_EN_PREPARATION],
            ['offset' => 24, 'quantity' => 80, 'status' => Commande::STATUT_PRET],
        ] as $position => $orderData) {
            $orderDate = $start->copy()->addDays($orderData['offset']);
            $reference = 'CMD-'.strtoupper(substr($spec['slug'], 0, 20)).'-'.str_pad((string) ($position + 1), 2, '0', STR_PAD_LEFT);
            $total = $orderData['quantity'] * (float) $oeufs->prix_unitaire;
            $order = Commande::firstOrCreate(
                ['matricule' => $reference],
                [
                    'id_client' => $client->id,
                    'statut' => $orderData['status'],
                    'total_ht' => $total,
                    'tva' => 0,
                    'total_ttc' => $total,
                    'id_saver' => $actors['client'] ?? $adminId,
                    'date_commande' => $orderDate->toDateString(),
                ]
            );
            LigneCommande::firstOrCreate(
                ['id_commande' => $order->id, 'id_produit' => $oeufs->id],
                [
                    'qte' => $orderData['quantity'],
                    'prix_unitaire' => $oeufs->prix_unitaire,
                    'total_ligne' => $total,
                ]
            );
        }

        foreach ([$cash, $bank] as $account) {
            $income = (float) CaisseTransaction::query()
                ->where('compte_id', $account->id)
                ->where('transaction_type', 'entrée')
                ->sum('montant');
            $expense = (float) CaisseTransaction::query()
                ->where('compte_id', $account->id)
                ->where('transaction_type', 'sortie')
                ->sum('montant');

            $account->current_balance = (int) round($account->initial_balance + $income - $expense);
            $account->save();
        }
    }

    /**
     * Complète les douze mois précédant les trente jours d'activité détaillée.
     *
     * @param  array<string, int>  $actors
     * @param  array<int, Bande>  $historicalBands
     * @param  array<int, Batiment>  $buildings
     * @param  array<int, Salaries>  $employees
     * @param  array<int, Client>  $secondaryClients
     */
    private function seedAnnualBusinessHistory(
        array $spec,
        array $actors,
        array $historicalBands,
        Bande $bandePonte,
        MortaliteCause $causeMaladie,
        MortaliteCause $causeChaleur,
        Agent $agent,
        Veterinaire $veterinaire,
        array $buildings,
        Matiere_premiere $alimChair,
        Matiere_premiere $alimPonte,
        CaisseCompte $cash,
        array $categories,
        BandeCoutType $laborCost,
        BandeCoutType $energyCost,
        array $employees,
        Client $client,
        array $secondaryClients,
        Produit $poulet,
        Produit $oeufs,
        Produit $fiente,
        bool $linkAnnualProductStock
    ): void {
        $feeds = [$alimChair, $alimPonte];
        $kilogramUnitId = (int) (Unite::where('abrege', 'kg')->value('id') ?? 1);
        $historyStart = now()->subMonths(12)->startOfMonth();

        foreach ($historicalBands as $cycle => $band) {
            for ($number = 1; $number <= 5; $number++) {
                Animal::firstOrCreate(
                    ['numero_animal' => strtoupper($spec['slug']).'-H'.($cycle + 1).'-'.str_pad((string) $number, 3, '0', STR_PAD_LEFT)],
                    [
                        'id_type_animal' => $band->id_type_animal,
                        'id_bande' => $band->id,
                        'age' => 56,
                        'poids' => 2.1,
                        'prix_dachat' => 500,
                        'prix_de_vente' => 3500,
                        'statut_animal' => $number === 5 ? 'vendu' : 'enelevage',
                    ]
                );
            }

            foreach ([14, 28, 42] as $vaccineAge) {
                $appointmentAt = $band->date_lancement_bande->copy()->addDays($vaccineAge)->setTime(10, 0);
                $vaccineName = match ($vaccineAge) {
                    14 => 'Gumboro',
                    28 => 'Newcastle',
                    default => 'Bronchite infectieuse',
                };
                $exists = Seance_de_vaccination::query()
                    ->where('id_bande', $band->id)
                    ->where('vaccin_attribuer', $vaccineName)
                    ->whereDate('created_at', $appointmentAt->toDateString())
                    ->exists();

                if (! $exists) {
                    Seance_de_vaccination::create([
                        'id_veterinaire' => $veterinaire->id,
                        'id_bande' => $band->id,
                        'vaccin_attribuer' => $vaccineName,
                        'verification' => true,
                        'description' => 'Protocole sanitaire du lot '.$band->nom,
                        'cout' => 30000,
                        'created_at' => $appointmentAt,
                        'updated_at' => $appointmentAt,
                    ]);
                }

                if (in_array($vaccineAge, [14, 42], true)) {
                    $this->recordDemoExpense(
                        $categories['Vétérinaire'],
                        $cash,
                        $actors['caissier'] ?? $actors['admin'],
                        30000,
                        $appointmentAt,
                        'DEMO-ANNUAL-VETO-'.$band->id.'-'.$vaccineAge,
                        'Vaccination '.$vaccineName.' - '.$band->nom
                    );
                }
            }
        }

        $programmes = [
            [$bandePonte->id_type_animal, 1, 'Marek', 0],
            [$bandePonte->id_type_animal, 90, 'Bronchite infectieuse', 90],
            [$bandePonte->id_type_animal, 180, 'Newcastle', 180],
            [$bandePonte->id_type_animal, 270, 'Gumboro', 180],
        ];
        foreach ($programmes as [$animalTypeId, $age, $vaccine, $recall]) {
            ProgrammeVaccination::firstOrCreate(
                ['id_type_animal' => $animalTypeId, 'age_jour' => $age, 'nom_vaccin' => $vaccine],
                ['rappel_jours' => $recall, 'description' => 'Rappel annuel du cheptel de ponte']
            );
        }

        for ($monthIndex = 0; $monthIndex < 12; $monthIndex++) {
            $month = $historyStart->copy()->addMonths($monthIndex);
            $monthKey = $month->format('Ym');
            $band = $historicalBands[min(5, intdiv($monthIndex, 2))];
            $feed = $feeds[$monthIndex % count($feeds)];
            $feedDate = $month->copy()->addDays(4);
            $eventDate = $month->copy()->addDays(8);
            $saleDate = $month->copy()->addDays(11);
            $orderDate = $month->copy()->addDays(16);
            $eggSaleDate = $month->copy()->addDays(23);

            Nutrition::firstOrCreate(
                ['id_bande' => $band->id, 'id_aliment' => $feed->id, 'date' => $eventDate->toDateString()],
                ['qte' => 240, 'id_unite' => $kilogramUnitId, 'nombre_de_passages' => 2]
            );
            Nutrition::firstOrCreate(
                ['id_bande' => $bandePonte->id, 'id_aliment' => $alimPonte->id, 'date' => $eventDate->toDateString()],
                ['qte' => 90, 'id_unite' => $kilogramUnitId, 'nombre_de_passages' => 2]
            );

            Mortalite::firstOrCreate(
                ['id_bande' => $band->id, 'date' => $month->copy()->addDays(18)->toDateString(), 'nombre_morts' => 8],
                [
                    'id_cause' => $monthIndex % 2 === 0 ? $causeMaladie->id : $causeChaleur->id,
                    'observation' => 'Contrôle sanitaire mensuel du lot',
                    'id_saver' => $actors['agent'] ?? $actors['admin'],
                ]
            );
            if ($monthIndex % 3 === 0) {
                Mortalite::firstOrCreate(
                    ['id_bande' => $bandePonte->id, 'date' => $month->copy()->addDays(20)->toDateString(), 'nombre_morts' => 3],
                    [
                        'id_cause' => $causeMaladie->id,
                        'observation' => 'Suivi mensuel du cheptel de ponte',
                        'id_saver' => $actors['agent'] ?? $actors['admin'],
                    ]
                );
            }

            foreach ([$month->copy()->addDays(3), $month->copy()->addDays(17)] as $cleaningDate) {
                foreach ($buildings as $building) {
                    Nettoyage::firstOrCreate(
                        [
                            'id_agent' => $agent->id,
                            'id_batiment' => $building->id,
                            'date' => $cleaningDate->toDateString(),
                        ],
                        [
                            'verif' => true,
                            'description' => 'Nettoyage planifié et désinfection préventive',
                        ]
                    );
                }
            }

            foreach ([$laborCost, $energyCost] as $costType) {
                BandeCout::firstOrCreate(
                    [
                        'id_bande' => $band->id,
                        'id_type_cout' => $costType->id,
                        'date_debut' => $month->copy()->addDays(1)->toDateString(),
                    ],
                    [
                        'montant' => $costType->id === $laborCost->id ? 85000 : 30000,
                        'date_fin' => $month->copy()->endOfMonth()->toDateString(),
                        'description' => 'Charges mensuelles du lot '.$band->nom,
                        'id_saver' => $actors['gestionnaire'] ?? $actors['admin'],
                    ]
                );
            }

            foreach ([2, 9, 16, 23] as $collectionDay) {
                $collectionDate = $month->copy()->addDays($collectionDay);
                Ramassage::firstOrCreate(
                    [
                        'id_bande' => $bandePonte->id,
                        'do_date' => $collectionDate->toDateString(),
                        'id_produit' => $oeufs->id,
                    ],
                    [
                        'id_prestataire' => $actors['agent'] ?? $actors['admin'],
                        'id_saver' => $actors['agent'] ?? $actors['admin'],
                        'ids_derive' => '[]',
                        'valeurs_derive' => '[]',
                        'commentaire' => 'Collecte hebdomadaire - '.$monthKey,
                        'statut' => 1,
                    ]
                );
            }
            Ramassage::firstOrCreate(
                [
                    'id_bande' => $band->id,
                    'do_date' => $month->copy()->addDays(24)->toDateString(),
                    'id_produit' => $fiente->id,
                ],
                [
                    'id_prestataire' => $actors['agent'] ?? $actors['admin'],
                    'id_saver' => $actors['agent'] ?? $actors['admin'],
                    'ids_derive' => '[]',
                    'valeurs_derive' => '[]',
                    'commentaire' => 'Valorisation mensuelle de la fiente',
                    'statut' => 1,
                ]
            );

            $this->recordDemoExpense(
                $categories['Alimentation'],
                $cash,
                $actors['caissier'] ?? $actors['admin'],
                600 * (float) $feed->prix_unitaire,
                $feedDate->setTime(8, 30),
                'DEMO-ANNUAL-FEED-'.$monthKey,
                'Réapprovisionnement mensuel en '.$feed->nom_produit
            );
            foreach ([
                ['Loyer', 150000, $month->copy()->addDays(1), 'Loyer mensuel des bâtiments'],
                ['Électricité', 45000, $month->copy()->addDays(20), 'Facture mensuelle d’électricité'],
                ['Transport', 30000, $month->copy()->addDays(10), 'Transport des produits vers les marchés'],
                ['Eau', 16000, $month->copy()->addDays(10), 'Approvisionnement en eau de la ferme'],
            ] as [$category, $amount, $expenseDate, $description]) {
                $this->recordDemoExpense(
                    $categories[$category],
                    $cash,
                    $actors['caissier'] ?? $actors['admin'],
                    $amount,
                    $expenseDate->setTime(15, 0),
                    'DEMO-ANNUAL-'.$category.'-'.$monthKey,
                    $description
                );
            }

            $this->recordDemoSaleInvoice(
                'ANNUAL-'.$spec['slug'].'-'.$monthKey.'-CH',
                $saleDate,
                $secondaryClients[$monthIndex % count($secondaryClients)],
                $poulet,
                $band,
                150,
                'animal',
                $actors['commercial'] ?? $actors['admin'],
                $cash,
                $linkAnnualProductStock
            );
            $this->recordDemoSaleInvoice(
                'ANNUAL-'.$spec['slug'].'-'.$monthKey.'-EGG',
                $eggSaleDate,
                $client,
                $oeufs,
                $bandePonte,
                2500,
                'oeuf',
                $actors['commercial'] ?? $actors['admin'],
                $cash,
                $linkAnnualProductStock
            );

            $orderQuantity = 400 + (($monthIndex % 4) * 100);
            $orderTotal = $orderQuantity * (float) $oeufs->prix_unitaire;
            $order = Commande::firstOrCreate(
                ['matricule' => 'CMD-ANNUAL-'.$spec['slug'].'-'.$monthKey],
                [
                    'id_client' => $client->id,
                    'statut' => [
                        Commande::STATUT_LIVREE,
                        Commande::STATUT_EN_PREPARATION,
                        Commande::STATUT_PRET,
                        Commande::STATUT_CONFIRMEE,
                    ][$monthIndex % 4],
                    'total_ht' => $orderTotal,
                    'tva' => 0,
                    'total_ttc' => $orderTotal,
                    'id_saver' => $actors['client'] ?? $actors['admin'],
                    'date_commande' => $orderDate->toDateString(),
                ]
            );
            LigneCommande::firstOrCreate(
                ['id_commande' => $order->id, 'id_produit' => $oeufs->id],
                [
                    'qte' => $orderQuantity,
                    'prix_unitaire' => $oeufs->prix_unitaire,
                    'total_ligne' => $orderTotal,
                ]
            );

            foreach ($employees as $employee) {
                $payday = $month->copy()->endOfMonth()->setTime(16, 0);
                PaiementSalaires::firstOrCreate(
                    ['id_salarie' => $employee->id, 'date_paiement' => $payday],
                    [
                        'type' => 'paiement_complet',
                        'telephone' => $employee->telephone,
                        'date_debut' => $month->copy()->startOfMonth(),
                        'date_fin' => $payday,
                        'montant' => $employee->salaire,
                        'description' => 'Paie de démonstration - '.$month->format('m/Y'),
                    ]
                );
                $this->recordDemoExpense(
                    $categories['Salaires'],
                    $cash,
                    $actors['caissier'] ?? $actors['admin'],
                    (float) $employee->salaire,
                    $payday,
                    'DEMO-ANNUAL-PAY-'.$employee->id.'-'.$monthKey,
                    'Salaire mensuel de '.$employee->nom
                );
            }
        }
    }

    private function recordDemoSaleInvoice(
        string $reference,
        Carbon $saleDate,
        Client $client,
        Produit $product,
        Bande $band,
        int $quantity,
        string $saleType,
        int $sellerId,
        CaisseCompte $cash,
        bool $linkStockMovement
    ): void {
        $total = $quantity * (float) $product->prix_unitaire;
        $invoice = Facture::firstOrCreate(
            ['matricule' => $reference],
            [
                'date_facturation' => $saleDate->toDateString(),
                'total_ht' => $total,
                'tva' => 0,
                'total_ttc' => $total,
                'id_client' => $client->id,
            ]
        );
        Vente::firstOrCreate(
            ['id_facture' => $invoice->id],
            ['id_saver' => $sellerId, 'date_vente' => $saleDate->toDateString()]
        );

        $lineValues = [
            'type_vente' => $saleType,
            'mode_paiement' => 'espece',
            'id_bande' => $band->id,
            'qte' => $quantity,
            'prix_unitaire' => $product->prix_unitaire,
            'total_ligne' => $total,
        ];
        if ($linkStockMovement) {
            $movement = $this->recordProductStockMovement(
                $product,
                'destockage',
                $quantity,
                $saleDate->copy()->setTime(11, 0)
            );
            $lineValues['Id_stock_produit'] = $movement->id;
        }

        $line = Ligne_facture::firstOrCreate(
            ['id_facture' => $invoice->id, 'id_produit' => $product->id],
            $lineValues
        );
        if ($linkStockMovement && ! $line->id_stock_produit) {
            $line->id_stock_produit = $lineValues['Id_stock_produit'];
            $line->save();
        }

        $this->recordDemoCashTransaction(
            $cash,
            $sellerId,
            'entrée',
            $total,
            $saleDate,
            'DEMO-ANNUAL-RECEIPT-'.$reference,
            'Encaissement de la facture '.$reference
        );
    }

    private function recordDemoExpense(
        Categorie $category,
        CaisseCompte $account,
        int $userId,
        float $amount,
        Carbon $at,
        string $reference,
        string $description
    ): void {
        $expense = Depense::firstOrCreate(
            ['motif_depense' => $reference],
            [
                'montant' => $amount,
                'fournisseur' => 'Fournisseur de démonstration',
                'description' => $description,
                'date_depense' => $at,
                'id_saver' => $userId,
                'id_categorie' => $category->id,
                'id_compte' => $account->id,
            ]
        );

        $this->recordDemoCashTransaction(
            $account,
            $userId,
            'sortie',
            $amount,
            $at,
            'DEMO-DEPENSE-'.$reference,
            $description,
            $expense->id
        );
    }

    private function recordDemoCashTransaction(
        CaisseCompte $account,
        int $userId,
        string $type,
        float $amount,
        Carbon $at,
        string $reference,
        string $description,
        ?int $expenseId = null
    ): void {
        CaisseTransaction::firstOrCreate(
            ['reference_number' => $reference],
            [
                'compte_id' => $account->id,
                'transaction_type' => $type,
                'montant' => $amount,
                'transaction_date' => $at->toDateString(),
                'description' => $description,
                'user_id' => $userId,
                'id_depense' => $expenseId,
            ]
        );
    }

    private function recordProductStockMovement(
        Produit $product,
        string $type,
        float $quantity,
        Carbon $at
    ): Stock_produit {
        $dateColumn = $type === 'approvisionnement' ? 'date_entree' : 'date_sortie';
        $existing = Stock_produit::query()
            ->where('id_produit', $product->id)
            ->where('type_mouvement', $type)
            ->whereDate($dateColumn, $at->toDateString())
            ->where('qte', $quantity)
            ->first();

        if ($existing) {
            return $existing;
        }

        $previousStock = $this->productStockLevel($product);
        $newStock = $type === 'approvisionnement'
            ? $previousStock + $quantity
            : $previousStock - $quantity;
        $this->productStockLevels[$product->id] = $newStock;

        return Stock_produit::create([
            'id_produit' => $product->id,
            'type_mouvement' => $type,
            'qte' => $quantity,
            'dernier_stock' => $previousStock,
            'nouveau_stock' => $newStock,
            'date_entree' => $type === 'approvisionnement' ? $at : null,
            'date_sortie' => $type === 'destockage' ? $at : null,
        ]);
    }

    /**
     * Lit une seule fois le dernier stock connu d'un produit, puis le suit en
     * mémoire pendant tout le provisioning.
     */
    private function productStockLevel(Produit $product): float
    {
        if (! array_key_exists($product->id, $this->productStockLevels)) {
            $this->productStockLevels[$product->id] = (float) (Stock_produit::query()
                ->where('id_produit', $product->id)
                ->orderByDesc('id')
                ->value('nouveau_stock') ?? 0);
        }

        return $this->productStockLevels[$product->id];
    }

    private function seedFoodSettings($typeChair, $typePonte, $alimChair, $alimPonte, $kg): void
    {
        $plans = [
            $typeChair->id => [
                ['aliment' => $alimChair->id, 'age_min' => 0, 'age_max' => 14, 'qte' => 0.03, 'freq' => 2],
                ['aliment' => $alimChair->id, 'age_min' => 15, 'age_max' => 28, 'qte' => 0.08, 'freq' => 2],
                ['aliment' => $alimChair->id, 'age_min' => 29, 'age_max' => 70, 'qte' => 0.12, 'freq' => 2],
            ],
            $typePonte->id => [
                ['aliment' => $alimPonte->id, 'age_min' => 0, 'age_max' => 30, 'qte' => 0.06, 'freq' => 2],
                ['aliment' => $alimPonte->id, 'age_min' => 31, 'age_max' => 120, 'qte' => 0.11, 'freq' => 2],
                ['aliment' => $alimPonte->id, 'age_min' => 121, 'age_max' => 720, 'qte' => 0.12, 'freq' => 2],
            ],
        ];

        foreach ($plans as $typeAnimalId => $versions) {
            foreach ($versions as $plan) {
                $setting = Food_Setting::firstOrCreate([
                    'id_type_animal' => $typeAnimalId,
                    'aliment' => (string) $plan['aliment'],
                ]);

                FoodSettingVersion::firstOrCreate(
                    [
                        'food_setting_id' => $setting->id,
                        'age_min' => $plan['age_min'],
                        'age_max' => $plan['age_max'],
                    ],
                    [
                        'id_unite' => $kg->id,
                        'quantite_aliment' => $plan['qte'],
                        'frequency' => $plan['freq'],
                        'period' => 'day',
                        'activated_at' => now(),
                        'ended_at' => null,
                    ]
                );
            }
        }
    }
}
