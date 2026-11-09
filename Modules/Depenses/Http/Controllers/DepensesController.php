<?php

namespace Modules\Depenses\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Depenses\Entities\Categorie;
use Modules\Depenses\Entities\Depense;
use Modules\GestionCaisse\Facades\CompteServiceFacade;
class DepensesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
         $depenses = Depense::Where('date_depense', 'like', '%' . $request->input('recherche') . '%')
        ->paginate(20);
         session()->flash('search',$request->input('recherche'));
        $types = Categorie::all();
         $fournisseurs = Depense::where('fournisseur', '!=', 'autre')
        ->distinct()
        ->pluck('fournisseur');
        return view('depenses::depense.index',compact('depenses','types','fournisseurs'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $caisses = CompteServiceFacade::listComptes();
        $categories = Categorie::all();
        return view('depenses::depense.create',compact('categories','caisses'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
      $request->validate([
      'id_type_depense' => 'required|bail|numeric',
      'nature_depense'=>'required|bail|string',
      'prix_unitaire'=>'required|bail|numeric|min:0',
      'date'=>'required|bail|date|before_or_equal:today',
      'id_caisse' => 'nullable|exists:gestion_caisse_comptes,id',

    ],[
      'id_type_depense.required'=>'Veuillez entrer un type de dépense',
     ' nature_depense.required'=>'Veuillez entrer la nature de la dépense',
     ' prix_unitaire.required'=>'Veuillez entrer le montant de la dépense' ,
      'date.required'=>'Veuillez entrer la date de la dépense',
      'date.before_or_equal' => 'La date de la dépense ne peut pas être postérieure à aujourd\'hui',

      'nature_depense.required'=>'Veuillez donné le motif de la dépense',
    ]);
        try {
            $depense = new Depense;
            $depense->montant = $request->input('prix_unitaire');
            $depense->date_depense = $request->input('date');
            $depense->id_saver = auth()->user()->id;
            $depense->id_categorie = $request->input('id_type_depense');
            $depense->motif_depense = $request->input('nature_depense');
            $depense->fournisseur = $request->filled('fournisseur') ? $request->input('fournisseur') : 'autre';
            $depense->description = $request->filled('description') ? $request->input('description') : "Aucune description pour cette dépense";
            if ($request->filled('id_caisse')) {
                if (!CompteServiceFacade::verifierSoldeSuffisant($request->input('id_caisse'), $depense->montant, $depense->id)) {
                    return redirect()->back()->withErrors(['error' => 'Le solde de la caisse est insuffisant pour cette dépense.']);
                }
                $depense->id_compte = $request->input('id_caisse');
            }

            $depense->save();
            return redirect()->route('depenses.index')->with('message', 'Dépense enregistrée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('depenses::depense.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('depenses::depense.edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
    public function search(Request $request)
        {
            $query = Depense::query();

            // Filtrage par dates
            if ($request->filled('date_debut')) {
                $query->where('date_depense', '>=', $request->input('date_debut'));
            }

            if ($request->filled('date_fin')) {
                $query->where('date_depense', '<=', $request->input('date_fin'));
            }

            // Filtrage par catégorie (id_type)
            if ($request->filled('id_type')) {
                $id_type = $request->input('id_type');
                $query->where('id_categorie', $id_type);
            }

            // Filtrage par fournisseur
            if ($request->filled('fournisseur')) {
                $fournisseur = $request->input('fournisseur');
                $query->where('fournisseur', $fournisseur);
            }

            // Exécution de la requête et récupération des résultats
            $depenses = $query->paginate(30);

            return view('depenses::depense.infos', compact('depenses'))->render();
        }
        public function getPrixUnitaire($id)
            {
                // Cherche la catégorie de dépense par son ID
                $categorie = Categorie::find($id);

                // Si la catégorie est trouvée, retourne son prix unitaire, sinon retourne 0
                if ($categorie) {
                    return response()->json(['prix_unitaire' => $categorie->prix_unitaire ?? 0]);
                }

                return response()->json(['prix_unitaire' => 0]);
            }

}
