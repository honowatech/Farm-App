<?php

namespace App\Services;

use App\Models\Bande;
use App\Models\Nutrition;
use App\Models\Stock_matiere_premiere;
use App\Traits\FindsValidFoodSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NutritionService
{
    use FindsValidFoodSettings;

    /**
     * Enregistre une entrée de nutrition et son mouvement de stock.
     *
     * Lève une RuntimeException avec un message destiné à l'utilisateur
     * lorsqu'une règle métier n'est pas respectée.
     */
    public function enregistrer(Bande $bande, array $data): Nutrition
    {
        $dateNutrition = Carbon::parse($data['date']);
        $idAliment = (int) $data['id_aliment'];

        $age = $bande->getAgeOnDate($dateNutrition);
        $foodSettingsValides = $this->getValidFoodSettings($bande->id_type_animal, $age, $dateNutrition);
        $foodSettingChoisi = $foodSettingsValides->firstWhere('aliment', $idAliment);

        if ($foodSettingsValides->isEmpty() || $foodSettingChoisi === null) {
            throw new \RuntimeException('Aucun planning de nutrition valide n\'a été trouvé pour cet âge à cette date.');
        }

        $quantiteEnKg = $this->convertirEnKg((float) $data['qte'], strtolower($data['unite_string']), $idAliment);

        $stockDisponible = $this->stockDisponible($idAliment);

        if ($stockDisponible < $quantiteEnKg) {
            throw new \RuntimeException('Stock insuffisant. Stock actuel : '.$stockDisponible.' Kg, Requis : '.$quantiteEnKg.' Kg.');
        }

        $this->verifierFrequences($bande, $idAliment, $dateNutrition, $foodSettingsValides, $foodSettingChoisi);

        return DB::transaction(function () use ($bande, $data, $idAliment, $quantiteEnKg, $stockDisponible) {
            $nutrition = Nutrition::create([
                'id_bande' => $bande->id,
                'id_aliment' => $idAliment,
                'qte' => $data['qte'],
                'date' => $data['date'],
                'id_unite' => $data['id_unite'],
            ]);

            Stock_matiere_premiere::create([
                'id_matiere_premiere' => $idAliment,
                'type_mouvement' => 'destockage',
                'dernier_stock' => $stockDisponible,
                'quantite' => $quantiteEnKg,
                'nouveau_stock' => $stockDisponible - $quantiteEnKg,
                'date_sortie' => $data['date'],
                'motif' => 'Nutrition des animaux (Bande: '.$bande->nom.')',
            ]);

            return $nutrition;
        });
    }

    private function convertirEnKg(float $quantite, string $unite, int $idAliment): float
    {
        return match ($unite) {
            'g' => $quantite / 1000,
            'sac' => $quantite * $this->poidsConversionSac($idAliment),
            default => $quantite,
        };
    }

    private function poidsConversionSac(int $idAliment): float
    {
        $dernierAchat = Stock_matiere_premiere::where('id_matiere_premiere', $idAliment)
            ->where('type_mouvement', 'approvisionnement')
            ->whereNotNull('conversion_sac')
            ->latest('date_entree')
            ->first();

        if (! $dernierAchat || ! $dernierAchat->conversion_sac) {
            throw new \RuntimeException('Impossible de trouver le poids de conversion pour un sac de cet aliment. Veuillez vérifier les achats.');
        }

        return (float) $dernierAchat->conversion_sac;
    }

    private function stockDisponible(int $idAliment): float
    {
        return (float) Stock_matiere_premiere::where('id_matiere_premiere', $idAliment)
            ->sum(DB::raw('quantite * (CASE WHEN type_mouvement = "approvisionnement" THEN 1 ELSE -1 END)'));
    }

    private function verifierFrequences(
        Bande $bande,
        int $idAliment,
        Carbon $dateNutrition,
        $foodSettingsValides,
        $foodSettingChoisi,
    ): void {
        $totalFrequencyAttendue = $foodSettingsValides->sum('frequency');

        $passagesDejaFaitsCeJour = Nutrition::where('id_bande', $bande->id)
            ->whereDate('date', $dateNutrition)
            ->count();

        $passagesAlimentDejaFaitsCeJour = Nutrition::where('id_bande', $bande->id)
            ->where('id_aliment', $idAliment)
            ->whereDate('date', $dateNutrition)
            ->count();

        if ($passagesDejaFaitsCeJour >= $totalFrequencyAttendue) {
            throw new \RuntimeException('Cette bande a déjà atteint le nombre total de repas prévus pour cette date.');
        }

        if ($passagesAlimentDejaFaitsCeJour >= $foodSettingChoisi->frequency) {
            throw new \RuntimeException('Cet aliment a déjà été donné le nombre de fois requis pour cette date.');
        }
    }
}
