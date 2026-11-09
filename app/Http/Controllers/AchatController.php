<?php

namespace App\Http\Controllers;

use App\Models\Achat;
use App\Models\Detail_fournisseur;
use App\Models\Fournisseur;
use App\Models\Matiere_premiere;
use App\Models\Stock_matiere_premiere;
use Illuminate\Http\Request;

class AchatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(request $request)
    {
        $achats = Achat::where('prix_unitaire', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('mode_de_paiement', 'like', '%'.$request->input('recherche').'%')
            ->orderBy('updated_at', 'desc')->paginate(20);
        $fournisseurs = Fournisseur::all();
        $aliments = Matiere_premiere::all();
        session()->flash('search', $request->input('recherche'));

        return view('admin.pages.achat.index', compact('achats', 'fournisseurs', 'aliments'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $matieres_premieres = Matiere_premiere::where('statut', 1)->get();

        return view('admin.pages.achat.create ', compact('matieres_premieres'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function ajax(Request $request)
    {
        $table_data = $request->input('table_data');
        $id_produit = $table_data[0];
        $details = Detail_fournisseur::where('id_matiere_premiere', $id_produit)->get();

        return view('admin.pages.achat.ajax', compact('details'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $matiere_premiere = Matiere_premiere::findOrFail($request->input('matiere_premiere'));
        $stock = StockMatierepremiere($matiere_premiere);
        $qte = $request->input('qte');
        $fournisseur = $request->input('fournisseur');
        $prix_unitaire = $request->input('prix_unitaire');
        $mode_paiement = $request->input('mode_paiement');
        $total = $qte * $prix_unitaire;
        $date = $request->input('date');
        session()->put('fournisseur', $fournisseur);
        $validated = $request->validate([
            'matiere_premiere' => 'bail|required|min:1',
            'qte' => 'bail|required|numeric',
            'mode_paiement' => 'bail|required',
            'prix_unitaire' => 'bail|required|numeric',
            'unite' => 'required|string|in:kg,g,sac',
            'date' => 'bail|required|date|before_or_equal:'.now(),
        ]);
        if ($fournisseur != null) {
            $validated = $request->validate([
                'fournisseur' => 'bail|required|min:1',
            ]);
        }
        // Validation additionnelle pour sack_weight si unite = sac
        if ($request->input('unite') === 'sac') {
            $request->validate([
                'sack_weight' => 'required|numeric|min:1',
            ]);
        }
        session()->forget('fournisseur', $fournisseur);
        // dd($matiere_premiere,$qte,$fournisseur,$prix_unitaire,$mode_paiement,$total,$date);
        $unite = $validated['unite'];
        $achat = new Achat;
        $achat->id_matiere_premiere = $matiere_premiere->id;
        $achat->id_fournisseur = $fournisseur;
        $achat->date = $date;
        $achat->prix_unitaire = $prix_unitaire;
        $achat->prix_total = $total;
        $achat->mode_de_paiement = $mode_paiement;
        $mouvement_stock = new Stock_matiere_premiere;
        $mouvement_stock->id_matiere_premiere = $matiere_premiere->id;
        $mouvement_stock->type_mouvement = 'approvisionnement';
        $mouvement_stock->dernier_stock = $stock['valeur_en_stock'];
        if ($unite === 'sac') {
            $poidsParSac = $request->input('sack_weight');
            $quantiteEnKg = $qte * $poidsParSac; // Conversion en KG

            $mouvement_stock->quantite = $quantiteEnKg;
            $mouvement_stock->nouveau_stock = $stock['valeur_en_stock'] + $quantiteEnKg;
            $mouvement_stock->conversion_sac = $request->input('sack_weight');
        } elseif ($unite === 'g') {
            $quantiteEnKg = $qte / 1000; // Conversion de grammes en KG
            $mouvement_stock->quantite = $quantiteEnKg;
            $mouvement_stock->nouveau_stock = $stock['valeur_en_stock'] + $quantiteEnKg;
        } else {
            $mouvement_stock->nouveau_stock = $qte; // Déjà en KG
            $mouvement_stock->quantite = $qte;
        }
        $mouvement_stock->date_entree = $date;
        $mouvement_stock->save();
        $achat->qte = $qte;
        $achat->id_stock_matiere_premiere = $mouvement_stock->id;
        $achat->unite_achat = $request->input('unite');
        $achat->save();
        $message = "Achat enregistré avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->route('achat.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $stock = Achat::findOrFail($id)->stock_matiere_premiere;

        return view('admin.pages.achat.info', compact('stock'));
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
    public function update($id) {}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $achat = Achat::findOrFail($id);
        $achat->delete();
        $message = 'achat n°'.$achat->id." Supprimé avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->back();
    }

    public function search(Request $request)
    {
        $query = Achat::query();

        // Filtrage par dates
        if ($request->filled('date_debut')) {
            $query->where('date', '>=', $request->input('date_debut'));
        }

        if ($request->filled('date_fin')) {
            $query->where('date', '<=', $request->input('date_fin'));
        }

        // Filtrage par catégorie (id_type)
        if ($request->filled('id_fournisseur')) {
            $id_fournisseur = $request->input('id_fournisseur');
            $query->where('id_fournisseur', $id_fournisseur);
        }
        // Filtrage par catégorie (id_type)
        if ($request->filled('id_aliment')) {
            $id_aliment = $request->input('id_aliment');
            $query->where('id_matiere_premiere', $id_aliment);
        }

        // Exécution de la requête et récupération des résultats
        $achats = $query->paginate(30);

        return view('admin.pages.achat.details', compact('achats'))->render();
    }
}
