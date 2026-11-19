@extends('layouts.app')

@section('title', 'Passer une commande')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Passer une commande</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('client.commande.store') }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th class="text-right">Prix unitaire</th>
                                        <th style="width: 140px;" class="text-center">Quantité</th>
                                        <th class="text-right">Sous-total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($produits as $produit)
                                        <tr>
                                            <td>
                                                <div class="font-weight-bold">{{ $produit->nom }}</div>
                                                <div class="text-muted small">{{ $produit->description ?? '' }}</div>
                                            </td>
                                            <td class="text-right">{{ number_format((float) $produit->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                            <td class="text-center">
                                                <input type="number" name="quantites[{{ $produit->id }}]" min="0" value="0"
                                                       class="form-control text-center qte-input" data-prix="{{ $produit->prix_unitaire }}"
                                                       data-sous-total="sous-total-{{ $produit->id }}">
                                            </td>
                                            <td class="text-right sous-total" id="sous-total-{{ $produit->id }}">0 FCFA</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center">Aucun produit disponible.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <a href="{{ route('client.catalogue') }}" class="btn btn-outline-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">
                                <i data-feather="check" class="mr-50"></i> Enregistrer la commande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
    // Met à jour le sous-total de chaque ligne et le total général en temps réel.
    function formatMontant(valeur) {
        return new Intl.NumberFormat('fr-FR').format(Math.round(valeur)) + ' FCFA';
    }

    $('.qte-input').on('input', function () {
        var $input = $(this);
        var prix = parseFloat($input.data('prix')) || 0;
        var qte = parseInt($input.val(), 10) || 0;
        var sousTotal = prix * qte;

        $('#' + $input.data('sous-total')).text(formatMontant(sousTotal));
    });
</script>
@endsection
