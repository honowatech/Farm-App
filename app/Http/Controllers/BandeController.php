<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Bande;
use App\Models\Batiment;
use App\Models\Produit;
use App\Models\Stock_produit;
use App\Models\Type_animal;
use App\Models\Type_produit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BandeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $annimals = Type_animal::All();
        $bandes = Bande::where('nom', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('nbre_sujet', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('age_depart', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('prix_acquisition', 'like', '%'.$request->input('recherche').'%')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        session()->flash('search', $request->input('recherche'));

        return view('admin.pages.bande.index', compact('bandes', 'annimals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $batiments = Batiment::get();
        $types_animal = Type_animal::get();

        return view('admin.pages.bande.create', compact('batiments', 'types_animal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'nom' => 'bail|required|unique:bandes|max:255|min:4',
            'type_animal' => 'bail|required|integer|exists:type_animals,id', // Vérifie que le type d'animal existe
            'age_depart' => 'bail|required|numeric|min:1',
            'batiment' => 'bail|required|integer|exists:batiments,id', // Vérifie que le bâtiment existe
            'unite_age_depart' => 'bail|required|string|in:jour,semaine,mois,annee',
            'nbre_sujet' => 'bail|required|numeric|min:1',
            'date' => 'bail|required|date|before_or_equal:today', // La date ne peut pas être dans le futur
            'prix_acquisition' => 'bail|required|numeric|min:0',
        ]);

        // Préparation des données
        $nom = $request->input('nom');
        $id_type_animal = $request->input('type_animal');
        $id_batiment = $request->input('batiment');

        // Ajoute l'heure actuelle à la date de lancement pour l'enregistrer en format complet Y-m-d H:i:s
        $date_lancement_bande = Carbon::parse($request->input('date'))->setTimeFrom(Carbon::now());

        $age_depart = $request->input('age_depart');
        $nbre_sujet = $request->input('nbre_sujet');
        $prix_acquisition = $request->input('prix_acquisition');
        $unite_age_depart = $request->input('unite_age_depart');
        // Création de la bande
        $bande = Bande::create([
            'nom' => $nom,
            'id_type_animal' => $id_type_animal,
            'id_batiment' => $id_batiment,
            'date_lancement_bande' => $date_lancement_bande,
            'age_depart' => $age_depart,
            'nbre_sujet' => $nbre_sujet,
            'statut_bande' => 'encours', // Par défaut la bande est "en cours"
            'unite_age_depart' => $unite_age_depart,
            'prix_acquisition' => $prix_acquisition,
        ]);

        // Création des animaux associés à la bande
        for ($numero_animal = 1; $numero_animal <= $nbre_sujet; $numero_animal++) {
            Animal::create([
                'id_type_animal' => $id_type_animal,
                'id_bande' => $bande->id,
                'age' => $age_depart,
                'statut_animal' => 'enelevage', // Statut par défaut
                'numero_animal' => strtotime(now()).'_'.$bande->id.'_'.$numero_animal, // Génère un identifiant unique pour chaque animal
            ]);
        }

        // Message de succès
        session()->flash('message', strtoupper($nom)." créé avec <b class='text-success'> Succès.</b>");

        // Redirection vers la liste des bandes
        return redirect()->route('bande.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $bande = Bande::with('animal', 'batiment')->findOrFail($id);

        return view('admin.pages.bande.info', compact('bande'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {}

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $bande = Bande::findOrFail($id);
        $bande->delete();
        $message = $bande->nom." Supprimé avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->back();
    }

    public function gestion_bande_get_form(Request $request, $id)
    {

        $bande = Bande::findOrFail($id);

        return view('admin.pages.bande.confirmation', compact('bande'));
    }

    public function teminer_bande(Request $request, $id)
    {
        $bande = Bande::findOrFail($id);
        $bande_id_annimal = $bande->id_type_animal;
        $bande_nbre_produit = $bande->nbre_sujet;

        // Récupérer le produit associé, s'il existe
        $produit = Produit::where('id_type_animal', $bande_id_annimal)->first(); // Utiliser first() pour éviter des requêtes multiples

        // Récupérer directement l'id du type de produit
        $id_type_produit = Type_produit::where('nom', 'animal')->value('id'); // Ne récupérer que l'id au lieu de tout le modèle

        if (! $produit) {
            // Si aucun produit n'existe, en créer un nouveau
            $nouveau_produit = new Produit;
            $nouveau_produit->id_type_animal = $bande_id_annimal;
            $nouveau_produit->prix_unitaire = 1;
            $nouveau_produit->nom = $bande->type_animal->nom;
            $nouveau_produit->id_type_produit = $id_type_produit; // Assigner l'id directement
            $nouveau_produit->save();

            $produit = $nouveau_produit; // Mettre à jour produit avec le nouveau créé
        }

        // Récupérer stock_produit associé au produit, s'il existe
        $stock_produit = Stock_produit::where('id_produit', $produit->id)->latest()->first(); // Utiliser latest() pour obtenir l'entrée la plus récente

        if (! $stock_produit) {
            // Si aucun stock_produit n'existe, en créer un nouveau
            $stock_produit = new Stock_produit;
            $stock_produit->id_produit = $produit->id;
            $stock_produit->type_mouvement = 'approvisionnement';
            $stock_produit->qte = $bande_nbre_produit;
            $stock_produit->dernier_stock = 0;
            $stock_produit->nouveau_stock = $bande_nbre_produit;
            $stock_produit->date_entree = now()->format('Y-m-d');
            $stock_produit->save();
        } else {
            // Mettre à jour le stock_produit existant
            $stock_produit_new = new Stock_produit;
            $stock_produit_new->type_mouvement = 'approvisionnement';
            $stock_produit_new->qte = $bande_nbre_produit;
            $stock_produit_new->id_produit = $produit->id;
            $stock_produit_new->dernier_stock = $stock_produit->nouveau_stock;
            $stock_produit_new->nouveau_stock = $stock_produit->nouveau_stock + $bande_nbre_produit;
            $stock_produit_new->date_entree = now()->format('Y-m-d');
            $stock_produit_new->save();
        }
        $bande->statut_bande = 'terminee';
        $bande->save();

        // Définir le message de succès et rediriger
        session()->flash('message', "Bande stoppée avec <b class='text-success'> Succès.</b>");

        return redirect()->back();
    }

    public function gestion_bande(Request $request, $id) {}

    public function search(Request $request)
    {
        $query = Bande::query();

        // Filtrage par dates
        if ($request->filled('date_debut')) {
            $query->where('date_lancement_bande', '>=', $request->input('date_debut'));
        }

        if ($request->filled('date_fin')) {
            $query->where('date_lancement_bande', '<=', $request->input('date_fin'));
        }
        // Filtrage par bande (id_bande)
        if ($request->filled('id_type_animal')) {
            $id_type_animal = $request->input('id_type_animal');
            $query->where('id_type_animal', $id_type_animal);
        }

        // Exécution de la requête et récupération des résultats
        $bandes = $query->paginate(30);

        return view('admin.pages.bande.details', compact('bandes'))->render();
    }
}
