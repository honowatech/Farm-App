<?php

namespace Modules\Depenses\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Depenses\Entities\Categorie;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        $categories = Categorie::where('nom', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('description', 'like', '%'.$request->input('recherche').'%')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        session()->flash('search', $request->input('recherche'));

        return view('depenses::categorie.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('depenses::categorie.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_type_depense' => 'required|in:ponctuel,reguliere',
            'frequence' => 'required_if:id_type_depense,reguliere',
            'prix_unitaire' => 'required_if:id_type_depense,reguliere',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Création de la nouvelle catégorie
        $categorie = new Categorie;
        $categorie->type = $request->id_type_depense;
        $categorie->frequence = $request->frequence;
        $categorie->prix_unitaire = $request->prix_unitaire;
        $categorie->nom = $request->nom;
        if ($request->filled('description')) {
            $categorie->description = $request->description;
        } else {
            $categorie->description = 'Aucune description pour cette catégorie';
        }
        $categorie->save();
        $message = 'categorie '.strtoupper($request->nom)." créé avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->route('categorie.index');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        $categorie = Categorie::FindOrFail($id);

        return view('depenses::categorie.infos', compact('categorie'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id, Request $request)
    {
        $categorie = Categorie::FindOrFail($id);

        return view('depenses::categorie.edit', compact('categorie'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $nom = $request->input('nom');
        $description = $request->input('description');
        $validated = $request->validate([
            'nom' => 'bail|required|max:255',
        ]);
        if (Categorie::where('nom', $nom)->whereNotIn('id', [$id])->count() > 0) {
            $validated = $request->validate([
                'nom' => 'unique:categorie_depenses',
            ]);
        }
        $categorie = Categorie::findOrFail($id);
        $categorie->nom = $nom;
        $categorie->description = $description;
        $categorie->save();
        $message = ucfirst(strtoupper($nom))." mis à jour avec <b class='text-success'> Succès.</b> ";
        session()->flash('message', $message);

        return redirect()->route('categorie.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id, Request $request)
    {
        $categorie = Categorie::FindOrFail($id);
        $message = 'categorie '.ucfirst($categorie->nom)." supprimé avec <b class='text-success'> Succès.</b>";
        $categorie->delete();
        session()->flash('message', $message);

        return redirect()->route('categorie.index');
    }
}
