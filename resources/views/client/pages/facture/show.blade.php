@extends('layouts.app')

@section('title', 'Facture '.$facture->matricule)

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Facture {{ $facture->matricule }}</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-right">
                <a href="{{ route('client.factures.pdf', $facture) }}" class="btn btn-danger btn-sm"><i data-feather="download" class="mr-50"></i> Télécharger PDF</a>
            </div>
        </div>

        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <h5>Client</h5>
                            <p class="mb-0 font-weight-bold">{{ $facture->client_facture->nom ?? '—' }}</p>
                            <p class="mb-0">{{ $facture->client_facture->telephone ?? '' }}</p>
                            <p class="mb-0">{{ $facture->client_facture->adresse ?? '' }}</p>
                        </div>
                        <div class="col-md-6 col-12 text-md-right">
                            <h5>Facture</h5>
                            <p class="mb-0"><strong>{{ $facture->matricule }}</strong></p>
                            <p class="mb-0">Date : {{ \Illuminate\Support\Carbon::parse($facture->date_facturation)->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="table-responsive mt-2">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-right">Prix unitaire</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($facture->lignes_facture as $ligne)
                                    <tr>
                                        <td>{{ $ligne->produit->nom ?? 'Produit #'.$ligne->id_produit }}</td>
                                        <td class="text-center">{{ $ligne->qte }}</td>
                                        <td class="text-right">{{ number_format((float) $ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                        <td class="text-right">{{ number_format((float) $ligne->total_ligne, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr><th colspan="3" class="text-right">Total HT</th><th class="text-right">{{ number_format((float) $facture->total_ht, 0, ',', ' ') }} FCFA</th></tr>
                                <tr><th colspan="3" class="text-right">TVA</th><th class="text-right">{{ number_format((float) $facture->tva, 0, ',', ' ') }} FCFA</th></tr>
                                <tr><th colspan="3" class="text-right">Total TTC</th><th class="text-right text-success">{{ number_format((float) $facture->total_ttc, 0, ',', ' ') }} FCFA</th></tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
