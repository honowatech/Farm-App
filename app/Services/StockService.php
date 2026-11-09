<?php

namespace App\Services;

use App\Models\Derive_produit;
use App\Models\Stock_matiere_premiere;
use App\Models\Stock_produit;

class StockService
{
    /**
     * Stock courant d'un produit, basé sur le dernier mouvement enregistré.
     */
    public function stockProduit(int $produitId): float
    {
        return (float) (Stock_produit::where('id_produit', $produitId)
            ->orderBy('id', 'desc')
            ->value('nouveau_stock') ?? 0);
    }

    /**
     * Stock d'un produit dérivé (somme des mouvements approvisionnement/destockage).
     */
    public function stockDerive(int $deriveId): float
    {
        return (float) Stock_produit::where('id_derive_produit', $deriveId)
            ->selectRaw("COALESCE(SUM(CASE WHEN type_mouvement = 'approvisionnement' THEN qte ELSE -qte END), 0) as total")
            ->value('total');
    }

    /**
     * Stock d'une matière première (somme des mouvements approvisionnement/destockage).
     */
    public function stockMatierePremiere(int $matiereId): float
    {
        return (float) Stock_matiere_premiere::where('id_matiere_premiere', $matiereId)
            ->selectRaw("COALESCE(SUM(CASE WHEN type_mouvement = 'approvisionnement' THEN quantite ELSE -quantite END), 0) as total")
            ->value('total');
    }

    /**
     * Stock d'un produit calculé via ses produits dérivés.
     */
    public function stockProduitDerives(int $produitId): float
    {
        $deriveIds = Derive_produit::where('id_produit', $produitId)->pluck('id');

        return (float) Stock_produit::whereIn('id_derive_produit', $deriveIds)
            ->selectRaw("COALESCE(SUM(CASE WHEN type_mouvement = 'approvisionnement' THEN qte ELSE -qte END), 0) as total")
            ->value('total');
    }

    /**
     * Enregistre une sortie (destockage) de produit.
     */
    public function sortieProduit(int $produitId, float $qte, string $date): Stock_produit
    {
        $dernierStock = $this->stockProduit($produitId);

        return Stock_produit::create([
            'id_produit' => $produitId,
            'type_mouvement' => 'destockage',
            'qte' => $qte,
            'dernier_stock' => $dernierStock,
            'nouveau_stock' => $dernierStock - $qte,
            'date_sortie' => $date,
        ]);
    }
}
