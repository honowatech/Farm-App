<?php

namespace App\Traits;

use App\Models\FoodSettingVersion;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

trait FindsValidFoodSettings
{
    /**
     * Récupère les versions de planning de nutrition valides pour un type d'animal,
     * un âge et une date donnés.
     *
     * @param  Carbon|string  $date
     * @return Collection
     */
    protected function getValidFoodSettings(int $id_type_animal, int $ageInDays, $date)
    {
        $targetDate = Carbon::parse($date);

        return FoodSettingVersion::query()
            // Jointure pour accéder aux infos du parent (id_type_animal, aliment)
            ->join('foods_setting', 'food_setting_versions.food_setting_id', '=', 'foods_setting.id')

            // Filtrer par le bon type d'animal
            ->where('foods_setting.id_type_animal', $id_type_animal)

            // Filtrer les versions qui étaient actives à la date cible
            ->where('food_setting_versions.activated_at', '<=', $targetDate->endOfDay())
            ->where(function ($query) use ($targetDate) {
                $query->where('food_setting_versions.ended_at', '>=', $targetDate->startOfDay())
                    ->orWhereNull('food_setting_versions.ended_at');
            })

            // Filtrer par la tranche d'âge correcte
            ->where('food_setting_versions.age_min', '<=', $ageInDays)
            ->where('food_setting_versions.age_max', '>=', $ageInDays)

            // Sélectionner les colonnes nécessaires pour éviter les ambiguïtés
            ->select('food_setting_versions.*', 'foods_setting.aliment')

            // Charger les relations pour un accès facile
            ->with(['unite', 'foodSetting.alimentName'])
            ->get();
    }
}
