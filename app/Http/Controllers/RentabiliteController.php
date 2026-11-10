<?php

namespace App\Http\Controllers;

use App\Models\Bande;
use App\Services\RentabiliteService;

class RentabiliteController extends Controller
{
    public function index(RentabiliteService $service)
    {
        $bandes = Bande::orderBy('nom')->get();

        $resultats = $bandes->map(function ($bande) use ($service) {
            return array_merge(['bande' => $bande], $service->rentabilite($bande));
        });

        return view('admin.pages.rentabilite.index', compact('resultats'));
    }
}
