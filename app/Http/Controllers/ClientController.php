<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Typeusers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $clients = Client::where('nom', 'like', '%'.$request->input('recherche').'%')

            ->orWhere('email', 'like', '%'.$request->input('recherche').'%')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        session()->flash('search', $request->input('recherche'));

        return view('admin.pages.client.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.pages.client.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $id_type_user = Typeusers::whereIn('libelle', ['client', 'clients'])->first()->id;
        $nom = $request->input('nom');

        $email = $request->input('email');
        $password = $request->input('password');
        $telephone = str_replace(' ', '', $request->input('telephone'));
        $adresse = $request->input('adresse');
        $validated = $request->validate([
            'nom' => 'bail|required|max:255',
            'telephone' => 'bail|required|unique:clients|regex:/^[6,2][0-9]{8}$/',

            'email' => 'bail|required|email|unique:clients|unique:users|max:255',
            'password' => 'bail|required|min:8',
            'adresse' => 'bail|required|min:4',
        ]);
        // dd($nom,$email,$password,$telephone,$adresse,);
        // on crèe son compte utilisateur
        $user = new User;
        $user->name = $nom;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->id_type_user = $id_type_user;
        $user->save();
        // On enregistre le client
        $client = new Client;
        $client->nom = $nom;
        $client->telephone = $telephone;
        $client->adresse = $adresse;
        $client->email = $email;
        $client->id_user = $user->id;
        $client->save();
        $message = 'Client(e) '.strtoupper($nom).' créé(e) avec succès.';
        session()->flash('message', $message);

        return redirect()->route('client.show', $client->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $client = Client::findOrFail($id);
        session()->put('info', ['client', $client]);
        $user = User::findOrFail($client->id_user);

        return view('admin.pages.user.info', compact('user', 'client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        session()->put('info', ['client', $client]);

        return view('admin.pages.client.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $id_user = User::findOrFail(Client::findOrFail($id)->id_user)->id;
        $nom = $request->input('nom');
        $email = $request->input('email');
        $telephone = str_replace(' ', '', $request->input('telephone'));
        $adresse = $request->input('adresse');
        $validated = $request->validate([
            'nom' => 'bail|required|max:255',
            'telephone' => 'bail|required|regex:/^[6,2][0-9]{8}$/',
            'email' => 'bail|required|max:255',
            'adresse' => 'bail|required|min:4',
        ]);
        if (Client::where('email', $email)->whereNotIn('id', [$id])->exists()) {
            $validated = $request->validate([
                'email' => 'unique:clients',
            ]);
        }
        if (User::where('email', $email)->whereNotIn('id', [$id_user])->exists()) {
            $validated = $request->validate([
                'email' => 'unique:users',
            ]);
        }
        if (Client::where('telephone', $telephone)->whereNotIn('id', [$id])->exists()) {
            $validated = $request->validate([
                'telephone' => 'unique:clients',
            ]);
        }
        // dd($nom,$prenom,$email,$telephone,$adresse,);
        // on édite son compte utilisateur
        $user = User::findOrFail($id_user);
        $user->name = $nom;
        $user->email = $email;
        $user->save();
        // On édite le client
        $client = Client::findOrFail($id);
        $client->nom = $nom;
        $client->telephone = $telephone;
        $client->adresse = $adresse;
        $client->email = $email;
        $client->save();
        $message = 'Client '.strtoupper($nom).' mis à jour avec succès.';
        session()->flash('message', $message);

        return redirect()->route(session()->get('info')[0].'.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $id_user = User::findOrFail(Client::findOrFail($id)->id_user)->id;
        $client = Client::findOrFail($id);
        $user = User::findOrFail($id_user);
        if ($client->statut == 0) {
            $client->statut = 1;
            $user->statut = 1;
            $message = 'Client '.strtoupper($client->nom).' activé(e) avec succès.';
        } else {
            $client->statut = 0;
            $user->statut = 0;
            $message = 'Client '.strtoupper($client->nom).' désactivé(e) avec succès.';
        }
        $client->save();
        $user->save();
        session()->flash('message', $message);

        return redirect()->back();
    }
}
