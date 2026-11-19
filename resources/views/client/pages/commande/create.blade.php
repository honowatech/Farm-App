@extends('layouts.app')

@section('title', 'Mon panier')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Mon panier</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-right">
                <a href="{{ route('client.catalogue') }}" class="btn btn-outline-primary btn-sm">
                    <i data-feather="arrow-left" class="mr-50"></i> Continuer mes achats
                </a>
            </div>
        </div>

        <div class="content-body">
            @if(count($cart) === 0)
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i data-feather="shopping-cart" style="width: 48px; height: 48px;" class="text-muted mb-1"></i>
                        <h4 class="mt-1">Votre panier est vide</h4>
                        <p class="text-muted">Parcourez le catalogue pour ajouter des produits.</p>
                        <a href="{{ route('client.catalogue') }}" class="btn btn-primary">Voir le catalogue</a>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="panier-table">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th class="text-right">Prix unitaire</th>
                                        <th style="width: 120px;" class="text-center">Quantité</th>
                                        <th class="text-right">Sous-total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalPanier = 0; @endphp
                                    @foreach($cart as $id => $qte)
                                        @php
                                            $produit = $produits[$id] ?? null;
                                            if (! $produit) { continue; }
                                            $sousTotal = (float) $produit->prix_unitaire * (int) $qte;
                                            $totalPanier += $sousTotal;
                                        @endphp
                                        <tr class="ligne-panier" data-id="{{ $produit->id }}">
                                            <td>
                                                <div class="font-weight-bold">{{ $produit->nom }}</div>
                                                <div class="text-muted small">{{ $produit->description ?? '' }}</div>
                                            </td>
                                            <td class="text-right">{{ number_format((float) $produit->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                            <td class="text-center">
                                                <input type="number" min="0" value="{{ $qte }}"
                                                       class="form-control form-control-sm text-center qte-input"
                                                       data-id="{{ $produit->id }}" data-prix="{{ $produit->prix_unitaire }}">
                                            </td>
                                            <td class="text-right sous-total" id="sous-total-{{ $produit->id }}">{{ number_format($sousTotal, 0, ',', ' ') }} FCFA</td>
                                            <td class="text-right">
                                                <button type="button" class="btn btn-sm text-danger p-0 btn-remove" data-id="{{ $produit->id }}" title="Retirer">
                                                    <i data-feather="trash-2"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">Total</th>
                                        <th class="text-right text-success" id="total-panier">{{ number_format($totalPanier, 0, ',', ' ') }} FCFA</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <a href="{{ route('client.catalogue') }}" class="btn btn-outline-secondary">Continuer mes achats</a>
                            <form action="{{ route('client.commande.store') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i data-feather="check" class="mr-50"></i> Valider la commande
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
    function formatMontant(valeur) {
        return new Intl.NumberFormat('fr-FR').format(Math.round(valeur)) + ' FCFA';
    }

    function mettreAJourCompteur(count) {
        var $badge = $('#cart-count-badge');
        if (count > 0) {
            $badge.text(count).show();
        } else {
            $badge.hide();
        }
    }

    // Mise à jour de la quantité en AJAX.
    $(document).on('change', '.qte-input', function () {
        var $input = $(this);
        var id = $input.data('id');
        var qte = parseInt($input.val(), 10) || 0;

        $.ajax({
            url: '{{ route('client.panier.update') }}',
            method: 'POST',
            dataType: 'json',
            data: { id_produit: id, qte: qte, _token: $('meta[name="csrf-token"]').attr('content') }
        }).done(function (res) {
            if (!res.ok) { return; }

            mettreAJourCompteur(res.cartCount);

            if (res.removed) {
                $('.ligne-panier[data-id="' + id + '"]').remove();
            } else {
                $('#sous-total-' + id).text(formatMontant(res.sousTotal));
            }

            $('#total-panier').text(formatMontant(res.total));

            // Panier vide après retrait : recharge la vue une seule fois.
            if (res.cartCount === 0) {
                window.location.reload();
            }
        }).fail(function (xhr) {
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur lors de la mise à jour.';
            toastr.error(msg, 'Panier');
        });
    });

    // Retrait d'un produit en AJAX.
    $(document).on('click', '.btn-remove', function () {
        var $btn = $(this);
        var id = $btn.data('id');

        $.ajax({
            url: '{{ route('client.panier.remove') }}',
            method: 'POST',
            dataType: 'json',
            data: { id_produit: id, _token: $('meta[name="csrf-token"]').attr('content') }
        }).done(function (res) {
            if (!res.ok) { return; }

            mettreAJourCompteur(res.cartCount);
            $('.ligne-panier[data-id="' + id + '"]').remove();
            $('#total-panier').text(formatMontant(res.total));

            if (res.cartCount === 0) {
                window.location.reload();
            }
        }).fail(function (xhr) {
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur lors du retrait.';
            toastr.error(msg, 'Panier');
        });
    });
</script>
@endsection
