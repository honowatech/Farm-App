<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Veterinaire;
use App\Models\Matiere_premiere;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class VeterinaireController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(Request $request)
  {
    $veterinaires = Veterinaire::where('nom', 'like', '%' . $request->input('recherche') . '%')
        ->orWhere('adresse', 'like', '%' . $request->input('recherche') . '%')
        ->orWhere('telephone', 'like', '%' . $request->input('recherche') . '%')
        ->orWhere('telephone_secondaire', 'like', '%' . $request->input('recherche') . '%')
        ->orderBy('updated_at','desc')
        ->paginate(20);
      session()->flash('search',$request->input('recherche'));
    return view('admin.pages.veterinaires.index',compact('veterinaires'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    return view('admin.pages.veterinaires.create');
  }
  
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $nom = $request->input('nom');
    $telephone = str_replace(' ','',$request->input('telephone'));
    $telephone_secondaire = str_replace(' ','',$request->input('telephone_secondaire'));
    $adresse = $request->input('adresse');
    $validated = $request->validate([
        'nom' => 'bail|required|max:255',
        'telephone' => 'bail|required|unique:veterinaires|regex:/^[6,2][0-9]{8}$/',
        'adresse' => 'bail|required|min:4',
    ]);
    if ($telephone_secondaire != null) {
      $validated = $request->validate([
          'telephone' => 'bail|unique:veterinaires|regex:/^[6,2][0-9]{8}$/',
      ]);
    }
// dd($nom,$telephone,$telephone_secondaire,$adresse);
// On enregistre le veterinaire
    $veterinaire = new Veterinaire;
    $veterinaire->nom = $nom;
    $veterinaire->telephone_secondaire = $telephone_secondaire;
    $veterinaire->telephone = $telephone;
    $veterinaire->adresse = $adresse;
    $veterinaire->statut = 1;
    $veterinaire->save();
    $message = "Vétérinaire"." ".name($nom)."créé avec <b class='text-success'> Succès.</b>";
    session()->flash('message',$message);
    return redirect()->route('veterinaire.show',$veterinaire->id);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    $veterinaire = Veterinaire::findOrFail($id);
    return view('admin.pages.veterinaires.info',compact('veterinaire'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    $veterinaire = Veterinaire::findOrFail($id);
    return view('admin.pages.veterinaires.edit',compact('veterinaire'));
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
    $telephone = str_replace(' ','',$request->input('telephone'));
    $telephone_secondaire = str_replace(' ','',$request->input('telephone_secondaire'));
    $adresse = $request->input('adresse');
    $validated = $request->validate([
        'nom' => 'bail|required|max:255',
        'telephone' => 'bail|required|regex:/^[6,2][0-9]{8}$/',
        'adresse' => 'bail|required|min:4',
    ]);
    if ($telephone_secondaire != null) {
      $validated = $request->validate([
          'telephone_secondaire' => 'bail|regex:/^[6,2][0-9]{8}$/',
      ]);
      if (Veterinaire::where('telephone_secondaire',$telephone_secondaire)->whereNotIn('id',[$id])->exists()) {
        $validated = $request->validate([
            'telephone_secondaire' => 'unique:veterinaires',
        ]);
      }
    }
    if ( Veterinaire::where('telephone',$telephone)->whereNotIn('id',[$id])->exists()) {
      $validated = $request->validate([
        'telephone' => 'unique:veterinaires',
      ]);
    }
// dd($nom,$telephone,$telephone_secondaire,$adresse);
// On édite leveterinaire
    $veterinaire = Veterinaire::findOrFail($id);
    $veterinaire->nom = $nom;
    $veterinaire->telephone = $telephone;
    $veterinaire->telephone_secondaire = $telephone_secondaire;
    $veterinaire->adresse = $adresse;
    $veterinaire->save();
    $message = "veterinaire"." ".name($nom)."mis à jour avec <b class='text-success'> Succès.</b>";
    session()->flash('message',$message);
    return redirect()->route('veterinaire.show',$veterinaire->id);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    $veterinaire = Veterinaire::findOrFail($id);
    if ($veterinaire->statut == 0) {
      $veterinaire->statut = 1;
      $message = "Vétérinaire ".name($veterinaire->nom)." Activé(e) avec <b class='text-success'> Succès.</b>";
    }else{
      $veterinaire->statut = 0;
      $message = "Vétérinaire ".name($veterinaire->nom)." Desactivé(e) avec <b class='text-success'> Succès.</b>";
    }
    $veterinaire->save();
    session()->flash('message',$message);
    return redirect()->back();
  }
}
