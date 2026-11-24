@extends('layouts.app')

@section('title', 'Stock des matières premières')

@section('contenu')
<div class="content app-content">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2">
                <h2 class="content-header-title">Stock des matières premières</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Stock des matières premières</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-xl-5">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">État des stocks</h4></div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead><tr><th>Matière première</th><th class="text-right">Disponible</th><th class="text-right">Seuil</th></tr></thead>
                            <tbody>
                                @forelse($matieres as $matiere)
                                    <tr>
                                        <td>{{ $matiere->nom_produit }}</td>
                                        <td class="text-right">{{ number_format((float) $matiere->stock_courant, 1, ',', ' ') }} kg</td>
                                        <td class="text-right">{{ $matiere->seuil_alerte !== null ? number_format((float) $matiere->seuil_alerte, 1, ',', ' ').' kg' : '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">Aucune matière première enregistrée.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-7">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Historique des mouvements</h4></div>
                    <div class="card-body">
                        <form method="GET" class="mb-1">
                            <div class="input-group">
                                <input type="search" name="recherche" value="{{ request('recherche') }}" class="form-control" placeholder="Rechercher une matière première">
                                <div class="input-group-append"><button class="btn btn-primary" type="submit">Rechercher</button></div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead><tr><th>Date</th><th>Matière première</th><th>Mouvement</th><th class="text-right">Qté</th><th class="text-right">Stock après</th><th>Motif</th></tr></thead>
                            <tbody>
                                @forelse($mouvements as $mouvement)
                                    <tr>
                                        <td>{{ optional($mouvement->date_entree ?? $mouvement->date_sortie)->format('d/m/Y') }}</td>
                                        <td>{{ $mouvement->matiere_premiere?->nom_produit ?? 'Matière supprimée' }}</td>
                                        <td>{{ $mouvement->type_mouvement === 'approvisionnement' ? 'Entrée' : 'Sortie' }}</td>
                                        <td class="text-right">{{ number_format((float) $mouvement->quantite, 1, ',', ' ') }}</td>
                                        <td class="text-right">{{ number_format((float) $mouvement->nouveau_stock, 1, ',', ' ') }}</td>
                                        <td>{{ $mouvement->motif ?: '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted">Aucun mouvement de stock.</td></tr>
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
