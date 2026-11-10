<?php

namespace App\Http\Controllers;

use App\Models\Bande;
use App\Models\Mortalite;
use App\Models\MortaliteCause;
use App\Services\MortaliteService;
use Illuminate\Http\Request;

class MortaliteController extends Controller
{
    public function index(Request $request)
    {
        $mortalites = Mortalite::with(['bande', 'cause', 'saver'])
            ->when($request->filled('id_bande'), function ($query) use ($request) {
                $query->where('id_bande', $request->input('id_bande'));
            })
            ->orderBy('date', 'desc')
            ->paginate(20)
            ->withQueryString();

        $bandes = Bande::orderBy('nom')->get();

        return view('admin.pages.mortalite.index', compact('mortalites', 'bandes'));
    }

    public function create()
    {
        $bandes = Bande::orderBy('nom')->get();
        $causes = MortaliteCause::where('statut', 1)->orderBy('libelle')->get();

        return view('admin.pages.mortalite.create', compact('bandes', 'causes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_bande' => 'required|exists:bandes,id',
            'date' => 'required|date',
            'nombre_morts' => 'required|integer|min:0',
            'id_cause' => 'nullable|exists:mortalite_causes,id',
            'observation' => 'nullable|string|max:1000',
        ]);

        $bande = Bande::findOrFail($data['id_bande']);
        $effectif = app(MortaliteService::class)->effectifVivant($bande);

        if ($data['nombre_morts'] > $effectif) {
            return redirect()->back()->withInput()
                ->with('error', "Le nombre de morts ({$data['nombre_morts']}) dépasse l'effectif vivant ({$effectif}).");
        }

        $data['id_saver'] = auth()->id();

        Mortalite::create($data);

        return redirect()->route('mortalite.index')->with('message', 'Mortalité enregistrée avec succès.');
    }

    public function edit(Mortalite $mortalite)
    {
        $bandes = Bande::orderBy('nom')->get();
        $causes = MortaliteCause::where('statut', 1)->orderBy('libelle')->get();

        return view('admin.pages.mortalite.edit', compact('mortalite', 'bandes', 'causes'));
    }

    public function update(Request $request, Mortalite $mortalite)
    {
        $data = $request->validate([
            'id_bande' => 'required|exists:bandes,id',
            'date' => 'required|date',
            'nombre_morts' => 'required|integer|min:0',
            'id_cause' => 'nullable|exists:mortalite_causes,id',
            'observation' => 'nullable|string|max:1000',
        ]);

        $bande = Bande::findOrFail($data['id_bande']);
        $mortsSansCetteEntree = Mortalite::where('id_bande', $bande->id)
            ->where('id', '!=', $mortalite->id)
            ->sum('nombre_morts');
        $effectifDisponible = max(0, $bande->nbre_sujet - $mortsSansCetteEntree);

        if ($data['nombre_morts'] > $effectifDisponible) {
            return redirect()->back()->withInput()
                ->with('error', "Le nombre de morts dépasse l'effectif disponible ({$effectifDisponible}).");
        }

        $mortalite->update($data);

        return redirect()->route('mortalite.index')->with('message', 'Mortalité mise à jour avec succès.');
    }

    public function destroy(Mortalite $mortalite)
    {
        $mortalite->delete();

        return redirect()->route('mortalite.index')->with('message', 'Mortalité supprimée avec succès.');
    }
}
