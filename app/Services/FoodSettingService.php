<?php

namespace App\Services;

use App\Models\Food_Setting;
use App\Models\FoodSettingVersion;
use Illuminate\Support\Facades\DB;

class FoodSettingService
{
    /**
     * Crée un planning de nutrition (parent) et ses versions initiales.
     *
     * @throws \RuntimeException si un planning existe déjà pour ce couple
     *                           type d'animal / aliment.
     */
    public function creer(int $idTypeAnimal, int $idAliment, array $invoices): Food_Setting
    {
        if (Food_Setting::where('id_type_animal', $idTypeAnimal)
            ->where('aliment', $idAliment)
            ->exists()) {
            throw new \RuntimeException('Un planning pour ce type d\'animal et cet aliment existe déjà. Veuillez le modifier.');
        }

        return DB::transaction(function () use ($idTypeAnimal, $idAliment, $invoices) {
            $parent = Food_Setting::create([
                'id_type_animal' => $idTypeAnimal,
                'aliment' => $idAliment,
            ]);

            foreach ($invoices as $invoice) {
                $this->creerVersion($parent->id, $invoice);
            }

            return $parent;
        });
    }

    /**
     * Archive les versions actives et crée les nouvelles versions d'un planning.
     */
    public function mettreAJour(int $idTypeAnimal, int $idAliment, array $invoices): Food_Setting
    {
        return DB::transaction(function () use ($idTypeAnimal, $idAliment, $invoices) {
            $parent = Food_Setting::where('id_type_animal', $idTypeAnimal)
                ->where('aliment', $idAliment)
                ->firstOrFail();

            $parent->activeVersionsDetails()->update(['ended_at' => now()]);

            foreach ($invoices as $invoice) {
                $this->creerVersion($parent->id, $invoice);
            }

            $parent->touch();

            return $parent;
        });
    }

    private function creerVersion(int $foodSettingId, array $invoice): FoodSettingVersion
    {
        return FoodSettingVersion::create([
            'food_setting_id' => $foodSettingId,
            'age_min' => $invoice['age_min'],
            'age_max' => $invoice['age_max'],
            'quantite_aliment' => $invoice['qte'],
            'id_unite' => $invoice['unite'],
            'frequency' => $invoice['frequency'],
            'period' => $invoice['period'],
            'activated_at' => now(),
            'ended_at' => null,
        ]);
    }
}
