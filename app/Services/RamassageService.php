<?php

namespace App\Services;

use App\Models\Ramassage;
use App\Models\Stock_produit;
use Illuminate\Support\Facades\DB;

class RamassageService
{
    public function __construct(private StockService $stockService)
    {
    }

    /**
     * Enregistre un ramassage et les mouvements de stock associés, de façon
     * transactionnelle.
     *
     * @param  array  $data  Données déjà validées par le contrôleur.
     */
    public function enregistrer(array $data): Ramassage
    {
        return DB::transaction(function () use ($data) {
            $ramassage = Ramassage::create([
                'id_produit' => $data['id_produit'],
                'id_bande' => $data['id_bande'],
                'ids_derive' => json_encode($data['ids']),
                'valeurs_derive' => json_encode($data['valeurs']),
                'id_prestataire' => $data['id_prestataire'],
                'commentaire' => $data['commentaire'],
                'id_saver' => $data['id_saver'],
                'to_do_date' => $data['date'],
                'statut' => 1,
            ]);

            $this->creerMouvementsStock($data, $ramassage);

            return $ramassage;
        });
    }

    /**
     * Crée un mouvement d'approvisionnement par produit dérivé ramassé.
     * Le stock courant est calculé par agrégation SQL (pas de boucle N+1).
     */
    private function creerMouvementsStock(array $data, Ramassage $ramassage): void
    {
        foreach ($data['ids'] as $index => $idDerive) {
            $qte = (float) $data['valeurs'][$index];

            if ((int) $qte === 0) {
                continue;
            }

            $idDerive = (int) $idDerive;
            $stockCourant = $this->stockService->stockDerive($idDerive);

            Stock_produit::create([
                'id_produit' => $data['id_produit'],
                'qte' => $qte,
                'id_derive_produit' => $idDerive,
                'date_entree' => $data['date'],
                'dernier_stock' => $stockCourant,
                'nouveau_stock' => $stockCourant + (int) $qte,
                'type_mouvement' => 'approvisionnement',
            ]);
        }
    }
}
