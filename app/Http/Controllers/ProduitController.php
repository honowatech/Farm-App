<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Type_animal;
use App\Models\Type_produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $produits = Produit::where('nom', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('description', 'like', '%'.$request->input('recherche').'%')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        session()->flash('search', $request->input('recherche'));

        return view('admin.pages.produit.index', compact('produits'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $types_animal = Type_animal::get();
        $types_produits = Type_produit::All();

        return view('admin.pages.produit.create', compact('types_animal', 'types_produits'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $nom = $request->input('nom');
        $id_type_animal = $request->input('type_animal');
        $description = $request->input('description');
        $type_produit = $request->input('type_produit');
        $validated = $request->validate([
            'nom' => 'bail|required|unique:produits|max:255|min:4',
            'type_animal' => 'bail|required|numeric|max:255',
            'description' => 'bail|required||min:5',
        ]);
        $produit = new Produit;
        $produit->nom = $nom;
        $produit->id_type_produit = $type_produit;
        $produit->id_type_animal = $id_type_animal;
        $produit->description = $description;

        $produit->save();
        $message = strtoupper($nom)." créé avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->route('produit.show', $produit->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $produit = Produit::findOrFail($id);

        return view('admin.pages.produit.info', compact('produit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $produit = Produit::findOrFail($id);

        return view('admin.pages.produit.edit', compact('produit'));
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
            'nom' => 'bail|required|max:255|min:4',
            'description' => 'bail|required||min:5',
        ]);
        if (Produit::where('nom', $nom)->whereNotIn('id', [$id])->exists()) {
            $validated = $request->validate([
                'nom' => 'unique:produits',
            ]);
        }
        $produit = Produit::findOrFail($id);
        $produit->nom = $nom;
        $produit->description = $description;
        $produit->save();
        $message = ucfirst($nom)." Modifié avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->route('produit.show', $produit->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);
        if ($request->input('delete') == 1) {
            $produit->delete();
            $message = $produit->nom." Supprimé avec <b class='text-success'> Succès.</b>";
            session()->flash('message', $message);

            return redirect()->back();
        } else {
            if ($produit->statut == 0) {
                $produit->statut = 1;
                $message = $produit->nom." Rendu 'Visible' avec <b class='text-success'> Succès.</b>";
            } else {
                $produit->statut = 0;
                $message = $produit->nom."  Rendu 'Invisible' avec <b class='text-success'> Succès.</b>";
            }
            $produit->save();
            session()->flash('message', $message);

            return redirect()->back();
        }
    }
}
