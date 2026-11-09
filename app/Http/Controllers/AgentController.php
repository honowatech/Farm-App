<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $agents = Agent::where('nom', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('adresse', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('telephone', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('telephone_secondaire', 'like', '%'.$request->input('recherche').'%')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        session()->flash('search', $request->input('recherche'));

        return view('admin.pages.agents.index', compact('agents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.pages.agents.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $nom = $request->input('nom');
        $telephone = str_replace(' ', '', $request->input('telephone'));
        $telephone_secondaire = str_replace(' ', '', $request->input('telephone_secondaire'));
        $adresse = $request->input('adresse');
        $validated = $request->validate([
            'nom' => 'bail|required|max:255',
            'telephone' => 'bail|required|unique:agents|regex:/^[6,2][0-9]{8}$/',
            'adresse' => 'bail|required|min:4',
        ]);
        if ($telephone_secondaire != null) {
            $validated = $request->validate([
                'telephone' => 'bail|unique:agents|regex:/^[6,2][0-9]{8}$/',
            ]);
        }
        // On enregistre l' agent
        $agent = new Agent;
        $agent->nom = $nom;
        $agent->telephone_secondaire = $telephone_secondaire;
        $agent->telephone = $telephone;
        $agent->adresse = $adresse;
        $agent->statut = 1;
        $agent->save();
        $message = 'Vétérinaire'.' '.name($nom)."créé avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->route('agent.show', $agent->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $agent = Agent::findOrFail($id);

        return view('admin.pages.agents.info', compact('agent'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $agent = Agent::findOrFail($id);

        return view('admin.pages.agents.edit', compact('agent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $nom = $request->input('nom');
        $telephone = str_replace(' ', '', $request->input('telephone'));
        $telephone_secondaire = str_replace(' ', '', $request->input('telephone_secondaire'));
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
            if (Agent::where('telephone_secondaire', $telephone_secondaire)->whereNotIn('id', [$id])->exists()) {
                $validated = $request->validate([
                    'telephone_secondaire' => 'unique:agents',
                ]);
            }
        }
        if (Agent::where('telephone', $telephone)->whereNotIn('id', [$id])->exists()) {
            $validated = $request->validate([
                'telephone' => 'unique:agents',
            ]);
        }
        // dd($nom,$telephone,$telephone_secondaire,$adresse);
        // On édite leagent
        $agent = Agent::findOrFail($id);
        $agent->nom = $nom;
        $agent->telephone = $telephone;
        $agent->telephone_secondaire = $telephone_secondaire;
        $agent->adresse = $adresse;
        $agent->save();
        $message = 'Agent'.' '.name($nom)."mis à jour avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->route('agent.show', $agent->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $agent = Agent::findOrFail($id);
        if ($agent->statut == 0) {
            $agent->statut = 1;
            $message = 'Agent '.name($agent->nom)." Activé(e) avec <b class='text-success'> Succès.</b>";
        } else {
            $agent->statut = 0;
            $message = 'Agent '.name($agent->nom)." Desactivé(e) avec <b class='text-success'> Succès.</b>";
        }
        $agent->save();
        session()->flash('message', $message);

        return redirect()->back();
    }
}
