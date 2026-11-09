<?php

use App\Services\StockService;

if (! function_exists('StockProduit')) {
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
if (! function_exists('Matierepremiere')) {
    function Matierepremiere($matierePremieres)
    {
        $pas_en_stock = 0;
        $ids_pas_en_stock = '';
        $en_stock = 0;
        $ids_en_stock = '';
        $active = 0;
        $ids_active = '';
        $desactive = 0;
        $ids_desactive = '';
        foreach ($matierePremieres as $matierePremiere) {
            $valeur_en_stock = StockMatierepremiere($matierePremiere)['valeur_en_stock'];
            if ($valeur_en_stock == 0) {
                $pas_en_stock++;
                $ids_pas_en_stock .= $matierePremiere->id.',';
            } else {
                $en_stock++;
                $ids_en_stock .= $matierePremiere->id.',';
            }
            if ($matierePremiere->statut == 1) {
                $active++;
                $ids_active .= $matierePremiere->id.',';
            } else {
                $desactive++;
                $ids_desactive .= $matierePremiere->id.',';
            }
        }

        return [
            'pas_en_stock' => $pas_en_stock,
            'en_stock' => $en_stock,
            'active' => $active,
            'desactive' => $desactive,
            'ids_pas_en_stock' => $ids_pas_en_stock,
            'ids_en_stock' => $ids_en_stock,
            'ids_active' => $ids_active,
            'ids_desactive' => $ids_desactive,
        ];
    }
}
