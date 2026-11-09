<?php

namespace App\Http\Controllers;

use App\Models\Type_animal;
use Illuminate\Http\Request;

class Type_animalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $types_reproduction = [
            'normal' => 'Mettre bas',
            'ponte' => 'Pondre',
        ];
        $colors = [
            'normal' => 'primary',
            'ponte' => 'secondary',
        ];
        $type_animals = Type_animal::where('nom', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('description', 'like', '%'.$request->input('recherche').'%')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        session()->flash('search', $request->input('recherche'));

        return view('admin.pages.type_animal.index', compact('type_animals', 'colors', 'types_reproduction'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $types_reproduction = [
            'normal=>Mettre bas',
            'ponte=>Pondre',
        ];

        return view('admin.pages.type_animal.create', compact('types_reproduction'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $type_reproduction = $request->input('type_reproduction');
        $nom = $request->input('nom');
        $description = $request->input('description');
        $validated = $request->validate([
            'type_reproduction' => 'bail|required',
            'nom' => 'bail|required|unique:type_animals|max:255|min:2',
            'description' => 'bail|required|max:255|min:5',
        ]);
        $type_animal = new type_animal;
        $type_animal->type_reproduction = $type_reproduction;
        $type_animal->nom = $nom;
        $type_animal->description = $description;
        $type_animal->save();
        $message = "Type d'animal ".strtoupper($nom)." créé avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->route('type_animal.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $type_animal = Type_animal::findOrFail($id);

        return view('admin.pages.type_animal.info', compact('type_animal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $types_reproduction = [
            'normal=>Mettre bas',
            'ponte=>Pondre',
        ];
        $type_animal = Type_animal::findOrFail($id);

        return view('admin.pages.type_animal.edit', compact('type_animal', 'types_reproduction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $type_reproduction = $request->input('type_reproduction');
        $nom = $request->input('nom');
        $description = $request->input('description');
        $validated = $request->validate([
            'type_reproduction' => 'bail|required',
            'nom' => 'bail|required|max:255|min:2',
            'description' => 'bail|required|max:255|min:5',
        ]);
        if (Type_animal::where('nom', $nom)->whereNotIn('id', [$id])->count() > 0) {
            $validated = $request->validate([
                'nom' => 'unique:type_animal',
            ]);
        }
        $type_animal = Type_animal::findOrFail($id);
        $type_animal->type_reproduction = $type_reproduction;
        $type_animal->nom = $nom;
        $type_animal->description = $description;
        $type_animal->save();
        $message = ucfirst(strtoupper($nom))." mis à jour avec <b class='text-success'> Succès.</b> ";
        session()->flash('message', $message);

        return redirect()->route('type_animal.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $type_animal = Type_animal::findOrFail($id);
        $type_animal->delete();
        $message = $type_animal->nom." Supprimé avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->back();
    }
}
