<?php

namespace App\Http\Controllers;

use App\Models\Batiment;
use Illuminate\Http\Request;

class BatimentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $batiments = Batiment::where('nom', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('description', 'like', '%'.$request->input('recherche').'%')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        session()->flash('search', $request->input('recherche'));

        return view('admin.pages.batiment.index', compact('batiments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.pages.batiment.create');
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
        $validated = $request->validate([
            'nom' => 'bail|required|unique:batiments|max:255|min:2',
            'description' => 'bail|required|max:255|min:5',
        ]);
        $user = new Batiment;
        $user->nom = $nom;
        $user->description = $description;
        $user->save();
        $message = 'Bâtiment '.strtoupper($nom)." créé avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->route('batiment.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $batiment = Batiment::findOrFail($id);

        return view('admin.pages.batiment.info', compact('batiment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $batiment = Batiment::findOrFail($id);

        return view('admin.pages.batiment.edit', compact('batiment'));
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
        $description = $request->input('description');
        $validated = $request->validate([
            'nom' => 'bail|required|max:255',
            'description' => 'bail|required|max:255',
        ]);
        if (Batiment::where('nom', $nom)->whereNotIn('id', [$id])->count() > 0) {
            $validated = $request->validate([
                'nom' => 'unique:batiment',
            ]);
        }
        $batiment = Batiment::findOrFail($id);
        $batiment->nom = $nom;
        $batiment->description = $description;
        $batiment->save();
        $message = ucfirst(strtoupper($nom))." mis à jour avec <b class='text-success'> Succès.</b> ";
        session()->flash('message', $message);

        return redirect()->route('batiment.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $batiment = Batiment::findOrFail($id);
        $batiment->delete();
        $message = $batiment->nom." Supprimé avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->back();
    }
}
