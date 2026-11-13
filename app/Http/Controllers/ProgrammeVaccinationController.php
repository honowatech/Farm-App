<?php

namespace App\Http\Controllers;

use App\Models\ProgrammeVaccination;
use Illuminate\Contracts\Support\Renderable;

class ProgrammeVaccinationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche les programmes vaccinaux par type d'animal (lecture seule).
     */
    public function index(): Renderable
    {
        $programmes = ProgrammeVaccination::with('typeAnimal')
            ->orderBy('id_type_animal')
            ->orderBy('age_jour')
            ->paginate(10, ['*'], 'programme_page');

        // Regrouper la page courante par type d'animal pour l'affichage.
        $programmes = $programmes->setCollection(
            $programmes->getCollection()->groupBy('id_type_animal')
        );

        return view('admin.pages.seanceVaccination.programmes', compact('programmes'));
    }
}
