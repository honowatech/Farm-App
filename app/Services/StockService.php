<?php

namespace App\Services;

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
