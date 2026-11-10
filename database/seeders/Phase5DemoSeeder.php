<?php

namespace Database\Seeders;

use App\Models\Bande;
use App\Models\BandeCout;
use App\Models\BandeCoutType;
use App\Models\Batiment;
use App\Models\Client;
use App\Models\Facture;
use App\Models\Fournisseur;
use App\Models\Ligne_facture;
use App\Models\Matiere_premiere;
use App\Models\Mortalite;
use App\Models\MortaliteCause;
use App\Models\Nutrition;
use App\Models\Produit;
use App\Models\ProgrammeVaccination;
use App\Models\Seance_de_vaccination;
use App\Models\Stock_matiere_premiere;
use App\Models\Type_animal;
use App\Models\Type_produit;
use App\Models\Unite;
use App\Models\User;
use App\Models\Vente;
use App\Models\Veterinaire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class Phase5DemoSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        $adminId = User::first()?->id ?? 1;

        // 1. Types de produits (enum existant)
        $typeAnimal = Type_produit::firstOrCreate(['nom' => 'animal']);
        $typeOeuf = Type_produit::firstOrCreate(['nom' => 'oeuf']);
        $typeFiente = Type_produit::firstOrCreate(['nom' => 'fiente']);

        // 2. Types d'animaux
        $pouletChair = Type_animal::firstOrCreate(['nom' => 'Poulet de chair']);
        $pondeuse = Type_animal::firstOrCreate(['nom' => 'Poule pondeuse']);

        // 3. Bâtiments
        $batA = Batiment::firstOrCreate(['nom' => 'Bâtiment A']);
        $batB = Batiment::firstOrCreate(['nom' => 'Bâtiment B']);
        $batC = Batiment::firstOrCreate(['nom' => 'Bâtiment C']);

        // 4. Unités
        $kg = Unite::firstOrCreate(['libelle' => 'Kilogramme', 'abrege' => 'kg']);
        $litre = Unite::firstOrCreate(['libelle' => 'Litre', 'abrege' => 'L']);

        // 5. Vétérinaire
        $veto = Veterinaire::firstOrCreate(['nom' => 'Dr Vet', 'statut' => 1]);

        // 6. Clients
        $client1 = Client::firstOrCreate(['nom' => 'Client Marché Central'], ['telephone' => '690000001']);
        $client2 = Client::firstOrCreate(['nom' => 'Supermarché Niki'], ['telephone' => '690000002']);

        // 7. Fournisseurs
        Fournisseur::firstOrCreate(['nom' => 'Provenderie du Centre'], ['statut' => 1]);
        Fournisseur::firstOrCreate(['nom' => 'Couvoir Moderne'], ['statut' => 1]);

        // 8. Matières premières (aliments)
        $alimDemarrage = $this->matiere('Aliment démarrage', 400, 100);
        $alimCroissance = $this->matiere('Aliment croissance', 380, 80);
        $this->matiere('Aliment finition', 360, null);
        $this->matiere('Maïs', 250, 50);

        // 9. Produits
        $pouletEntier = Produit::firstOrCreate(
            ['nom' => 'Poulet entier'],
            ['prix_unitaire' => 3500, 'id_type_produit' => $typeAnimal->id, 'statut' => 1]
        );
        $oeufs = Produit::firstOrCreate(
            ['nom' => 'Œufs'],
            ['prix_unitaire' => 75, 'id_type_produit' => $typeOeuf->id, 'statut' => 1]
        );
        Produit::firstOrCreate(
            ['nom' => 'Fiente'],
            ['prix_unitaire' => 500, 'id_type_produit' => $typeFiente->id, 'statut' => 1]
        );

        // 10. Bandes
        $bande1 = Bande::firstOrCreate(['nom' => 'Bande Poulets - Janvier'], [
            'date_lancement_bande' => now()->subDays(30),
            'nbre_sujet' => 500,
            'age_depart' => '1',
            'statut_bande' => 'encours',
            'id_type_animal' => $pouletChair->id,
            'id_batiment' => $batA->id,
            'prix_acquisition' => 250000,
            'unite_age_depart' => 'jour',
        ]);

        $bande2 = Bande::firstOrCreate(['nom' => 'Bande Pondeuses - Février'], [
            'date_lancement_bande' => now()->subDays(45),
            'nbre_sujet' => 300,
            'age_depart' => '1',
            'statut_bande' => 'encours',
            'id_type_animal' => $pondeuse->id,
            'id_batiment' => $batB->id,
            'prix_acquisition' => 300000,
            'unite_age_depart' => 'jour',
        ]);

        Bande::firstOrCreate(['nom' => 'Bande Poulets - Terminée'], [
            'date_lancement_bande' => now()->subDays(90),
            'date_fin_bande' => now()->subDays(10)->format('Y-m-d'),
            'nbre_sujet' => 400,
            'age_depart' => '1',
            'statut_bande' => 'terminee',
            'id_type_animal' => $pouletChair->id,
            'id_batiment' => $batC->id,
            'prix_acquisition' => 200000,
            'unite_age_depart' => 'jour',
        ]);

        // 11. Mortalités (effectif vivant = initial - morts)
        $causeMaladie = MortaliteCause::where('libelle', 'Maladie')->first();
        $causePicage = MortaliteCause::where('libelle', 'Picage / cannibalisme')->first();

        Mortalite::firstOrCreate(
            ['id_bande' => $bande1->id, 'date' => now()->subDays(3)->format('Y-m-d'), 'nombre_morts' => 20],
            ['id_cause' => $causeMaladie?->id, 'id_saver' => $adminId]
        );
        Mortalite::firstOrCreate(
            ['id_bande' => $bande1->id, 'date' => now()->subDays(1)->format('Y-m-d'), 'nombre_morts' => 5],
            ['id_cause' => $causePicage?->id, 'id_saver' => $adminId]
        );
        Mortalite::firstOrCreate(
            ['id_bande' => $bande2->id, 'date' => now()->subDays(2)->format('Y-m-d'), 'nombre_morts' => 10],
            ['id_cause' => $causeMaladie?->id, 'id_saver' => $adminId]
        );

        // 12. Nutrition : bande 1 nourrie aujourd'hui, bande 2 NON (→ alerte)
        Nutrition::firstOrCreate([
            'id_bande' => $bande1->id,
            'id_aliment' => $alimDemarrage->id,
            'date' => now()->format('Y-m-d'),
        ], [
            'qte' => 100,
            'id_unite' => $kg->id,
            'nombre_de_passages' => 2,
        ]);

        // 13. Stock matière première bas (aliment croissance → alerte)
        Stock_matiere_premiere::firstOrCreate(
            ['id_matiere_premiere' => $alimCroissance->id, 'type_mouvement' => 'approvisionnement'],
            ['quantite' => 50, 'dernier_stock' => 0, 'nouveau_stock' => '50']
        );

        // 14. Ventes / factures / lignes (revenus par bande)
        $facture1 = Facture::firstOrCreate(['matricule' => 'FACT-DEMO-001'], [
            'date_facturation' => now()->subDays(5),
            'total_ht' => 350000,
            'tva' => 0,
            'total_ttc' => 350000,
            'id_client' => $client1->id,
        ]);
        Vente::firstOrCreate(['id_facture' => $facture1->id], ['id_saver' => $adminId, 'date_vente' => now()->subDays(5)->format('Y-m-d')]);
        Ligne_facture::firstOrCreate(
            ['id_facture' => $facture1->id, 'id_produit' => $pouletEntier->id],
            ['type_vente' => 'animal', 'mode_paiement' => 'espece', 'id_bande' => $bande1->id, 'qte' => 100, 'prix_unitaire' => 3500, 'total_ligne' => 350000]
        );

        $facture2 = Facture::firstOrCreate(['matricule' => 'FACT-DEMO-002'], [
            'date_facturation' => now()->subDays(2),
            'total_ht' => 75000,
            'tva' => 0,
            'total_ttc' => 75000,
            'id_client' => $client2->id,
        ]);
        Vente::firstOrCreate(['id_facture' => $facture2->id], ['id_saver' => $adminId, 'date_vente' => now()->subDays(2)->format('Y-m-d')]);
        Ligne_facture::firstOrCreate(
            ['id_facture' => $facture2->id, 'id_produit' => $oeufs->id],
            ['type_vente' => 'oeuf', 'mode_paiement' => 'espece', 'id_bande' => $bande2->id, 'qte' => 1000, 'prix_unitaire' => 75, 'total_ligne' => 75000]
        );

        // 15. Vaccinations (avec coût)
        Seance_de_vaccination::firstOrCreate(
            ['id_bande' => $bande1->id, 'vaccin_attribuer' => 'Gumboro'],
            ['id_veterinaire' => $veto->id, 'verification' => 1, 'description' => '', 'cout' => 15000]
        );
        Seance_de_vaccination::firstOrCreate(
            ['id_bande' => $bande2->id, 'vaccin_attribuer' => 'Newcastle'],
            ['id_veterinaire' => $veto->id, 'verification' => 1, 'description' => '', 'cout' => 10000]
        );

        // 16. Coûts indirects (main d'œuvre)
        $typeMO = BandeCoutType::where('libelle', "Main d'œuvre")->first();
        BandeCout::firstOrCreate(
            ['id_bande' => $bande1->id, 'id_type_cout' => $typeMO->id],
            ['montant' => 50000, 'id_saver' => $adminId]
        );
        BandeCout::firstOrCreate(
            ['id_bande' => $bande2->id, 'id_type_cout' => $typeMO->id],
            ['montant' => 30000, 'id_saver' => $adminId]
        );

        // 17. Programmes de vaccination (rappels)
        ProgrammeVaccination::firstOrCreate(['id_type_animal' => $pouletChair->id, 'age_jour' => 14, 'nom_vaccin' => 'Gumboro']);
        ProgrammeVaccination::firstOrCreate(['id_type_animal' => $pouletChair->id, 'age_jour' => 21, 'nom_vaccin' => 'Newcastle']);
        ProgrammeVaccination::firstOrCreate(['id_type_animal' => $pondeuse->id, 'age_jour' => 30, 'nom_vaccin' => 'Newcastle']);

        Model::reguard();

        $this->command->info('Jeu de données de démonstration Phase 5 créé avec succès.');
    }

    private function matiere(string $nom, float $prix, ?float $seuil): Matiere_premiere
    {
        $matiere = Matiere_premiere::where('nom_produit', $nom)->first();

        if ($matiere) {
            return $matiere;
        }

        return Matiere_premiere::create([
            'nom_produit' => $nom,
            'prix_unitaire' => $prix,
            'seuil_alerte' => $seuil,
        ]);
    }
}
