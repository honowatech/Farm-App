<?php
    if (!function_exists('StockProduit')) {
        function Stockproduit($produit) {
            $statut_stock = '';
            $valeur_en_stock = 0;
            $ajout = 0;
            $retire = 0;
            if($produit->derives->count() != 0){
/////////////////////////////////////////////////////////////////////////////////////////////
                foreach ($produit->derives as $derive) {
                    foreach ($derive->mouvements_de_stock as $stock) {
                        if ($stock->type_mouvement == 'approvisionnement') {
                            $ajout += $stock->qte;
                        }else{
                            $retire += $stock->qte;
                        }
                    }
                }
                $valeur_en_stock = $ajout - $retire;
/////////////////////////////////////////////////////////////////////////////////////////////
            }else{  
                $valeur_en_stock = 0;
            }
            if ($valeur_en_stock > 0) {
                $statut_stock = '';
            }else{
                $valeur_en_stock = 0;
                $statut_stock = 'Stock Épuisé';
            }
            return [
                    'valeur_en_stock' => $valeur_en_stock,
                    'statut_stock' => $statut_stock
                    ];
        }
    }
    if (!function_exists('StockDerive')) {
        function StockDerive($derive) {                                   
            $statut_stock = '';
            $valeur_en_stock = 0;
            $ajout = 0;
            $retire = 0;
/////////////////////////////////////////////////////////////////////////////////////////////
                foreach ($derive->mouvements_de_stock as $stock) {
                    if ($stock->type_mouvement == 'approvisionnement') {
                        $ajout += $stock->qte;
                    }else{
                        $retire += $stock->qte;
                    }
                }
                $valeur_en_stock = $ajout - $retire;
/////////////////////////////////////////////////////////////////////////////////////////////
            if ($valeur_en_stock > 0) {
                $statut_stock = '';
            }else{
                $valeur_en_stock = 0;
                $statut_stock = 'Stock Épuisé';
            }
            return [
                    'valeur_en_stock' => $valeur_en_stock,
                    'statut_stock' => $statut_stock
                    ];
        }
    }
    if (!function_exists('StockMatierepremiere')) {
        function StockMatierepremiere($matierePremiere) {
            $statut_stock = '';
            $valeur_en_stock = 0;
            $ajout = 0;
            $retire = 0;
            if($matierePremiere->count() != 0){
        /////////////////////////////////////////////////////////////////////////////////////////////
                    foreach ($matierePremiere->mouvements_de_stock as $stock) {
                        if ($stock->type_mouvement == 'approvisionnement') {
                            $ajout += $stock->quantite;
                        }else{
                            $retire += $stock->quantite;
                        }
                    }
                    
                $valeur_en_stock = $ajout - $retire;
        /////////////////////////////////////////////////////////////////////////////////////////////
            }else{
                $valeur_en_stock = 0;
            }
            if ($valeur_en_stock > 0) {
                $statut_stock = '';
            }else{
                $valeur_en_stock = 0;
                $statut_stock = 'Stock Épuisé';
            }
            return [
                    'valeur_en_stock' => $valeur_en_stock,
                    'statut_stock' => $statut_stock
                    ];
        }
    }
    if (!function_exists('Matierepremiere')) {
        function Matierepremiere($matierePremieres) {
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
                    $pas_en_stock ++;
                    $ids_pas_en_stock .= $matierePremiere->id.',';
                } else {
                    $en_stock ++;
                    $ids_en_stock .= $matierePremiere->id.',';
                }
                if ($matierePremiere->statut == 1) {
                    $active ++;
                    $ids_active .= $matierePremiere->id.',';
                } else {
                    $desactive ++;
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
                    'ids_desactive' => $ids_desactive
                    ];
        }
    }
?>