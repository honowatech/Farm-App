<?php

namespace App\Http\Controllers;
use App\Models\Nettoyage;
use App\Models\Batiment;
use App\Models\Agent;
use Illuminate\Http\Request;

class NettoyageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
         $nettoyages = Nettoyage::where('date', 'like', '%' . $request->input('recherche') . '%')
                                ->orWhere('verif', 'like', '%' . $valeur_verifie . '%')
                                ->orWhere('created_at', 'like', '%' . $request->input('recherche') . '%')
                                ->orderBy('created_at','desc')
                                ->paginate(20);
      session()->flash('search',$request->input('recherche'));
    return view('admin.pages.nettoyage.index',compact('nettoyages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $agents = Agent::All();
        $batiments = Batiment::All();
        $old_agent = old('agent') == null ? [] : old('agent');
       return view('admin.pages.nettoyage.create',compact('agents','batiments','old_agent'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Batiment = $request->input('batiment');
        $agent = $request->input('agent');
        $verification = $request->input('verif');
        $date = $request->input('date');
        $commentaire = $request->input('commentaire');
            $validated = $request->validate([
            'batiment' => 'bail|required|numeric|min:1',
            'agent' => 'bail|required|min:1',
            'date' => 'bail|date|after_or_equal:today',
            'verif' => 'bail|required|numeric|min:0|max:1'
        ]);
            Nettoyage::create([
                    'id_agent' => json_encode($agent),
                    'id_batiment' => $Batiment,
                    'date' => $date,
                    'verif' => $verification,
                    'description' => $commentaire,
                ]);
        $message = "Séance de néttoyage créée avec <b class='text-success'> Succès.</b>";
        session()->flash('message',$message);
        return redirect()->route('nettoyage.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $nettoyage = Nettoyage::findOrFail($id);
        $id_agents = json_decode($nettoyage->id_agent);
        $first = true;
        $liste = '';
        if (!empty($id_agents)){
            foreach ($id_agents as $id){
                $agent = Agent::find($id);
                if ($agent){
                    if (!$first){
                        $liste .= ', ';
                    }
                    $liste .= $agent->nom;
                    $first = false;
                }
            }
        }
    return view('admin.pages.nettoyage.info',compact('nettoyage','liste'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
     
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $seanceVaccination= Nettoyage::findOrFail($id);
    $seanceVaccination->delete();
    $message = "Séance de nettoyage Supprimée avec <b class='text-success'> Succès.</b>";
    session()->flash('message',$message);
    return redirect()->back();
    }
}
