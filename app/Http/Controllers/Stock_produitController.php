<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Stock_produit;
use App\Services\StockService;
use Illuminate\Http\Request;

class Stock_produitController extends Controller
{
    public function index(Request $request, StockService $stocks)
    {
        $stocksCourants = $stocks->stocksProduits();

        $produits = Produit::query()
            ->when($request->filled('recherche'), fn ($query) => $query->where('nom', 'like', '%'.$request->input('recherche').'%'))
            ->orderBy('nom')
            ->get()
            ->map(function (Produit $produit) use ($stocksCourants) {
                $produit->stock_courant = $stocksCourants[$produit->id] ?? 0.0;

                return $produit;
            });

        $mouvements = Stock_produit::with(['produit'])
            ->when($request->filled('recherche'), function ($query) use ($request) {
                $query->whereHas('produit', fn ($produit) => $produit->where('nom', 'like', '%'.$request->input('recherche').'%'));
            })
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.pages.stock_produit.index', compact('produits', 'mouvements'));
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
