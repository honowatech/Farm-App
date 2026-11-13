<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Injecte 30 jours d'activité réaliste sur l'ensemble des fonctionnalités
 * accessibles aux profils métier (élevage, production, achats, commerce,
 * finances, RH), sans supprimer les données existantes.
 *
 * Le seeder est idempotent : chaque enregistrement est identifié par une clé
 * naturelle (bande + date, référence, matricule, motif…) et n'est créé qu'une
 * fois. Il peut donc être rejoué sans dupliquer les données ni les mouvements
 * de stock.
 */
class MonthActivitySeeder extends Seeder
{
    private Carbon $start;

    private Carbon $today;

    /** @var array<string, int> */
    private array $users = [];

    private int $bandeChair = 0;

    private int $bandePonte = 0;

    private int $caisseId = 0;

    private int $banqueId = 0;

    /** @var array<string, int> */
    private array $categories = [];

    /** @var array<int, int> */
    private array $mpPrices = [];

    public function run(): void
    {
        $this->start = now()->subDays(30)->startOfDay();
        $this->today = now()->startOfDay();

        $this->resolveContext();

        if ($this->bandeChair === 0 || $this->bandePonte === 0) {
            $this->command?->warn('MonthActivitySeeder : bandes actives introuvables, seeder ignoré.');

            return;
        }

        $this->seedFoodSettings();
        $this->seedReferentials();
        $this->seedProgrammes();

        $index = 0;
        for ($day = $this->start->copy(); $day->lte($this->today); $day->addDay(), $index++) {
            $this->seedDay($day->copy(), $index);
        }

        $this->refreshBalances();
    }

    // ------------------------------------------------------------------
    // Contexte
    // ------------------------------------------------------------------

    private function resolveContext(): void
    {
        $emails = [
            'admin' => 'admin@farmapp.test',
            'gestionnaire' => 'gestionnaire@farmapp.test',
            'agent' => 'agent@farmapp.test',
            'veterinaire' => 'veterinaire@farmapp.test',
            'magasinier' => 'magasinier@farmapp.test',
            'commercial' => 'commercial@farmapp.test',
            'comptable' => 'comptable@farmapp.test',
            'caissier' => 'caissier@farmapp.test',
            'rh' => 'rh@farmapp.test',
            'client' => 'client@farmapp.test',
        ];

        $fallback = (int) (DB::table('users')->orderBy('id')->value('id') ?? 1);

        foreach ($emails as $role => $email) {
            $this->users[$role] = (int) (DB::table('users')->where('email', $email)->value('id') ?? $fallback);
        }

        $chair = DB::table('bandes')
            ->where('statut_bande', 'encours')
            ->where('id_type_animal', 8)
            ->orderByDesc('id')
            ->first();

        $pondeuse = DB::table('bandes')
            ->where('statut_bande', 'encours')
            ->where('id_type_animal', 9)
            ->orderByDesc('id')
            ->first();

        $this->bandeChair = (int) ($chair->id ?? 0);
        $this->bandePonte = (int) ($pondeuse->id ?? 0);
    }

    // ------------------------------------------------------------------
    // Référentiels et paramétrages
    // ------------------------------------------------------------------

