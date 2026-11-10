<?php

namespace App\Services;

use App\Models\Bande;
use App\Models\Matiere_premiere;
use App\Models\ProgrammeVaccination;
use App\Models\Stock_matiere_premiere;

class AlerteService
{
    /**
     * Évalue et retourne les alertes actives (calculées à la volée).
     */
    public function getAlertes(): array
    {
        $alertes = [];

        $bandesActives = Bande::where('statut_bande', 'encours')->get();

        // 1. Bande non nourrie aujourd'hui
        foreach ($bandesActives as $bande) {
            if (in_array($bande->statut_nutrition_aujourdhui, ['Aucune', 'Incomplet'], true)) {
                $alertes[] = $this->make(
                    'nutrition',
                    'warning',
                    'Bande non nourrie',
                    "La bande \"{$bande->nom}\" n'a pas été nourrie aujourd'hui.",
                    route('gestion_bande', $bande->id)
                );
            }
        }

        // 2. Stock de matière première bas
        foreach (Matiere_premiere::whereNotNull('seuil_alerte')->get() as $matiere) {
            $dernier = Stock_matiere_premiere::where('id_matiere_premiere', $matiere->id)->latest()->first();
            $stock = $dernier ? (float) $dernier->nouveau_stock : 0;

            if ($stock <= (float) $matiere->seuil_alerte) {
                $alertes[] = $this->make(
                    'stock',
                    'danger',
                    'Stock bas',
                    "Le stock de \"{$matiere->nom_produit}\" est bas ({$stock} restant).",
                    route('matiere_premiere.index')
                );
            }
        }

        // 3. Rappel de vaccination
        foreach ($bandesActives as $bande) {
            $age = $bande->age_en_jours;
            $programmes = ProgrammeVaccination::where('id_type_animal', $bande->id_type_animal)
                ->where('age_jour', '<=', $age)
                ->get();

            foreach ($programmes as $programme) {
                $alertes[] = $this->make(
                    'vaccin',
                    'info',
                    'Rappel vaccin',
                    "Vaccin \"{$programme->nom_vaccin}\" dû pour la bande \"{$bande->nom}\" (âge {$age} j).",
                    route('seanceVaccination.index')
                );
            }
        }

        return $alertes;
    }

    public function count(): int
    {
        return count($this->getAlertes());
    }

    private function make(string $type, string $severite, string $titre, string $message, string $lien): array
    {
        return compact('type', 'severite', 'titre', 'message', 'lien');
    }
}
