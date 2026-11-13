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
        $bandes_query = Bande::where('date_lancement_bande', '<', now())
            ->where('statut_bande', 'encours')
            ->orderBy('date_lancement_bande', 'desc');

        // Collection complète pour les statistiques et les alertes.
        $bandes_en_cours = (clone $bandes_query)->get();

        // Collection paginée pour l'affichage du tableau de bord.
        $bandes_paginees = $bandes_query->paginate(10, ['*'], 'bande_page');

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
                'bandes_paginees',
                'nutritions_total',
                'nettoyages_total',
                'vaccinations_total',
                'derniere_nutrition',
                'dernier_nettoyage',
                'derniere_vaccination'
            ));
        }

        // Pour les administrateurs et les gestionnaires, garder la vue admin existante
        if (in_array($role, ['admin', 'gestionnaire'], true)) {
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
                'bandes_paginees',
                'matiere_premieres',
                'batiments',
                'ca_du_mois',
                'ventes_du_mois',
                'sujets_en_elevage',
                'bandes_nutrition_alerte'
            ));
        }

        // Profils métier (vétérinaire, magasinier, commercial, comptable,
        // caissier, rh, client) : tableau de bord partagé et filtré.
        $user = Auth::user();
        $stats = [];

        if ($user->hasPermission('bande.view')) {
            $stats[] = ['label' => 'Bandes en cours', 'value' => $bandes_en_cours->count(), 'icon' => 'columns', 'color' => 'primary'];
        }

        if ($user->hasPermission('vente.view')) {
            $stats[] = ['label' => 'Ventes (30 jours)', 'value' => Vente::where('created_at', '>=', now()->subDays(30))->count(), 'icon' => 'shopping-cart', 'color' => 'success'];
        }

        if ($user->hasPermission('facture.view')) {
            $stats[] = ['label' => "Chiffre d'affaires (30 jours)", 'value' => number_format((float) Facture::where('date_facturation', '>=', now()->subDays(30))->sum('total_ttc'), 0, ',', ' ').' FCFA', 'icon' => 'file-text', 'color' => 'info'];
        }

        if ($user->hasPermission('depense.view')) {
            $stats[] = ['label' => 'Dépenses (30 jours)', 'value' => number_format((float) \Modules\Depenses\Entities\Depense::where('date_depense', '>=', now()->subDays(30))->sum('montant'), 0, ',', ' ').' FCFA', 'icon' => 'dollar-sign', 'color' => 'warning'];
        }

        if ($user->hasPermission('salarie.view')) {
            $stats[] = ['label' => 'Salariés actifs', 'value' => \Modules\Salarie\Entities\Salaries::where('statut', 1)->count(), 'icon' => 'award', 'color' => 'secondary'];
        }

        if ($user->hasPermission('caisse.view')) {
            $solde = (float) \Modules\GestionCaisse\Entities\Compte::sum('current_balance');
            $stats[] = ['label' => 'Solde des comptes', 'value' => number_format($solde, 0, ',', ' ').' FCFA', 'icon' => 'briefcase', 'color' => 'dark'];
        }

        if ($user->hasPermission('vaccination.view')) {
            $stats[] = ['label' => 'Vaccinations (30 jours)', 'value' => Seance_de_vaccination::where('created_at', '>=', now()->subDays(30))->count(), 'icon' => 'shield', 'color' => 'danger'];
        }

        if ($user->hasPermission('stock_matiere_premiere.view')) {
            $stats[] = ['label' => 'Aliments suivis', 'value' => Matiere_premiere::count(), 'icon' => 'coffee', 'color' => 'primary'];
        }

        // Raccourcis vers les modules autorisés
        $liens = [
            ['label' => 'Bandes', 'route' => 'bande.index', 'icon' => 'columns'],
            ['label' => 'Nutrition', 'route' => 'nutrition.index', 'icon' => 'activity'],
            ['label' => 'Vaccinations', 'route' => 'seanceVaccination.index', 'icon' => 'shield'],
            ['label' => 'Nettoyages', 'route' => 'nettoyage.index', 'icon' => 'droplet'],
            ['label' => 'Mortalité', 'route' => 'mortalite.index', 'icon' => 'alert-triangle'],
            ['label' => 'Ramassages', 'route' => 'ramassage.index', 'icon' => 'inbox'],
            ['label' => 'Produits', 'route' => 'produit.index', 'icon' => 'package'],
            ['label' => 'Stock produits', 'route' => 'stock_produit.index', 'icon' => 'layers'],
            ['label' => 'Achats', 'route' => 'achat.index', 'icon' => 'command'],
            ['label' => 'Fournisseurs', 'route' => 'fournisseur.index', 'icon' => 'user-minus'],
            ['label' => 'Matières premières', 'route' => 'matiere_premiere.index', 'icon' => 'coffee'],
            ['label' => 'Stock aliments', 'route' => 'stock_matiere_premiere.index', 'icon' => 'archive'],
            ['label' => 'Ventes', 'route' => 'vente.index', 'icon' => 'shopping-cart'],
            ['label' => 'Clients', 'route' => 'client.index', 'icon' => 'users'],
            ['label' => 'Paiements', 'route' => 'paiement.index', 'icon' => 'smartphone'],
            ['label' => 'Dépenses', 'route' => 'depenses.index', 'icon' => 'dollar-sign'],
            ['label' => 'Catégories de dépenses', 'route' => 'categorie.index', 'icon' => 'tag'],
            ['label' => 'Caisse', 'route' => 'compte.index', 'icon' => 'briefcase'],
            ['label' => 'Salariés', 'route' => 'salarie.index', 'icon' => 'award'],
            ['label' => 'Paie', 'route' => 'paiement_salaire.index', 'icon' => 'credit-card'],
            ['label' => 'Rentabilité', 'route' => 'rentabilite.index', 'icon' => 'trending-up'],
            ['label' => 'Alertes', 'route' => 'alerte.index', 'icon' => 'bell'],
            ['label' => 'Utilisateurs', 'route' => 'user.index', 'icon' => 'user-check'],
        ];

        $liens = array_values(array_filter($liens, function (array $lien) use ($user) {
            return \Illuminate\Support\Facades\Route::has($lien['route'])
                && $user->canAccessRoute($lien['route']);
        }));

        // Un client voit ses propres factures
        $facturesClient = collect();
        if ($role === 'client') {
            $client = \App\Models\Client::where('id_user', $user->id)->first();
            if ($client) {
                $facturesClient = Facture::where('id_client', $client->id)->latest('date_facturation')->take(10)->get();
            }
        }

        return view('shared.home', compact('role', 'stats', 'liens', 'facturesClient'));
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
}
