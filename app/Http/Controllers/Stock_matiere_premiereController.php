<?php

namespace App\Http\Controllers;

use App\Models\Matiere_premiere;
use App\Models\Stock_matiere_premiere;
use App\Services\StockService;
use Illuminate\Http\Request;

class Stock_matiere_premiereController extends Controller
{
    public function index(Request $request, StockService $stocks)
    {
        $stocksCourants = $stocks->stocksMatieresPremieres();

        $matieres = Matiere_premiere::query()
            ->when($request->filled('recherche'), fn ($query) => $query->where('nom_produit', 'like', '%'.$request->input('recherche').'%'))
            ->orderBy('nom_produit')
            ->get()
            ->map(function (Matiere_premiere $matiere) use ($stocksCourants) {
                $matiere->stock_courant = $stocksCourants[$matiere->id] ?? 0.0;

                return $matiere;
            });

        $mouvements = Stock_matiere_premiere::with('matiere_premiere')
            ->when($request->filled('recherche'), function ($query) use ($request) {
                $query->whereHas('matiere_premiere', fn ($matiere) => $matiere->where('nom_produit', 'like', '%'.$request->input('recherche').'%'));
            })
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.pages.stock_matiere_premiere.index', compact('matieres', 'mouvements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {}

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {}
}
