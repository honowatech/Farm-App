<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Seance_de_vaccination;
use App\Models\Bande;
use App\Models\Veterinaire;
class SeanceVaccinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request )
    {
        $verification = [];
        $lower_search = strtolower(trim($request->input('recherche')));
        if(str_contains('oui',$lower_search) ){
          array_push($verification, 1);
        }
        if( str_contains('non',$lower_search) ){
          array_push($verification, 0);
        }
        $valeur_verifie =  implode($verification) ;
         $seance_Vaccinations = Seance_de_vaccination::where('vaccin_attribuer', 'like', '%' . $request->input('recherche') . '%')
                                ->orWhere('verification', 'like', '%' . $valeur_verifie . '%')
                                ->orWhere('created_at', 'like', '%' . $request->input('recherche') . '%')
                                ->orderBy('created_at','desc')
                                ->paginate(20);
      session()->flash('search',$request->input('recherche'));
    return view('admin.pages.seanceVaccination.index',compact('seance_Vaccinations'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $veterinaires = Veterinaire::All();
        $bandes = Bande::All();
       return view('admin.pages.seanceVaccination.create',compact('veterinaires','bandes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $Bande = $request->input('bande');
    $veterinaire = $request->input('veterinaire');
    $vaccin_attribuer = $request->input('vaccin');
    $verification = $request->input('verif');
    $commentaire = $request->input('commentaire');
        $validated = $request->validate([
        'bande' => 'bail|required|numeric|min:1',
        'veterinaire' => 'bail|required|numeric|min:1',
        'vaccin' => 'bail|required',
        'commentaire'=> 'bail|required|min:5',
        'verif' => 'bail|required'
    ]);
        if (!in_array($verification, [ 0, 1 ])) {
            $message = "<b class='text-danger'> Erreur fatale !<br></b> L'enregistrement a échoué ";
            session()->flash('message',$message);
            $validated = $request->validate([
                'error' => 'required',
            ]);
        }

    $seance = new Seance_de_vaccination;
    $seance->id_veterinaire = $veterinaire;
    $seance->id_bande = $Bande;
    $seance->vaccin_attribuer = $vaccin_attribuer;
    $seance->description = $commentaire;
    $seance->verification = $verification;
    $seance->save();
    $message = "Séance de vaccination créée avec <b class='text-success'> Succès.</b>";
    session()->flash('message',$message);
    return redirect()->route('seanceVaccination.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $seanceVaccination = Seance_de_vaccination::findOrFail($id);
    return view('admin.pages.seanceVaccination.info',compact('seanceVaccination'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $seanceVaccination = Seance_de_vaccination::findOrFail($id);
    return view('admin.pages.seanceVaccination.edit',compact('seanceVaccination'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    $vaccin_attribuer = $request->input('vaccin');
    $verification = $request->input('verif');
    $commentaire = $request->input('commentaire');
        $validated = $request->validate([
        'vaccin' => 'bail|required',
        'commentaire'=> 'bail|required|min:5',
        'verif' => 'bail|required|numeric'
    ]);
       
// dd($nom,$telephone,$telephone_secondaire,$adresse);
// On édite leveterinaire
    $seance = Seance_de_vaccination::findOrFail($id);
    $seance->vaccin_attribuer = $vaccin_attribuer;
    $seance->description = $commentaire;
    $seance->verification = $verification;
    $seance->save();
    $message = "Séance de vaccination mise à jour avec <b class='text-success'> Succès.</b>";
    session()->flash('message',$message);
    return redirect()->route('seanceVaccination.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
     $seanceVaccination= Seance_de_vaccination::findOrFail($id);
    $seanceVaccination->delete();
    $message = "Séance de vaccination Supprimée avec <b class='text-success'> Succès.</b>";
    session()->flash('message',$message);
    return redirect()->back();
  
    }
}
