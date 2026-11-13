<?php

use App\Models\Logo;
use Carbon\Carbon;

if (! function_exists('textBreack')) {
    function textBreack($chaine, $nombre)
    {
        $result = '';

        if (strlen($chaine) <= $nombre) {
            return true;
        }

        for ($i = 0; $i < strlen($chaine); $i++) {
            if ($i > 0 && $i % $nombre === 0) {
                if (ctype_space($chaine[$i - 1])) {
                    $result .= "\n";
                } else {
                    $result .= "-\n";
                }
            }
            $result .= $chaine[$i];
        }

        return $result;
    }
}
if (! function_exists('logo')) {
    function logo()
    {
        $logo = Logo::latest()->first();
        if ($logo == null) {
            return '';
        }

        return asset($logo->path);
    }
}
if (! function_exists('name')) {
    function name($chaine)
    {
        $result = '';
        $divise = explode(' ', $chaine);

        foreach ($divise as $key => $value) {

            $result .= ucfirst($value).' ';

        }

        return $result;
    }
}
if (! function_exists('cutText')) {
    function cutText($text, $number)
    {
        $result = '';
        if (strlen($text) <= $number) {
            $result = $text;
        } else {
            for ($i = 0; $i < $number - 1; $i++) {
                $result .= $text[$i];
            }
            $result .= '...';
        }

        return $result;
    }
}
if (! function_exists('Ladate')) {
    function Ladate($ladate)
    {
        $mois = ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Decembre'];
        $ladate = new DateTime($ladate);

        return $ladate->format('d ').$mois[$ladate->format('m') - 1].$ladate->format(' Y');
    }
}
if (! function_exists('Heure')) {
    function Heure($ladate)
    {
        $time = explode(' ', $ladate)[1];
        $table = explode(':', $time);

        return $table[0].'h'.$table[1].'min';
    }
}
if (! function_exists('Heure2')) {
    function Heure2($lheure)
    {
        $table = explode(':', $lheure);

        return $table[0].'h'.$table[1].'min';
    }
}
if (! function_exists('phone')) {
    function phone($number)
    {
        if ($number === null || trim((string) $number) === '') {
            return '';
        }

        if (strlen(trim($number)) > 9 || strlen(trim($number)) > 9) {
            $result = $number;
        } else {
            $result = '+237 '.$number[0].' '.$number[1].$number[2].' '.$number[3].$number[4].' '.$number[5].$number[6].' '.$number[7].$number[8];
        }

        return $result;
    }
}
if (! function_exists('phone2')) {
    function phone2($number)
    {
        if (strlen(trim($number)) > 9 || strlen(trim($number)) > 9) {
            $result = $number;
        } else {
            $result = $number[0].' '.$number[1].$number[2].' '.$number[3].$number[4].' '.$number[5].$number[6].' '.$number[7].$number[8];
        }

        return $result;
    }
}

if (! function_exists('prochaine_date_paie')) {
    function prochaine_date_paie($type_periode, $accumulateur, $ancienne_date, $nombre_fois, $type = null)
    {
        // //////////////////////////////////////////////////////////////////////////////////////////////////////////

        $dt = Carbon::parse($ancienne_date);

        if ($type_periode == 'jour') {
            $prochaine_date = $dt->addDays($accumulateur * $nombre_fois);
        } elseif ($type_periode == 'semaine') {
            $prochaine_date = $dt->addWeeks($accumulateur * $nombre_fois);
        } elseif ($type_periode == 'mois') {
            $prochaine_date = $dt->addMonths($accumulateur * $nombre_fois);
        } elseif ($type_periode == 'annee') {
            $prochaine_date = $dt->addYears($accumulateur * $nombre_fois);
        }

        if ($type_periode == 'jour' && $type == 'avance') {
            $prochaine_date = $dt->addDays();
        } elseif ($type_periode == 'semaine' && $type == 'avance') {
            $prochaine_date = $dt->addWeeks();
        } elseif ($type_periode == 'mois' && $type == 'avance') {
            $prochaine_date = $dt->addMonths();
        } elseif ($type_periode == 'annee' && $type == 'avance') {
            $prochaine_date = $dt->addYears();
        }

        // //////////////////////////////////////////////////////////////////////////////////////////////////////////
        return $prochaine_date;
    }
}

