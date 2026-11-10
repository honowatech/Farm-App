<?php

namespace App\Services;

use App\Models\Bande;
use App\Models\Mortalite;

class MortaliteService
{
    /**
     * Effectif vivant = effectif initial - mortalité cumulée.
     */
    public function effectifVivant(Bande $bande): int
    {
        return max(0, $bande->nbre_sujet - $this->totalMorts($bande));
    }

    /**
     * Mortalité cumulée d'une bande.
     */
    public function totalMorts(Bande $bande): int
    {
        return (int) Mortalite::where('id_bande', $bande->id)->sum('nombre_morts');
    }

    /**
     * Taux de mortalité (en %).
     */
    public function tauxMortalite(Bande $bande): float
    {
        if ($bande->nbre_sujet <= 0) {
            return 0.0;
        }

        return round(($this->totalMorts($bande) / $bande->nbre_sujet) * 100, 2);
    }
}
