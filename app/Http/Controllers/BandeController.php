<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Bande;
use App\Models\Batiment;
use App\Models\Produit;
use App\Models\Stock_produit;
use App\Models\Type_animal;
use App\Models\Type_produit;
use App\Http\Requests\StoreBandeRequest;
use App\Support\TenantContext;
use App\Support\TenantQuota;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BandeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $annimals = Type_animal::orderBy('nom')->get();
        $bandes = Bande::where(function ($query) use ($request) {
            $query->where('nom', 'like', '%'.$request->input('recherche').'%')
                ->orWhere('nbre_sujet', 'like', '%'.$request->input('recherche').'%')
                ->orWhere('age_depart', 'like', '%'.$request->input('recherche').'%')
                ->orWhere('prix_acquisition', 'like', '%'.$request->input('recherche').'%');
        })
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
    public function store(StoreBandeRequest $request)
    {
        $validated = $request->validated();

        app(TenantQuota::class)->assertWithinLimit('bandes', Bande::count());

        // Ajoute l'heure actuelle à la date de lancement pour l'enregistrer en
        // format complet Y-m-d H:i:s.
        $dateLancement = Carbon::parse($validated['date'])->setTimeFrom(Carbon::now());

        $bande = DB::transaction(function () use ($validated, $dateLancement) {
            $bande = Bande::create([
                'nom' => $validated['nom'],
                'id_type_animal' => $validated['type_animal'],
                'id_batiment' => $validated['batiment'],
                'date_lancement_bande' => $dateLancement,
                'age_depart' => $validated['age_depart'],
                'nbre_sujet' => $validated['nbre_sujet'],
                'statut_bande' => 'encours',
                'unite_age_depart' => $validated['unite_age_depart'],
                'prix_acquisition' => $validated['prix_acquisition'],
            ]);

            $this->createAnimals($bande, (int) $validated['nbre_sujet'], $validated['age_depart'], $validated['type_animal']);

            return $bande;
        });

        session()->flash('message', strtoupper($validated['nom'])." créé avec <b class='text-success'> Succès.</b>");

        return redirect()->route('bande.index');
    }

    /**
     * Crée les animaux de la bande par insertion en masse (une seule requête
     * par lot) au lieu d'une requête par animal.
     */
    private function createAnimals(Bande $bande, int $nbreSujet, $ageDepart, $idTypeAnimal): void
    {
        if ($nbreSujet <= 0) {
            return;
        }

        $now = now();
        $timestamp = strtotime($now);
        $rows = [];

        for ($numero = 1; $numero <= $nbreSujet; $numero++) {
            $rows[] = [
                'id_type_animal' => $idTypeAnimal,
                'id_bande' => $bande->id,
                'age' => $ageDepart,
                'statut_animal' => 'enelevage',
                'numero_animal' => $timestamp.'_'.$bande->id.'_'.$numero,
                'tenant_id' => app(TenantContext::class)->id(),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 1000) as $chunk) {
            Animal::insert($chunk);
        }
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
            $nouveau_produit->nom = $bande->animal->nom;
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
