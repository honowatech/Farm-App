<?php

namespace App\Http\Controllers;
use App\Models\Typeusers;
use App\Models\Bande;
use App\Models\Batiment;
use App\Models\Matiere_premiere;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        session()->forget("info");

        // Récupérer les bandes en cours
        $bandes_en_cours = Bande::where('date_lancement_bande', '<', now())
                            ->where('statut_bande', 'encours')
                            ->get();

        // Si l'utilisateur est un agent, afficher la vue agent
        if (Auth::user()->type_user->libelle === 'agent') {
            // Statistiques pour l'agent
            $nutritions_total = \App\Models\Nutrition::count();
            $nettoyages_total = \App\Models\Nettoyage::count();
            $vaccinations_total = \App\Models\Seance_de_vaccination::count();

            // Dernières activités
            $derniere_nutrition = \App\Models\Nutrition::latest()->first()?->created_at?->format('d/m/Y H:i') ?? __('N/A');
            $dernier_nettoyage = \App\Models\Nettoyage::latest()->first()?->created_at?->format('d/m/Y H:i') ?? __('N/A');
            $derniere_vaccination = \App\Models\Seance_de_vaccination::latest()->first()?->created_at?->format('d/m/Y H:i') ?? __('N/A');

            return view('agent.pages.home.home', compact(
                'bandes_en_cours',
                'nutritions_total',
                'nettoyages_total',
                'vaccinations_total',
                'derniere_nutrition',
                'dernier_nettoyage',
                'derniere_vaccination'
            ));
        }

        // Pour les autres utilisateurs, garder la vue admin existante
        $matiere_premieres = Matiere_premiere::get();
        $batiments = Batiment::get();
        return view('admin.pages.home.home', compact('bandes_en_cours', 'matiere_premieres', 'batiments'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
}
