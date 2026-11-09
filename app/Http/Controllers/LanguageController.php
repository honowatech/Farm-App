<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switchLang($locale)
    {
        // Vérifie si la langue est supportée
        if (! in_array($locale, ['en', 'fr'])) {
            abort(400, 'Language not supported.');
        }
        // Stocke la langue dans la session
        session(['locale' => $locale]);

        // Redirige l'utilisateur à la page précédente
        return redirect()->back();
    }
}
