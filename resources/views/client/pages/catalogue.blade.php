@extends('layouts.app')

@section('title', 'Catalogue produits')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Catalogue produits</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-right">
                <a href="{{ route('client.commande.create') }}" class="btn btn-success btn-sm">
                    <i data-feather="shopping-cart" class="mr-50"></i> Voir le panier
                </a>
            </div>
        </div>

        <div class="content-body">
            <div class="row match-height">
                @forelse($produits as $produit)
                    <div class="col-xl-3 col-md-4 col-sm-6 col-12">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h4 class="card-title">{{ $produit->nom }}</h4>
                                <p class="card-text text-muted small">{{ $produit->description ?? '—' }}</p>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h5 class="font-weight-bolder text-success mb-0">{{ number_format((float) $produit->prix_unitaire, 0, ',', ' ') }} FCFA</h5>
                                        <span class="badge badge-light-{{ $produit->statut ? 'success' : 'secondary' }}">{{ $produit->statut ? 'Disponible' : 'Indisponible' }}</span>
                                    </div>
                                    <form action="{{ route('client.panier.add') }}" method="POST" class="d-flex">
                                        @csrf
                                        <input type="hidden" name="id_produit" value="{{ $produit->id }}">
                                        <input type="number" name="qte" min="1" value="1" class="form-control form-control-sm mr-1" style="width: 70px;">
                                        <button type="submit" class="btn btn-primary btn-sm btn-block" {{ $produit->statut ? '' : 'disabled' }}>
                                            <i data-feather="shopping-cart" class="mr-50"></i> Ajouter
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-primary">Aucun produit disponible pour le moment.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
