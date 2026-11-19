@extends('layouts.app')

@section('title', 'Commande '.$commande->matricule)

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Commande {{ $commande->matricule }}</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-right">
                <a href="{{ route('client.commandes.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
            </div>
        </div>

        <div class="content-body">
            @php
                $badges = [
                    'brouillon' => 'secondary', 'confirmee' => 'info',
                    'en_preparation' => 'warning', 'prete' => 'primary',
                    'encaissee' => 'success', 'livree' => 'success', 'annulee' => 'danger',
                ];
                $labels = [
                    'brouillon' => 'Brouillon', 'confirmee' => 'Confirmée',
                    'en_preparation' => 'En préparation', 'prete' => 'Prête',
                    'encaissee' => 'Encaissée', 'livree' => 'Livrée', 'annulee' => 'Annulée',
                ];
            @endphp

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <div class="text-muted">Statut</div>
                                    <span class="badge badge-glow badge-{{ $badges[$commande->statut] ?? 'secondary' }}">{{ $labels[$commande->statut] ?? $commande->statut }}</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-muted">Date</div>
                                    <strong>{{ \Illuminate\Support\Carbon::parse($commande->date_commande)->format('d/m/Y') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="table-responsive">
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
                            @foreach($commande->lignes as $ligne)
                                <tr>
                                    <td>{{ $ligne->produit->nom ?? 'Produit #'.$ligne->id_produit }}</td>
                                    <td class="text-center">{{ $ligne->qte }}</td>
                                    <td class="text-right">{{ number_format((float) $ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                    <td class="text-right">{{ number_format((float) $ligne->total_ligne, 0, ',', ' ') }} FCFA</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Total HT</th>
                                <th class="text-right">{{ number_format((float) $commande->total_ht, 0, ',', ' ') }} FCFA</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-right">TVA</th>
                                <th class="text-right">{{ number_format((float) $commande->tva, 0, ',', ' ') }} FCFA</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-right">Total TTC</th>
                                <th class="text-right text-success">{{ number_format((float) $commande->total_ttc, 0, ',', ' ') }} FCFA</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
