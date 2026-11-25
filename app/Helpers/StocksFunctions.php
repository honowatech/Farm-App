<?php

use App\Services\StockService;

if (! function_exists('Stockproduit')) {
    function Stockproduit($produit)
    {
        $valeur_en_stock = app(StockService::class)->stockProduitDerives($produit->id);

        return [
            'valeur_en_stock' => $valeur_en_stock > 0 ? $valeur_en_stock : 0,
            'statut_stock' => $valeur_en_stock > 0 ? '' : 'Stock Épuisé',
        ];
    }
}
if (! function_exists('StockDerive')) {
    function StockDerive($derive)
    {
        $valeur_en_stock = app(StockService::class)->stockDerive($derive->id);

        return [
            'valeur_en_stock' => $valeur_en_stock > 0 ? $valeur_en_stock : 0,
            'statut_stock' => $valeur_en_stock > 0 ? '' : 'Stock Épuisé',
        ];
    }
}
if (! function_exists('StockMatierepremiere')) {
    function StockMatierepremiere($matierePremiere)
    {
        $valeur_en_stock = app(StockService::class)->stockMatierePremiere($matierePremiere->id);

        return [
            'valeur_en_stock' => $valeur_en_stock > 0 ? $valeur_en_stock : 0,
            'statut_stock' => $valeur_en_stock > 0 ? '' : 'Stock Épuisé',
        ];
    }
}
