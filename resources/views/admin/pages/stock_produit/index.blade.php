@extends('layouts.app')

@section('title', 'Stock des produits')

@section('contenu')
<div class="content app-content">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2">
                <h2 class="content-header-title">Stock des produits</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Stock des produits</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-xl-5">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">État du stock</h4></div>
                    <div data-tour-step="table" class="table-responsive">
                        <table class="table">
                            <thead><tr><th>Produit</th><th class="text-right">Quantité disponible</th></tr></thead>
                            <tbody>
                                @forelse($produits as $produit)
                                    <tr>
                                        <td>{{ $produit->nom }}</td>
                                        <td class="text-right">{{ number_format((float) $produit->stock_courant, 0, ',', ' ') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center text-muted">Aucun produit enregistré.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-7">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Mouvements récents</h4></div>
                    <div class="card-body">
                        <form data-tour-step="search" method="GET" class="mb-1">
                            <div class="input-group">
                                <input type="search" name="recherche" value="{{ request('recherche') }}" class="form-control" placeholder="Rechercher un produit">
                                <div class="input-group-append"><button class="btn btn-primary" type="submit">Rechercher</button></div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead><tr><th>Date</th><th>Produit</th><th>Mouvement</th><th class="text-right">Qté</th><th class="text-right">Stock après</th></tr></thead>
                            <tbody>
                                @forelse($mouvements as $mouvement)
                                    <tr>
                                        <td>{{ optional($mouvement->date_entree ?? $mouvement->date_sortie)->format('d/m/Y') }}</td>
                                        <td>{{ $mouvement->produit?->nom ?? 'Produit supprimé' }}</td>
                                        <td>{{ $mouvement->type_mouvement === 'approvisionnement' ? 'Entrée' : 'Sortie' }}</td>
                                        <td class="text-right">{{ number_format((float) $mouvement->qte, 0, ',', ' ') }}</td>
                                        <td class="text-right">{{ number_format((float) $mouvement->nouveau_stock, 0, ',', ' ') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">Aucun mouvement de stock.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">{{ $mouvements->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