    /**
     * Crée les plans alimentaires manquants pour les poulets de chair (type 8)
     * et les pondeuses (type 9).
     */
    private function seedFoodSettings(): void
    {
        $plans = [
            8 => [
                ['aliment' => 6, 'age_min' => 0, 'age_max' => 14, 'qte' => 0.03, 'freq' => 2],
                ['aliment' => 7, 'age_min' => 15, 'age_max' => 28, 'qte' => 0.08, 'freq' => 2],
                ['aliment' => 8, 'age_min' => 29, 'age_max' => 70, 'qte' => 0.12, 'freq' => 2],
            ],
            9 => [
                ['aliment' => 6, 'age_min' => 0, 'age_max' => 30, 'qte' => 0.06, 'freq' => 2],
                ['aliment' => 7, 'age_min' => 31, 'age_max' => 120, 'qte' => 0.11, 'freq' => 2],
                ['aliment' => 8, 'age_min' => 121, 'age_max' => 720, 'qte' => 0.12, 'freq' => 2],
            ],
        ];

        foreach ($plans as $typeAnimal => $versions) {
            foreach ($versions as $plan) {
                $settingId = DB::table('foods_setting')
                    ->where('id_type_animal', $typeAnimal)
                    ->where('aliment', (string) $plan['aliment'])
                    ->value('id');

                if (! $settingId) {
                    $settingId = DB::table('foods_setting')->insertGetId([
                        'id_type_animal' => $typeAnimal,
                        'aliment' => (string) $plan['aliment'],
                        'created_at' => now()->subYear(),
                        'updated_at' => now()->subYear(),
                    ]);
                }

                $versionExists = DB::table('food_setting_versions')
                    ->where('food_setting_id', $settingId)
                    ->where('age_min', $plan['age_min'])
                    ->where('age_max', $plan['age_max'])
                    ->exists();

                if (! $versionExists) {
                    DB::table('food_setting_versions')->insert([
                        'food_setting_id' => $settingId,
                        'id_unite' => 1,
                        'age_min' => $plan['age_min'],
                        'age_max' => $plan['age_max'],
                        'quantite_aliment' => $plan['qte'],
                        'frequency' => $plan['freq'],
                        'period' => 'day',
                        'activated_at' => now()->subYear(),
                        'ended_at' => null,
                        'created_at' => now()->subYear(),
                        'updated_at' => now()->subYear(),
                    ]);
                }
            }
        }

        $this->mpPrices = DB::table('matiere_premieres')->pluck('prix_unitaire', 'id')->map(fn ($p) => (int) $p)->all();
    }

