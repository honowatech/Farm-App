<?php

namespace App\Http\Controllers;

use App\Models\Matiere_premiere;
use App\Models\Stock_matiere_premiere;
use Illuminate\Http\Request;

class Matiere_premiereController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(request $request)
  {
    $table_data = $request->input('table_data');
    if ( $table_data != null) {
      $ids = explode(',', $table_data);
      $title = "Liste des Matières premières finies";
      $matiere_premieres = Matiere_premiere::whereIn('id',$ids)->get();
      return view('admin.pages.home.infos.matiere_premieres_ajax',compact('matiere_premieres','title'));
    }
    
    $matierePremieres = Matiere_premiere::where('nom_produit', 'like', '%' . $request->input('recherche') . '%')
        ->orWhere('description', 'like', '%' . $request->input('recherche') . '%')
        ->orderBy('updated_at','desc')
        ->paginate(20);
      session()->flash('search',$request->input('recherche'));
    return view('admin.pages.matiere_premieres.index',compact('matierePremieres'));

  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    return view('admin.pages.matiere_premieres.create');

  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $nom = $request->input('nom');
    $description = $request->input('description');
    $prix = $request->input('prix_unitaire');
    $validated = $request->validate([
        'nom' => 'bail|required|unique:batiments|max:255|min:2',
        'description' => 'bail|required|max:255|min:5',
        'prix_unitaire' => 'bail|required|numeric',
    ]);
    $matierePremiere = new Matiere_premiere;
    $matierePremiere->nom_produit = $nom;
    $matierePremiere->description = $description;
    $matierePremiere->prix_unitaire = $prix;
    $matierePremiere->save();
    $message = "matière première ".strtoupper($nom)." créé avec <b class='text-success'> Succès.</b>";
    session()->flash('message',$message);
    return redirect()->route('matiere_premiere.index');
  }



  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    $matierePremiere = Matiere_premiere::findOrFail($id);
    return view('admin.pages.matiere_premieres.info',compact('matierePremiere',));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    $matierePremiere = Matiere_premiere::findOrFail($id);
    return view('admin.pages.matiere_premieres.edit',compact('matierePremiere'));

  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id,request $request)
  {
    $nom = $request->input('nom');
    $description = $request->input('description');
    $prix_unitaire = $request->input('prix_unitaire');
    $validated = $request->validate([
        'nom' => 'bail|required|max:255',
        'description' => 'bail|required|max:255',
        'prix_unitaire' =>'bail|required|numeric',
    ]);
    if ( Matiere_premiere::where('nom_produit',$nom)->whereNotIn('id',[$id])->count() > 0) {
      $validated = $request->validate([
        'nom' => 'unique:batiment',
      ]);
    }
    $matierePremiere = Matiere_premiere::findOrFail($id);
    $matierePremiere->nom_produit = $nom;
    $matierePremiere->description = $description;
    $matierePremiere->prix_unitaire = $prix_unitaire;
    $matierePremiere->save();
    $message = ucfirst(strtoupper($nom))." mis à jour avec <b class='text-success'> Succès.</b> ";
    session()->flash('message',$message);
    return redirect()->route('matiere_premiere.index');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    $matierePremiere = Matiere_premiere::findOrFail($id);
    if ($matierePremiere->statut == 0) {
      $matierePremiere->statut = 1;
      $message = $matierePremiere->name." Activé(e) avec <b class='text-success'> Succès.</b>";
    }else{
      $matierePremiere->statut = 0;
      $message = $matierePremiere->name." Desactivé(e) avec <b class='text-success'> Succès.</b>";
    }
    $matierePremiere->save();
    session()->flash('message',$message);
    return redirect()->back();

  }

}

?>