if (! function_exists('montant_restant')) {
    function montant_restant($montant_global, $salaire)
    {
        // ///////////// Cette fonction retourne le montant à rembourser s'il y a une avance en cours ///////////////

        $montant_avance = intval($montant_global) % intval($salaire);
        if ($montant_avance == 0) {
            $montant_restant = 0;
            $statut = true;
        } else {
            $montant_restant = $salaire - $montant_avance;
            $statut = false;
        }

        // //////////////////////////////////////////////////////////////////////////////////////////////////////////
        return [
            'montant' => $montant_restant,
            'statut' => $statut,
        ];
    }
}

if (! function_exists('calculerProchaineNutrition')) {
    function calculerProchaineNutrition($frequence, $periode, $derniere_nutrition, $compteur_actuel)
    {
        $derniereNutritionDate = new DateTime($derniere_nutrition);

        // Vérifie si le quota n'est pas encore atteint
        if ($compteur_actuel < $frequence) {
            // Si le quota n'est pas atteint, la prochaine nutrition reste à la date de la dernière nutrition
            return $derniereNutritionDate;
        }

        // Calcule la prochaine nutrition en fonction de la période et ajuste à 00h00
        switch ($periode) {
            case 'day':
                // Prochaine nutrition au début du jour suivant
                $prochaineNutrition = $derniereNutritionDate->modify('+1 day')->setTime(0, 0);
                break;
            case 'week':
                // Prochaine nutrition au début de la semaine suivante (prochain lundi à 00h00)
                $prochaineNutrition = $derniereNutritionDate->modify('next monday')->setTime(0, 0);
                break;
            case 'month':
                // Prochaine nutrition au début du mois suivant (1er jour du mois prochain à 00h00)
                $prochaineNutrition = $derniereNutritionDate->modify('first day of next month')->setTime(0, 0);
                break;
            default:
                // Par défaut, on choisit le lendemain à 00h00 si la période n'est pas définie
                $prochaineNutrition = $derniereNutritionDate->modify('+1 day')->setTime(0, 0);
        }

        return $prochaineNutrition;
    }
}
if (! function_exists('estAutoriseANourrir')) {
    function estAutoriseANourrir($frequence_globale, $periode, $compteur_nutrition_globale, $derniere_nutrition, $frequence_aliment, $compteur_nutrition_aliment)
    {
        $maintenant = new DateTime;
        $aujourdhuiMinuit = (clone $maintenant)->setTime(0, 0, 0);
        $finMois = (clone $maintenant)->modify('last day of this month')->setTime(23, 59, 59);
        $finSemaine = (clone $maintenant)->modify('next sunday')->setTime(23, 59, 59);

        if (is_null($derniere_nutrition)) {
            // Aucun enregistrement n'a été fait, donc on peut nourrir immédiatement
            return true;
        }

        $derniereNutrition = new DateTime($derniere_nutrition);

        // Déterminer la période de fin en fonction de la fréquence demandée
        switch ($periode) {
            case 'day':
                $fin_periode = $derniereNutrition->format('Y-m-d') === $maintenant->format('Y-m-d') ?
                    $aujourdhuiMinuit->modify('+1 day') :
                    $aujourdhuiMinuit;
                break; // Fin de la journée à minuit
            case 'week':
                $fin_periode = $finSemaine; // Fin de la semaine en cours
                break;
            case 'month':
                $fin_periode = $finMois; // Fin du mois en cours
                break;
        }

        // Si on est passé à une nouvelle période (minuit pour jour, fin de mois ou semaine), réinitialiser le compteur
        if ($maintenant >= $fin_periode) {
            return 'reset';
        }

        // Vérifier si l'animal peut être nourri avec cet aliment spécifique
        if ($maintenant < $fin_periode && $compteur_nutrition_aliment >= $frequence_aliment) {
            return false;
        }

        // Vérifier si on peut nourrir globalement (sans considération d'aliment spécifique)
        if ($maintenant < $fin_periode && $compteur_nutrition_globale < $frequence_globale) {
            return true;
        }

        // Sinon, l'animal a déjà été nourri suffisamment avec cet aliment ou globalement
        return false;
    }

}
