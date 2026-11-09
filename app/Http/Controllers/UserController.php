<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Typeusers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $users = User::where('name', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('email', 'like', '%'.$request->input('recherche').'%')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        session()->flash('search', $request->input('recherche'));

        return view('admin.pages.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $typesUser = Typeusers::get();

        return view('admin.pages.user.create', compact('typesUser'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $name = $request->input('noms');
        $email = $request->input('email');
        $password = $request->input('password');
        $telephone = str_replace(' ', '', $request->input('telephone'));
        $type_user = $request->input('type_user');
        $type = Typeusers::findOrFail($type_user);
        if (strtoupper($type->libelle) == strtoupper('client') || strtoupper($type->libelle) == strtoupper('clients')) {
            $message = "<b>Une erreur s'est produite.</b>";
            session()->flash('message', $message);

            return redirect()->back();
        }
        $validated = $request->validate([
            'noms' => 'bail|required|max:255',
            'email' => 'bail|required|unique:users|max:255',
            'password' => 'bail|required|min:8',
            'type_user' => 'bail|required|min:1',
        ]);
        $user = new User;
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->id_type_user = $type_user;
        $user->save();
        $message = strtoupper($user->type_user->libelle).' '.strtoupper($name)." créé avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        session()->put('info', ['user']);
        $client_correspondant = Client::where('id_user', $user->id);
        if ($client_correspondant->exists()) {
            $client = Client::findOrFail($client_correspondant->first()->id);

            return view('admin.pages.user.info', compact('user', 'client'));
        }

        return view('admin.pages.user.info', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $client_correspondant = Client::where('id_user', $user->id);
        // ici on redirige vers la vue d'édition du client si le compte possède un compte client...
        if ($client_correspondant->exists()) {
            session()->put('info', ['user']);
            $client = Client::findOrFail($client_correspondant->first()->id);

            return view('admin.pages.client.edit', compact('client'));
        }

        return view('admin.pages.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $telephone = str_replace(' ', '', $request->input('telephone'));
        $validated = $request->validate([
            'name' => 'bail|required|max:255',
            'email' => 'bail|required|max:255',
        ]);
        if (User::where('email', $email)->whereNotIn('id', [$id])->count() > 0) {
            $validated = $request->validate([
                'email' => 'unique:users',
            ]);
        }
        $user = User::findOrFail($id);
        $user->name = $name;
        $user->email = $email;
        $user->save();
        $message = ucfirst($user->type_user->libelle).' '.strtoupper($name)." mis à jour avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->statut == 0) {
            $user->statut = 1;
            $message = $user->name." Activé(e) avec <b class='text-success'> Succès.</b>";
        } else {
            $user->statut = 0;
            $message = $user->name." Desactivé(e) avec <b class='text-success'> Succès.</b>";
        }
        $user->save();
        session()->flash('message', $message);

        return redirect()->back();
    }
}
