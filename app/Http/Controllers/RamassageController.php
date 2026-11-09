<?php

namespace App\Http\Controllers;

use App\Models\Bande;
use App\Models\Batiment;
use App\Models\Type_animal;
use App\Models\Produit;
use App\Models\Derive_produit;
use App\Models\Animal;
use App\Models\Ramassage;
use App\Models\Agent;
use App\Models\Unite;
use App\Models\Stock_produit;
use App\Models\User;
use App\Models\Typeusers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RamassageController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(Request $request)
  {
    $bandes = Bande::all();
    $ramassages = Ramassage::where('commentaire', 'like', '%' . $request->input('recherche') . '%')
        ->orWhere('to_do_date', 'like', '%' . $request->input('recherche') . '%')
        ->orderBy('updated_at','desc')
        ->paginate(20);
      session()->flash('search',$request->input('recherche'));
    return view('admin.pages.ramassage.index',compact('ramassages','bandes'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create(Request $request)
  {
    //$ids_type_user = [];
    //$typesuser = Typeusers::where(strtoupper('libelle'),strtoupper('agent'))->get();
    //foreach ($typesuser as $typeuser) {
    //  // code...
    //  array_push($ids_type_user,$typeuser->id);
    //}
    //$agents = User::whereIn('id_type_user',$ids_type_user)->get();
    $agents = Agent::all();
    $produits = Produit::get();
    $bandes = Bande::get();
    if(session()->has('id_produit')||session()->has('id_bande')){
      $derives = Derive_produit::where('id_produit',[session()->get('id_produit')])->orderBy('min')->get();
      return view('admin.pages.ramassage.create',compact('produits','agents','derives','bandes'));
    }
    return view('admin.pages.ramassage.create',compact('produits','agents','bandes'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function ajax(Request $request)
  {
    $table_data = $request->input('table_data');
    $id_produit = $table_data[0];
    $derives = Derive_produit::where('id_produit',[$id_produit])->orderBy('min')->get();
    return view('admin.pages.ramassage.ajax',compact('derives','table_data'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function ajax2(Request $request)
  {
    $table_data = $request->input('table_data');
    $id_bande = $table_data[0];
    if($id_bande==0){
    $produits = Produit::where('id_type_animal',[0])->get();
      return view('admin.pages.ramassage.ajax2',compact('produits','table_data'));
    }
    $bande = Bande::findOrFail($id_bande);
    if ($bande->type_animal == null) {
      return "<option>cette bande ne peut pas être utilisée</option>";
    }
    $produits = Produit::where('id_type_animal',[$bande->type_animal->id])->get();
    return view('admin.pages.ramassage.ajax2',compact('produits','table_data'));
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function ajax3(Request $request)
  {
    $color = 'danger';
    $number = 0; 
    $letter = 'ramassage';
    $verb = 'effectué';
    $table_data = $request->input('table_data');
    $id_bande = $table_data[0];
    $id_produit = $table_data[1];
    $date = $table_data[2];
    if(in_array(0,$table_data)){
      return "Remplir Les champs pour avoir des infos supplementaires";
    }else{
      $ramassages = Ramassage::where('id_bande',$id_bande)
                ->where('id_produit',$id_produit)
                ->get();
      foreach($ramassages as $ramassage){
        if ($date == explode(' ', $ramassage->to_do_date)[0]) {
          $number++;
        }
      }
      if ($number>0) {
        $color = 'success';
        if ($number>1) {
          $letter = 'ramassages';
          $verb = 'effectués';
        }
      } 
    }
    return view('admin.pages.ramassage.ajax3',compact('number','color','letter','verb'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $full_error = 0;
    $ids_accomparer = [];
    $id_bande = $request->input('id_bande');
    $id_produit = $request->input('id_produit');
    $id_prestataire = $request->input('id_prestataire');
    $date = $request->input('date').' '.$request->input('heure');
    $ids = explode('_',$request->input('ids'));
    $qtes = explode('_',$request->input('valeurs'));
    $commentaire = $request->input('commentaire');
    session()->flash('id_produit',$id_produit);
    session()->flash('id_bande',$id_bande);
// vérifications des champs de base
    $validated = $request->validate([
        'id_produit' => 'bail|required|numeric|min:1',
        'id_bande' => 'bail|required|numeric|min:1',
        'date' => 'bail|required|date|before_or_equal:'.date_format(now(),'Y-m-d'),
        'heure' => 'bail|required',
        'id_prestataire' => 'bail|required|numeric|min:1',
        'ids' => 'bail|required',
        'valeurs' => 'bail|required',
    ]);
    $derives = Derive_produit::where('id_produit',[$id_produit])->orderBy('min')->get();
// vérification des valeus de quantité
    foreach ($derives as $derive) {
      $validated = $request->validate([
          'derive_'.$derive->id => 'bail|required|numeric|min:0',
      ]);
      if ($request->input('derive_'.$derive->id) != 0) {
        $full_error ++;
      }
      array_push($ids_accomparer,strval($derive->id));
    }
    if($full_error==0){
      $validated = $request->validate([
          'full_error' => 'bail|required|numeric|min:0',
      ]);
    }
// vérification des ids des dérivés 
    if ($ids_accomparer != $ids) {
      $message = "<b class='text-danger'> Une erreur s'est produite ! </b> <br> Actualiser le page";
      session()->flash('message',$message);
      $validated = $request->validate([
          'error' => 'bail|required',
      ]);
      session()->forget('message',$message);
    }
// vérification des commentaires
    if ($commentaire != null) {
      $validated = $request->validate([
          'commentaire' => 'bail|required|min:5',
      ]);
    }
    session()->forget('id_produit');
    //dd($id_produit,$qtes,$ids,$id_prestataire,$commentaire,$id_bande,$date);
    $ramassage = new Ramassage;
    $ramassage->id_produit = $id_produit;
    $ramassage->id_bande = $id_bande;
    $ramassage->ids_derive = json_encode($ids);
    $ramassage->valeurs_derive = json_encode($qtes);
    $ramassage->id_prestataire = $id_prestataire;
    $ramassage->commentaire = $commentaire;
    $ramassage->id_saver = Auth::user()->id;
    $ramassage->to_do_date = $date;
    $ramassage->statut = 1;
    $ramassage->save();
    for ($index=0; $index < count($ids); $index++) {
      if ($qtes[$index] == 0)
        continue;
  ///////////////////////////////////////////////////////////////////////////////////////////// 
      $valeur_en_stock = 0;
      $ajout = 0;
      $retire = 0;
      $derive_produit = Derive_produit::findOrFail($ids[$index]);
  /////////////////////////////////////////////////////////////////////////////////////////////
              foreach ($derive_produit->mouvements_de_stock as $stock) {
                  if ($stock->type_mouvement == 'approvisionnement') {
                      $ajout += $stock->qte;
                  }else{
                      $retire += $stock->qte;
                  }
              }
          $valeur_en_stock = $ajout - $retire;
  /////////////////////////////////////////////////////////////////////////////////////////////
      $stock = new Stock_produit;
      $stock->id_produit = $id_produit;
      $stock->qte = $qtes[$index];
      $stock->id_derive_produit = $ids[$index];
      $stock->date_entree = $date;
      $stock->dernier_stock = $valeur_en_stock;
      $stock->nouveau_stock = $valeur_en_stock+intval($qtes[$index]);
      $stock->type_mouvement = 'approvisionnement';
      $stock->save();
  /////////////////////////////////////////////////////////////////////////////////////////////
    }
    $message = "Ramassage créé avec <b class='text-success'> Succès.</b>";
    session()->flash('message',$message);
    return redirect()->back();
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    $ramassage = Ramassage::findOrFail($id);
    return view('admin.pages.ramassage.info',compact('ramassage'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    $unites = Unite::orderBy('libelle')->get();
    $derive_produit = Ramassage::findOrFail($id);
    return view('admin.pages.ramassage.edit',compact('derive_produit','unites'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request,$id)
  {
    
    $nom = $request->input('nom');
    $prix_unitaire = $request->input('prix_unitaire');
    $min = $request->input('min');
    $max = $request->input('max');
    $id_unite = $request->input('unite');
    $validated = $request->validate([
        'nom' => 'bail|required|unique:produits|max:255|min:4',
        'prix_unitaire' => 'bail|required|numeric',
        'min' => 'bail|required|numeric|min:0|lte:'.$max,
        'max' => 'bail|required|numeric|min:0|gte:'.$min,
        'unite' => 'bail|required|numeric|min:1',
    ]);
   // dd($id_produit,$nom,$prix_unitaire,$min,$max,$id_unite);
    $derive = Ramassage::findOrFail($id);;
    $derive->nom = $nom;
    $derive->prix_unitaire = $prix_unitaire;
    $derive->min = $min;
    $derive->max = $max;
    $derive->id_unite = $id_unite;
    $derive->save();
    $message = $derive->produit->nom."_".ucfirst($derive->nom)." Modifié avec <b class='text-success'> Succès.</b>";
    session()->flash('message',$message);
    return redirect()->route('ramassage.show',$derive->id);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    $ramassage = Ramassage::findOrFail($id);
    $ramassage->statut = 0;
    $message = "Ramassage  Rendu 'Supprimé' avec <b class='text-success'> Succès.</b>";
    $ramassage->save();
    session()->flash('message',$message);
    return redirect()->back();
  }

  public function search(Request $request)
  {
      $query = Ramassage::query();

      // Filtrage par dates
      if ($request->filled('date_debut')) {
          $query->where('to_do_date', '>=', $request->input('date_debut'));
      }

      if ($request->filled('date_fin')) {
          $query->where('to_do_date', '<=', $request->input('date_fin'));
      }

      // Filtrage par catégorie (id_type)
      if ($request->filled('id_bande')) {
          $id_bande = $request->input('id_bande');
          $query->where('id_bande', $id_bande);
      }

      // Exécution de la requête et récupération des résultats
      $ramassages = $query->paginate(30);

      return view('admin.pages.ramassage.details', compact('ramassages'))->render();
  }

  
}