    /**
     * Complète les programmes vaccinaux de référence pour les poulets de chair
     * (type 8) et les pondeuses (type 9).
     */
    private function seedProgrammes(): void
    {
        $programmes = [
            8 => [
                ['age' => 7, 'vaccin' => 'Bronchite infectieuse', 'rappel' => null, 'description' => 'Primo-vaccination respiratoire'],
                ['age' => 14, 'vaccin' => 'Gumboro', 'rappel' => 21, 'description' => 'Protection contre la bursite infectieuse'],
                ['age' => 21, 'vaccin' => 'Newcastle', 'rappel' => 42, 'description' => 'Vaccination contre la maladie de Newcastle'],
            ],
            9 => [
                ['age' => 1, 'vaccin' => 'Marek', 'rappel' => null, 'description' => 'Vaccination au couvoir'],
                ['age' => 14, 'vaccin' => 'Gumboro', 'rappel' => 28, 'description' => 'Protection contre la bursite infectieuse'],
                ['age' => 30, 'vaccin' => 'Newcastle', 'rappel' => 60, 'description' => 'Vaccination contre la maladie de Newcastle'],
                ['age' => 90, 'vaccin' => 'Bronchite infectieuse', 'rappel' => 120, 'description' => 'Rappel respiratoire avant ponte'],
            ],
        ];

        foreach ($programmes as $typeAnimal => $lignes) {
            foreach ($lignes as $ligne) {
                $existant = DB::table('programmes_vaccination')
                    ->where('id_type_animal', $typeAnimal)
                    ->where('age_jour', $ligne['age'])
                    ->where('nom_vaccin', $ligne['vaccin'])
                    ->exists();

                if ($existant) {
                    continue;
                }

                DB::table('programmes_vaccination')->insert([
                    'id_type_animal' => $typeAnimal,
                    'age_jour' => $ligne['age'],
                    'nom_vaccin' => $ligne['vaccin'],
                    'rappel_jours' => $ligne['rappel'],
                    'description' => $ligne['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function seedReferentials(): void    {
        // Devise et comptes de caisse
        if (! DB::table('gestion_caisse_devises')->where('code', 'XAF')->exists()) {
            DB::table('gestion_caisse_devises')->insert([
                'code' => 'XAF',
                'symbole' => 'FCFA',
                'nom' => 'Franc CFA',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $deviseId = (int) DB::table('gestion_caisse_devises')->where('code', 'XAF')->value('id');

        $this->caisseId = (int) DB::table('gestion_caisse_comptes')->where('nom', 'Caisse principale')->value('id');
        if ($this->caisseId === 0) {
            $this->caisseId = DB::table('gestion_caisse_comptes')->insertGetId([
                'nom' => 'Caisse principale',
                'type_account' => 'caisse',
                'nom_banque' => null,
                'numero_compte' => null,
                'initial_balance' => 1500000,
                'current_balance' => 1500000,
                'currency_id' => $deviseId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->banqueId = (int) DB::table('gestion_caisse_comptes')->where('nom', 'Compte bancaire')->value('id');
        if ($this->banqueId === 0) {
            $this->banqueId = DB::table('gestion_caisse_comptes')->insertGetId([
                'nom' => 'Compte bancaire',
                'type_account' => 'banque',
                'nom_banque' => 'Afriland First Bank',
                'numero_compte' => '10005-000123456789',
                'initial_balance' => 5000000,
                'current_balance' => 5000000,
                'currency_id' => $deviseId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Catégories de dépenses utiles aux profils financiers
        $categories = [
            'Aliment' => ['reguliere', 'hebdomadaire', "Achats d'aliment"],
            'Vétérinaire' => ['ponctuel', null, 'Soins et vaccinations'],
            'Transport' => ['reguliere', 'hebdomadaire', 'Livraisons et déplacements'],
            'Électricité' => ['reguliere', 'mensuelle', 'Facture d\'électricité'],
            'Eau' => ['reguliere', 'mensuelle', 'Facture d\'eau'],
        ];

        foreach ($categories as $nom => [$type, $frequence, $description]) {
            $id = DB::table('depense_categorie_depenses')->where('nom', $nom)->value('id');
            if (! $id) {
                DB::table('depense_categorie_depenses')->insert([
                    'nom' => $nom,
                    'type' => $type,
                    'frequence' => $frequence,
                    'description' => $description,
                    'prix_unitaire' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->categories = DB::table('depense_categorie_depenses')->pluck('id', 'nom')->map(fn ($id) => (int) $id)->all();

        // Prix de vente manquants (œufs, fientes)
        DB::table('produits')->where('id', 4)->whereNull('prix_unitaire')->update(['prix_unitaire' => 100]);
        DB::table('produits')->where('id', 2)->whereNull('prix_unitaire')->update(['prix_unitaire' => 1500]);
        DB::table('produits')->where('id', 5)->whereNull('prix_unitaire')->update(['prix_unitaire' => 1500]);

        // Stock initial de poulets prêts à la vente et de fientes
        if (! DB::table('stock_produits')->where('id_produit', 17)->exists()) {
            $this->prodMovement(17, 'approvisionnement', 250, $this->start->copy()->setTime(6, 0));
        }
        if (! DB::table('stock_produits')->where('id_produit', 2)->exists()) {
            $this->prodMovement(2, 'approvisionnement', 120, $this->start->copy()->setTime(6, 5));
        }
    }

    // ------------------------------------------------------------------
    // Activité quotidienne
    // ------------------------------------------------------------------

    private function seedDay(Carbon $day, int $index): void
    {
        $date = $day->toDateString();
        $dow = (int) $day->dayOfWeek;

        // Achats d'aliment : chaque lundi + premier jour
        if ($dow === 1 || $index === 0) {
            $this->seedPurchase($day);
        }

        $this->seedNutrition($day);
        $this->seedRamassage($day, $index);

        if ($index % 3 === 0) {
            $this->seedNettoyage($day);
        }

        if ($index % 4 === 0) {
            $this->seedMortalite($day, $index);
        }

        if (in_array($index, [3, 10, 17, 24], true)) {
            $this->seedVaccination($day, $index);
        }

        if ($index >= 3 && $index % 3 === 0) {
            $this->seedSale($day, $index);
        }

        $this->seedExpenses($day, $index);

        if ($dow === 3) {
            $this->seedBandeCouts($day, $index);
        }

        if ($index === 25) {
            $this->seedPaies($day);
        }

        if ($index === 12) {
            $this->seedTransfert($day);
        }
    }

    private function seedPurchase(Carbon $day): void
    {
        $date = $day->toDateString();
        $quantites = [6 => 5, 7 => 6, 8 => 8];

        foreach ($quantites as $matiereId => $sacs) {
            $prixKg = $this->mpPrices[$matiereId] ?? 400;
            $prixSac = $prixKg * 50;
            $existant = DB::table('achats')
                ->where('id_matiere_premiere', $matiereId)
                ->where('date', $date)
                ->exists();

            if ($existant) {
                continue;
            }

            $total = $sacs * $prixSac;

            DB::table('achats')->insert([
                'id_matiere_premiere' => $matiereId,
                'prix_unitaire' => $prixSac,
                'qte' => $sacs,
                'prix_total' => $total,
                'mode_de_paiement' => 'espece',
                'id_fournisseur' => 5,
                'date' => $date,
                'unite_achat' => 'sac',
                'created_at' => $day->copy()->setTime(8, 0),
                'updated_at' => $day->copy()->setTime(8, 0),
            ]);

            $this->mpMovement(
                $matiereId,
                'approvisionnement',
                $sacs * 50,
                $day->copy()->setTime(8, 30),
                'Achat fournisseur - '.$date
            );

            $this->recordDepense(
                $day->copy()->setTime(9, 0),
                $total,
                'Aliment',
                'Achat aliment - '.$date,
                "Achat de {$sacs} sacs de 50 kg",
                'DEP-ACHAT-'.$date.'-'.$matiereId
            );
        }
    }

    private function seedNutrition(Carbon $day): void
    {
        $date = $day->toDateString();

        $bandes = [
            [
                'id' => $this->bandeChair,
                'aliment' => $this->alimentForChair($day),
                'qte' => $this->nutritionQte($this->bandeChair, $day, 0.10),
            ],
            [
                'id' => $this->bandePonte,
                'aliment' => $this->alimentForPonte($day),
                'qte' => $this->nutritionQte($this->bandePonte, $day, 0.11),
            ],
        ];

        foreach ($bandes as $bande) {
            $aliment = (int) $bande['aliment'];
            $qte = max(1, round((float) $bande['qte'], 2));

            $alreadySeeded = DB::table('nutritions')
                ->where('id_bande', $bande['id'])
                ->where('date', $date)
                ->exists();

            if ($alreadySeeded) {
                continue;
            }

            DB::table('nutritions')->insert([
                'id_bande' => $bande['id'],
                'id_aliment' => $aliment,
                'qte' => $qte,
                'description' => 'Nutrition du jour',
                'date' => $date,
                'id_unite' => 1,
                'nombre_de_passages' => 1,
                'derniere_nutrition' => $day->copy()->setTime(7, 0),
                'compteur_nutrition' => 1,
                'prochaine_nutrition' => $day->copy()->addDay()->setTime(7, 0),
                'statut_nutrition' => 1,
                'created_at' => $day->copy()->setTime(7, 0),
                'updated_at' => $day->copy()->setTime(7, 0),
            ]);

            // Garantit un stock suffisant avant de sortir l'aliment
            if ($this->mpStock($aliment) < $qte) {
                $this->mpMovement(
                    $aliment,
                    'approvisionnement',
                    ceil(($qte - $this->mpStock($aliment)) / 50) * 50,
                    $day->copy()->setTime(5, 30),
                    'Réapprovisionnement urgent - '.$date
                );
            }

            $nomBande = DB::table('bandes')->where('id', $bande['id'])->value('nom');
            $this->mpMovement(
                $aliment,
                'destockage',
                $qte,
                $day->copy()->setTime(7, 5),
                'Nutrition des animaux (Bande: '.$nomBande.') - '.$date
            );
        }
    }

    private function seedRamassage(Carbon $day, int $index): void
    {
        $date = $day->toDateString();

        // Œufs : ramassage quotidien sur la bande de pondeuses
        $this->recordRamassage(
            $day,
            $this->bandePonte,
            4,
            [3, 4],
            [100 + (($index * 7) % 40), 70 + (($index * 5) % 30)],
            'Ramassage des œufs'
        );

        // Fientes : ramassage hebdomadaire
        if ($index % 7 === 0) {
            $this->recordRamassage(
                $day,
                $this->bandeChair,
                2,
                [1, 8],
                [4, 3],
                'Ramassage des fientes'
            );
        }
    }

    private function recordRamassage(Carbon $day, int $bandeId, int $produitId, array $deriveIds, array $valeurs, string $commentaire): void
    {
        $date = $day->toDateString();
        $moment = $day->copy()->setTime(8, 0);

        $existant = DB::table('ramassages')
            ->where('id_bande', $bandeId)
            ->where('id_produit', $produitId)
            ->whereDate('to_do_date', $date)
            ->exists();

        if ($existant) {
            return;
        }

        DB::table('ramassages')->insert([
            'id_bande' => $bandeId,
            'ids_derive' => json_encode(array_map('strval', $deriveIds)),
            'valeurs_derive' => json_encode(array_map('strval', $valeurs)),
            'id_prestataire' => $this->users['agent'],
            'id_saver' => $this->users['agent'],
            'to_do_date' => $moment,
            'do_date' => $moment,
            'commentaire' => $commentaire,
            'statut' => 1,
            'id_produit' => $produitId,
            'created_at' => $moment,
            'updated_at' => $moment,
        ]);

        foreach ($deriveIds as $position => $deriveId) {
            $this->prodMovement(
                $produitId,
                'approvisionnement',
                (float) $valeurs[$position],
                $moment,
                (int) $deriveId
            );
        }
    }

    private function seedNettoyage(Carbon $day): void
    {
        $date = $day->toDateString();
        $batiments = DB::table('bandes')
            ->whereIn('id', [$this->bandeChair, $this->bandePonte])
            ->pluck('id_batiment')
            ->unique();

        foreach ($batiments as $batimentId) {
            if (DB::table('nettoyages')->where('id_batiment', $batimentId)->where('date', $date)->exists()) {
                continue;
            }

            DB::table('nettoyages')->insert([
                'id_agent' => json_encode([1, 2]),
                'id_batiment' => $batimentId,
                'date' => $date,
                'verif' => $day->day % 2,
                'description' => 'Nettoyage et désinfection du bâtiment',
                'created_at' => $day->copy()->setTime(17, 0),
                'updated_at' => $day->copy()->setTime(17, 0),
            ]);
        }
    }

    private function seedMortalite(Carbon $day, int $index): void
    {
        $date = $day->toDateString();

        foreach ([$this->bandeChair, $this->bandePonte] as $position => $bandeId) {
            if (DB::table('mortalites')->where('id_bande', $bandeId)->where('date', $date)->exists()) {
                continue;
            }

            $morts = max(1, 1 + (($index + $position * 2) % 4));

            DB::table('mortalites')->insert([
                'id_bande' => $bandeId,
                'date' => $date,
                'nombre_morts' => $morts,
                'id_cause' => 1 + (($index + $position) % 4),
                'observation' => 'Mortalité constatée lors de la tournée du matin',
                'id_saver' => $this->users['agent'],
                'created_at' => $day->copy()->setTime(6, 30),
                'updated_at' => $day->copy()->setTime(6, 30),
            ]);
        }
    }

    private function seedVaccination(Carbon $day, int $index): void
    {
        $bandeId = $index % 2 === 0 ? $this->bandePonte : $this->bandeChair;
        $typeAnimal = DB::table('bandes')->where('id', $bandeId)->value('id_type_animal');

        $programme = DB::table('programmes_vaccination')
            ->where('id_type_animal', $typeAnimal)
            ->orderBy('age_jour')
            ->get();

        $vaccin = $programme->isNotEmpty()
            ? $programme[$index % $programme->count()]->nom_vaccin
            : 'Newcastle';

        $moment = $day->copy()->setTime(10, 0);

        $existant = DB::table('seance_de_vaccination')
            ->where('id_bande', $bandeId)
            ->where('vaccin_attribuer', $vaccin)
            ->whereDate('created_at', $day->toDateString())
            ->exists();

        if ($existant) {
            return;
        }

        DB::table('seance_de_vaccination')->insert([
            'id_veterinaire' => 5,
            'id_bande' => $bandeId,
            'vaccin_attribuer' => $vaccin,
            'verification' => 1,
            'description' => 'Séance de vaccination réalisée par le vétérinaire',
            'cout' => 25000,
            'created_at' => $moment,
            'updated_at' => $moment,
        ]);

        $this->recordDepense(
            $moment,
            25000,
            'Vétérinaire',
            'Vaccination - '.$day->toDateString(),
            'Intervention vétérinaire ('.$vaccin.')',
            'DEP-VETO-'.$day->toDateString()
        );
    }

    private function seedSale(Carbon $day, int $index): void
    {
        $date = $day->toDateString();
        $famille = $index % 9;

        [$produitId, $qte, $typeVente] = match ($famille) {
            0 => [17, 20, 'animal'],
            3 => [4, 90, 'oeuf'],
            default => [2, 5, 'fiante'],
        };

        if ($this->productStock($produitId) < $qte) {
            return;
        }

        $prix = (float) (DB::table('produits')->where('id', $produitId)->value('prix_unitaire') ?? 0);
        if ($prix <= 0) {
            return;
        }

        $sequence = intdiv($index, 3);
        $matricule = 'FAC-DEMO-'.$day->format('Ymd').'-'.str_pad((string) $sequence, 2, '0', STR_PAD_LEFT);

        if (DB::table('factures')->where('matricule', $matricule)->exists()) {
            return;
        }

        $total = round($prix * $qte, 2);
        $clientId = $sequence % 2 === 0 ? 9 : 10;
        $moment = $day->copy()->setTime(11, 0);

        $factureId = DB::table('factures')->insertGetId([
            'date_facturation' => $moment,
            'total_ht' => $total,
            'tva' => 0,
            'total_ttc' => $total,
            'matricule' => $matricule,
            'id_client' => $clientId,
            'created_at' => $moment,
            'updated_at' => $moment,
        ]);

        DB::table('ventes')->insert([
            'id_facture' => $factureId,
            'id_saver' => $this->users['commercial'],
            'date_vente' => $date,
            'created_at' => $moment,
            'updated_at' => $moment,
        ]);

        $mouvementId = $this->prodMovement($produitId, 'destockage', $qte, $moment);

        DB::table('ligne_factures')->insert([
            'id_facture' => $factureId,
            'id_produit' => $produitId,
            'type_vente' => $typeVente,
            'Id_stock_produit' => $mouvementId,
            'id_bande' => $typeVente === 'animal' ? $this->bandeChair : null,
            'qte' => $qte,
            'prix_unitaire' => $prix,
            'total_ligne' => $total,
            'mode_paiement' => $sequence % 2 === 0 ? 'espèces' : 'mobile money',
            'created_at' => $moment,
            'updated_at' => $moment,
        ]);

        if ($sequence % 2 === 0) {
            $this->caisseEntry(
                'entrée',
                $total,
                $moment,
                'VTE-DEMO-'.$day->format('Ymd').'-'.$sequence,
                'Encaissement facture '.$matricule
            );
        } else {
            $reference = 'PAY-DEMO-'.$day->format('Ymd').'-'.$sequence;

            if (! DB::table('payment_transactions')->where('external_reference', $reference)->exists()) {
                DB::table('payment_transactions')->insert([
                    'payable_type' => 'App\\Models\\Facture',
                    'payable_id' => $factureId,
                    'amount' => (int) $total,
                    'phone_number' => '69000000'.$sequence,
                    'external_reference' => $reference,
                    'gateway_reference' => 'MAMONI-'.$reference,
                    'provider_slug' => 'mamoni',
                    'status' => $sequence % 4 === 1 ? 'paid' : 'pending',
                    'client_name' => 'Client Démo',
                    'receipt_url' => null,
                    'response' => null,
                    'created_at' => $moment,
                    'updated_at' => $moment,
                ]);
            }
        }
    }

    private function seedExpenses(Carbon $day, int $index): void
    {
        $date = $day->toDateString();

        if ((int) $day->dayOfWeek === 2) {
            $this->recordDepense(
                $day->copy()->setTime(14, 0),
                15000,
                'Transport',
                'Transport - '.$date,
                'Livraison des produits au marché',
                'DEP-TRANSPORT-'.$date
            );

            $this->recordDepense(
                $day->copy()->setTime(14, 30),
                8000,
                'Eau',
                'Eau - '.$date,
                'Consommation d\'eau de la semaine',
                'DEP-EAU-'.$date
            );
        }

        if ($index === 5) {
            $this->recordDepense(
                $day->copy()->setTime(15, 0),
                150000,
                'Loyer',
                'Loyer - '.$date,
                'Paiement du loyer mensuel',
                'DEP-LOYER-'.$date
            );
        }

        if ($index === 20) {
            $this->recordDepense(
                $day->copy()->setTime(15, 0),
                45000,
                'Électricité',
                'Électricité - '.$date,
                'Facture d\'électricité',
                'DEP-ELEC-'.$date
            );
        }
    }

    private function seedBandeCouts(Carbon $day, int $index): void
    {
        $bandes = [
            $this->bandeChair => [50000, 20000, 10000],
            $this->bandePonte => [40000, 15000, 10000],
        ];

        foreach ($bandes as $bandeId => $montants) {
            foreach ([1 => $montants[0], 2 => $montants[1], 3 => $montants[2]] as $typeId => $montant) {
                $description = 'Coût de la semaine du '.$day->toDateString();

                $existant = DB::table('bande_couts')
                    ->where('id_bande', $bandeId)
                    ->where('id_type_cout', $typeId)
                    ->where('description', $description)
                    ->exists();

                if ($existant) {
                    continue;
                }

                DB::table('bande_couts')->insert([
                    'id_bande' => $bandeId,
                    'id_type_cout' => $typeId,
                    'montant' => $montant,
                    'date_debut' => $day->copy()->startOfWeek()->toDateString(),
                    'date_fin' => $day->copy()->endOfWeek()->toDateString(),
                    'description' => $description,
                    'id_saver' => $this->users['comptable'],
                    'created_at' => $day->copy()->setTime(16, 0),
                    'updated_at' => $day->copy()->setTime(16, 0),
                ]);
            }
        }
    }

    private function seedPaies(Carbon $day): void
    {
        $salaries = DB::table('salaries')->where('statut', 1)->get();
        $total = 0;

        foreach ($salaries as $salarie) {
            $paiementDate = $day->copy()->setTime(16, 0);
            $montant = (float) ($salarie->salaire ?? 0);

            if ($montant <= 0) {
                continue;
            }

            $existant = DB::table('paiement_salaires')
                ->where('id_salarie', $salarie->id)
                ->whereDate('date_paiement', $day->toDateString())
                ->exists();

            if ($existant) {
                continue;
            }

            DB::table('paiement_salaires')->insert([
                'type' => 'paiement_complet',
                'date_paiement' => $paiementDate,
                'telephone' => $salarie->telephone,
                'id_salarie' => $salarie->id,
                'date_debut' => $day->copy()->startOfMonth(),
                'date_fin' => $day->copy()->endOfMonth(),
                'montant' => $montant,
                'description' => 'Paie de démonstration - '.$day->translatedFormat('F Y'),
                'created_at' => $paiementDate,
                'updated_at' => $paiementDate,
            ]);

            DB::table('salaries')->where('id', $salarie->id)->update([
                'date_dernier_paiement' => $paiementDate,
                'updated_at' => now(),
            ]);

            $total += $montant;
        }

        if ($total > 0) {
            $this->caisseEntry(
                'sortie',
                $total,
                $day->copy()->setTime(16, 30),
                'PAI-DEMO-'.$day->format('Ymd'),
                'Paiement des salaires'
            );
        }
    }

    private function seedTransfert(Carbon $day): void
    {
        $moment = $day->copy()->setTime(12, 0);
        $description = 'Alimentation de la caisse - '.$day->toDateString();

        if (DB::table('gestion_caisse_transferts')->where('description', $description)->exists()) {
            return;
        }

        DB::table('gestion_caisse_transferts')->insert([
            'amount' => 300000,
            'transfer_date' => $moment,
            'description' => $description,
            'from_account_id' => $this->banqueId,
            'to_account_id' => $this->caisseId,
            'user_id' => $this->users['comptable'],
            'created_at' => $moment,
            'updated_at' => $moment,
        ]);

        $this->caisseEntry('sortie', 300000, $moment, 'TRF-OUT-'.$day->format('Ymd'), 'Transfert vers la caisse', $this->banqueId);
        $this->caisseEntry('entrée', 300000, $moment, 'TRF-IN-'.$day->format('Ymd'), 'Transfert depuis la banque', $this->caisseId);
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    private function alimentForChair(Carbon $day): int
    {
        $age = $this->ageOf($this->bandeChair, $day);

        return match (true) {
            $age <= 14 => 6,
            $age <= 28 => 7,
            default => 8,
        };
    }

    private function alimentForPonte(Carbon $day): int
    {
        $age = $this->ageOf($this->bandePonte, $day);

        return $age <= 30 ? 6 : 7;
    }

    private function ageOf(int $bandeId, Carbon $day): int
    {
        $lancement = DB::table('bandes')->where('id', $bandeId)->value('date_lancement_bande');

        if (! $lancement) {
            return 0;
        }

        return (int) max(0, Carbon::parse($lancement)->startOfDay()->diffInDays($day->copy()->startOfDay(), false));
    }

    private function nutritionQte(int $bandeId, Carbon $day, float $facteur): float
    {
        $sujets = (int) DB::table('bandes')->where('id', $bandeId)->value('nbre_sujet');

        return round($sujets * $facteur, 2);
    }

    private function mpStock(int $matiereId): float
    {
        return (float) DB::table('stock_matiere_premieres')
            ->where('id_matiere_premiere', $matiereId)
            ->selectRaw("COALESCE(SUM(CASE WHEN type_mouvement = 'approvisionnement' THEN quantite ELSE -quantite END), 0) AS total")
            ->value('total');
    }

    private function mpMovement(int $matiereId, string $type, float $qte, Carbon $at, string $motif): void
    {
        if ($this->exists('stock_matiere_premieres', ['id_matiere_premiere' => $matiereId, 'motif' => $motif])) {
            return;
        }

        $dernier = $this->mpStock($matiereId);
        $nouveau = $type === 'approvisionnement' ? $dernier + $qte : $dernier - $qte;

        DB::table('stock_matiere_premieres')->insert([
            'id_matiere_premiere' => $matiereId,
            'type_mouvement' => $type,
            'quantite' => $qte,
            'dernier_stock' => $dernier,
            'nouveau_stock' => (string) $nouveau,
            'date_entree' => $type === 'approvisionnement' ? $at : null,
            'date_sortie' => $type === 'destockage' ? $at : null,
            'motif' => $motif,
            'unite_principale' => 'kg',
            'conversion_kg' => 1,
            'conversion_gr' => 1000,
            'conversion_sac' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function deriveStock(int $deriveId): float
    {
        return (float) DB::table('stock_produits')
            ->where('id_derive_produit', $deriveId)
            ->selectRaw("COALESCE(SUM(CASE WHEN type_mouvement = 'approvisionnement' THEN qte ELSE -qte END), 0) AS total")
            ->value('total');
    }

    private function productStock(int $produitId): float
    {
        return (float) DB::table('stock_produits')
            ->where('id_produit', $produitId)
            ->selectRaw("COALESCE(SUM(CASE WHEN type_mouvement = 'approvisionnement' THEN qte ELSE -qte END), 0) AS total")
            ->value('total');
    }

    private function prodMovement(int $produitId, string $type, float $qte, Carbon $at, ?int $deriveId = null): int
    {
        $dernier = $deriveId ? $this->deriveStock($deriveId) : $this->productStock($produitId);
        $nouveau = $type === 'approvisionnement' ? $dernier + $qte : $dernier - $qte;

        return DB::table('stock_produits')->insertGetId([
            'id_produit' => $produitId,
            'id_derive_produit' => $deriveId,
            'type_mouvement' => $type,
            'qte' => $qte,
            'dernier_stock' => $dernier,
            'nouveau_stock' => $nouveau,
            'date_entree' => $type === 'approvisionnement' ? $at : null,
            'date_sortie' => $type === 'destockage' ? $at : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function recordDepense(Carbon $at, float $montant, string $categorie, string $motif, string $description, string $reference): void
    {
        if ($this->exists('depense_depenses', ['motif_depense' => $motif])) {
            return;
        }

        $categorieId = $this->categories[$categorie] ?? null;
        if (! $categorieId) {
            return;
        }

        DB::table('depense_depenses')->insert([
            'id_compte' => $this->caisseId,
            'fournisseur' => null,
            'montant' => $montant,
            'description' => $description,
            'motif_depense' => $motif,
            'date_depense' => $at,
            'id_saver' => $this->users['comptable'],
            'id_categorie' => $categorieId,
            'created_at' => $at,
            'updated_at' => $at,
        ]);

        $this->caisseEntry('sortie', $montant, $at, $reference, $description);
    }

    private function caisseEntry(string $type, float $amount, Carbon $at, string $reference, string $description, ?int $compteId = null): void
    {
        if ($this->exists('gestion_caisse_transactions', ['reference_number' => $reference])) {
            return;
        }

        DB::table('gestion_caisse_transactions')->insert([
            'compte_id' => $compteId ?? $this->caisseId,
            'transaction_type' => $type,
            'montant' => $amount,
            'transaction_date' => $at->toDateString(),
            'description' => $description,
            'reference_number' => $reference,
            'user_id' => $this->users['caissier'],
            'related_transaction_id' => null,
            'id_depense' => null,
            'created_at' => $at,
            'updated_at' => $at,
        ]);
    }

    private function refreshBalances(): void
    {
        foreach ([$this->caisseId, $this->banqueId] as $compteId) {
            $initial = (float) DB::table('gestion_caisse_comptes')->where('id', $compteId)->value('initial_balance');

            $entrees = (float) DB::table('gestion_caisse_transactions')
                ->where('compte_id', $compteId)
                ->where('transaction_type', 'entrée')
                ->sum('montant');

            $sorties = (float) DB::table('gestion_caisse_transactions')
                ->where('compte_id', $compteId)
                ->where('transaction_type', 'sortie')
                ->sum('montant');

            DB::table('gestion_caisse_comptes')->where('id', $compteId)->update([
                'current_balance' => (int) round($initial + $entrees - $sorties),
                'updated_at' => now(),
            ]);
        }
    }

    private function exists(string $table, array $match): bool
    {
        $query = DB::table($table);

        foreach ($match as $column => $value) {
            if ($value === null) {
                $query->whereNull($column);
            } else {
                $query->where($column, $value);
            }
        }

        return $query->exists();
    }
}
