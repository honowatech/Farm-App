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
        $recherche = $request->input('recherche');
        $categories = Categorie::query()
            ->where(function ($query) use ($recherche) {
                $query->where('nom', 'like', '%'.$recherche.'%')
                    ->orWhere('description', 'like', '%'.$recherche.'%');
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        session()->flash('search', $recherche);

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
            'frequence' => 'required_if:id_type_depense,reguliere|nullable|in:quotidien,hebdomadaire,bimensuel,mensuel,trimestriel,semestriel,annuel',
            'prix_unitaire' => 'required_if:id_type_depense,reguliere|nullable|numeric|min:1',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        if ($this->hasDuplicateName($validatedData['nom'])) {
            return back()->withInput()->withErrors(['nom' => 'Une catégorie portant ce nom existe déjà.']);
        }

        $categorie = new Categorie;
        $categorie->type = $validatedData['id_type_depense'];
        $categorie->frequence = $validatedData['frequence'] ?? null;
        $categorie->prix_unitaire = $validatedData['prix_unitaire'] ?? null;
        $categorie->nom = trim($validatedData['nom']);
        $categorie->description = $validatedData['description'] ?? 'Aucune description pour cette catégorie';
        $categorie->save();

        return redirect()->route('categorie.index')->with('message', 'Catégorie créée avec succès.');
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
    public function edit($id)
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
        $validated = $request->validate([
            'nom' => 'bail|required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $categorie = Categorie::findOrFail($id);

        if ($this->hasDuplicateName($validated['nom'], $categorie->getKey())) {
            return back()->withInput()->withErrors(['nom' => 'Une catégorie portant ce nom existe déjà.']);
        }

        $categorie->nom = trim($validated['nom']);
        $categorie->description = $validated['description'] ?? null;
        $categorie->save();

        return redirect()->route('categorie.index')->with('message', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $categorie = Categorie::FindOrFail($id);

        if ($categorie->categorie()->exists()) {
            return redirect()->route('categorie.index')->with('error', 'Cette catégorie ne peut pas être supprimée car elle est utilisée par des dépenses.');
        }

        $categorie->delete();

        return redirect()->route('categorie.index')->with('message', 'Catégorie supprimée avec succès.');
    }

    private function hasDuplicateName(string $name, ?int $ignoreId = null): bool
    {
        $query = Categorie::query()
            ->whereRaw('LOWER(TRIM(nom)) = ?', [mb_strtolower(trim($name))]);

        if ($ignoreId !== null) {
            $query->where('id', '<>', $ignoreId);
        }

        return $query->exists();
    }
}
