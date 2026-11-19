@extends('layouts.app')

@section('title', 'Mes commandes')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Mes commandes</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-right">
                <a href="{{ route('client.commande.create') }}" class="btn btn-success btn-sm">
                    <i data-feather="plus" class="mr-50"></i> Nouvelle commande
                </a>
            </div>
        </div>

        <div class="content-body">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Matricule</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th class="text-right">Total TTC</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($commandes as $commande)
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
                                <tr>
                                    <td>{{ $commande->matricule }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($commande->date_commande)->format('d/m/Y') }}</td>
                                    <td><span class="badge badge-glow badge-{{ $badges[$commande->statut] ?? 'secondary' }}">{{ $labels[$commande->statut] ?? $commande->statut }}</span></td>
                                    <td class="text-right">{{ number_format((float) $commande->total_ttc, 0, ',', ' ') }} FCFA</td>
                                    <td class="text-right"><a class="btn btn-sm btn-outline-primary" href="{{ route('client.commandes.show', $commande) }}">Voir</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">Aucune commande.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($commandes->hasPages())
                    <div class="card-footer">
                        {{ $commandes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
