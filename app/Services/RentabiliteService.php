<?php

namespace App\Services;

use App\Models\Bande;
use App\Models\BandeCout;
use App\Models\Ligne_facture;
use App\Models\Nutrition;
use App\Models\Seance_de_vaccination;

class RentabiliteService
{
    /**
     * Calcule la rentabilité d'une bande (coûts vs revenus).
     */
    public function rentabilite(Bande $bande): array
    {
        $poussins = (float) ($bande->prix_acquisition ?? 0);

        $aliment = (float) Nutrition::where('id_bande', $bande->id)
            ->with('aliment_name')
            ->get()
            ->sum(fn ($n) => $n->qte * (float) ($n->aliment_name->prix_unitaire ?? 0));

        $vaccins = (float) Seance_de_vaccination::where('id_bande', $bande->id)->sum('cout');

        $autresCouts = (float) BandeCout::where('id_bande', $bande->id)->sum('montant');

        $revenus = (float) Ligne_facture::where('id_bande', $bande->id)->sum('total_ligne');

        $totalCouts = $poussins + $aliment + $vaccins + $autresCouts;
        $marge = $revenus - $totalCouts;

        return [
            'poussins' => $poussins,
            'aliment' => $aliment,
            'vaccins' => $vaccins,
            'autres_couts' => $autresCouts,
            'total_couts' => $totalCouts,
            'revenus' => $revenus,
            'marge' => $marge,
        ];
    }
}
