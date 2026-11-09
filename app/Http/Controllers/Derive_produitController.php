<?php

namespace App\Http\Controllers;

use App\Models\bande;
use App\Models\Batiment;
use App\Models\Type_animal;
use App\Models\Produit;
use App\Models\Derive_produit;
use App\Models\Animal;
use App\Models\Ramassage;
use App\Models\Unite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Derive_produitController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(Request $request)
  {
    
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create(Request $request)
  {
    $unites = Unite::orderBy('libelle')->get();
    $produit = Produit::findOrFail($request->input('produit'));
    return view('admin.pages.derive_produit.create',compact('produit','unites'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $id_produit = $request->input('produit');
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
// On vérifie si effectivement on a bien la valeur de produit//
    $message = "<b class='text-danger'> Une erreur s'est produite.</b>";
    session()->flash('message',$message);
    $validated = $request->validate([
        'produit' => 'bail|required|numeric|min:1',
    ]);
    session()->forget('message');

   // dd($id_produit,$nom,$prix_unitaire,$min,$max,$id_unite);
    $derive = new Derive_produit;
    $derive->id_produit = $id_produit;
    $derive->nom = $nom;
    $derive->prix_unitaire = $prix_unitaire;
    $derive->min = $min;
    $derive->max = $max;
    $derive->id_unite = $id_unite;
    $derive->save();
    $message = $derive->produit->nom."_".ucfirst($derive->nom)." créé avec <b class='text-success'> Succès.</b>";
    session()->flash('message',$message);
    return redirect()->route('derive_produit.show',$derive->id);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    $derive_produit = Derive_produit::findOrFail($id);
    $produit = Produit::findOrFail($derive_produit->id_produit);
    return view('admin.pages.derive_produit.info',compact('derive_produit','produit'));
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
    $derive_produit = Derive_produit::findOrFail($id);
    return view('admin.pages.derive_produit.edit',compact('derive_produit','unites'));
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
    $derive = Derive_produit::findOrFail($id);;
    $derive->nom = $nom;
    $derive->prix_unitaire = $prix_unitaire;
    $derive->min = $min;
    $derive->max = $max;
    $derive->id_unite = $id_unite;
    $derive->save();
    $message = $derive->produit->nom."_".ucfirst($derive->nom)." Modifié avec <b class='text-success'> Succès.</b>";
    session()->flash('message',$message);
    return redirect()->route('derive_produit.show',$derive->id);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    $derive_produit = Derive_produit::findOrFail($id);
    if ($derive_produit->statut == 0) {
      $derive_produit->statut = 1;
      $message = $derive_produit->nom." Rendu 'Visible' avec <b class='text-success'> Succès.</b>";
    }else{
      $derive_produit->statut = 0;
      $message = $derive_produit->nom."  Rendu 'Invisible' avec <b class='text-success'> Succès.</b>";
    }
    $derive_produit->save();
    session()->flash('message',$message);
    return redirect()->back();
  }
  
}
