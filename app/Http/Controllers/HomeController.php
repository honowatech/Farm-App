<?php

namespace App\Http\Controllers;

use App\Models\Bande;
use App\Models\Batiment;
use App\Models\Facture;
use App\Models\Matiere_premiere;
use App\Models\Nettoyage;
use App\Models\Nutrition;
use App\Models\Seance_de_vaccination;
use App\Models\Vente;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;

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
     * @return Renderable
     */
    public function index()
    {
        session()->forget('info');

        // Récupérer les bandes en cours
        $bandes_en_cours = Bande::where('date_lancement_bande', '<', now())
            ->where('statut_bande', 'encours')
            ->get();

        $role = Auth::user()->type_user?->libelle;

        // Si l'utilisateur est un agent, afficher la vue agent
        if ($role === 'agent') {
            // Statistiques pour l'agent
            $nutritions_total = Nutrition::count();
            $nettoyages_total = Nettoyage::count();
            $vaccinations_total = Seance_de_vaccination::count();

            // Dernières activités
            $derniere_nutrition = Nutrition::latest()->first()?->created_at?->format('d/m/Y H:i') ?? __('N/A');
            $dernier_nettoyage = Nettoyage::latest()->first()?->created_at?->format('d/m/Y H:i') ?? __('N/A');
            $derniere_vaccination = Seance_de_vaccination::latest()->first()?->created_at?->format('d/m/Y H:i') ?? __('N/A');

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

        // Pour les administrateurs, garder la vue admin existante
        if ($role === 'admin') {
            $matiere_premieres = Matiere_premiere::get();
            $batiments = Batiment::get();

            // KPIs : chiffre d'affaires et ventes du mois en cours
            $ca_du_mois = Facture::whereMonth('date_facturation', now()->month)
                ->whereYear('date_facturation', now()->year)
                ->sum('total_ttc');

            $ventes_du_mois = Vente::whereMonth('date_vente', now()->month)
                ->whereYear('date_vente', now()->year)
                ->count();

            // Effectif total des bandes en cours (sujets encore en élevage)
            $sujets_en_elevage = $bandes_en_cours->sum('nbre_sujet');

            // Alertes : bandes dont la nutrition du jour est absente ou incomplète
            $bandes_nutrition_alerte = $bandes_en_cours->filter(function (Bande $bande) {
                return in_array($bande->statut_nutrition_aujourdhui, ['Aucune', 'Incomplet'], true);
            });

            return view('admin.pages.home.home', compact(
                'bandes_en_cours',
                'matiere_premieres',
                'batiments',
                'ca_du_mois',
                'ventes_du_mois',
                'sujets_en_elevage',
                'bandes_nutrition_alerte'
            ));
        }

        abort(403, 'Accès non autorisé.');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
}
