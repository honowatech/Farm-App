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
                    <i data-feather="shopping-cart" class="mr-50"></i> Mon panier
                    <span class="badge badge-pill badge-danger ml-50" id="cart-count-badge" style="display: {{ array_sum(array_map('intval', session('cart', []))) > 0 ? 'inline' : 'none' }};">{{ array_sum(array_map('intval', session('cart', []))) }}</span>
                </a>
            </div>
        </div>

        <div class="content-body">
            {{-- Barre de recherche --}}
            <div class="card">
                <div class="card-body py-1">
                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i data-feather="search"></i></span>
                        </div>
                        <input type="text" id="search-produit" class="form-control" placeholder="Rechercher un produit (nom, description)…" autocomplete="off">
                    </div>
                </div>
            </div>

            <div class="row match-height" id="produits-grid">
                @forelse($produits as $produit)
                    <div class="col-xl-3 col-md-4 col-sm-6 col-12 produit-card" data-search="{{ strtolower($produit->nom.' '.($produit->description ?? '')) }}">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h4 class="card-title">{{ $produit->nom }}</h4>
                                <p class="card-text text-muted small">{{ $produit->description ?? '—' }}</p>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h5 class="font-weight-bolder text-success mb-0">{{ number_format((float) $produit->prix_unitaire, 0, ',', ' ') }} FCFA</h5>
                                        <span class="badge badge-light-{{ $produit->statut ? 'success' : 'secondary' }}">{{ $produit->statut ? 'Disponible' : 'Indisponible' }}</span>
                                    </div>
                                    <div class="d-flex">
                                        <input type="number" class="form-control form-control-sm mr-1 qte-ajout" min="1" value="1" style="width: 70px;" {{ $produit->statut ? '' : 'disabled' }}>
                                        <button type="button" class="btn btn-primary btn-sm btn-block btn-add-cart" data-id="{{ $produit->id }}" data-nom="{{ $produit->nom }}" {{ $produit->statut ? '' : 'disabled' }}>
                                            <i data-feather="shopping-cart" class="mr-50"></i> Ajouter
                                        </button>
                                    </div>
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

@section('javascript')
<script type="text/javascript">
    // Recherche en direct (filtre côté client, sans rechargement).
    $('#search-produit').on('input', function () {
        var terme = $(this).val().toLowerCase().trim();
        var vide = true;

        $('#produits-grid .produit-card').each(function () {
            var match = $(this).data('search').indexOf(terme) !== -1;
            $(this).toggle(match);
            if (match) { vide = false; }
        });

        $('#produits-vide').remove();
        if (vide) {
            $('#produits-grid').append('<div id="produits-vide" class="col-12"><div class="alert alert-primary">Aucun produit ne correspond à votre recherche.</div></div>');
        }
    });

    // Ajout au panier en AJAX (sans rechargement).
    function mettreAJourCompteur(count) {
        var $badge = $('#cart-count-badge');
        if (count > 0) {
            $badge.text(count).show();
        } else {
            $badge.hide();
        }
    }

    $(document).on('click', '.btn-add-cart', function () {
        var $btn = $(this);
        var id = $btn.data('id');
        var qte = $btn.closest('.d-flex').find('.qte-ajout').val() || 1;

        $.ajax({
            url: '{{ route('client.panier.add') }}',
            method: 'POST',
            dataType: 'json',
            data: { id_produit: id, qte: qte, _token: $('meta[name="csrf-token"]').attr('content') }
        }).done(function (res) {
            if (res.ok) {
                toastr.success($btn.data('nom') + ' ajouté au panier.', 'Panier');
                mettreAJourCompteur(res.cartCount);
            }
        }).fail(function (xhr) {
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur lors de l\'ajout.';
            toastr.error(msg, 'Panier');
        });
    });
</script>
@endsection
