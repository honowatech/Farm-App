@extends('layouts.app')

@section('title', 'Mon espace client')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Bonjour {{ auth()->user()->name }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row match-height">
                @foreach($stats as $stat)
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <span class="text-muted">{{ $stat['label'] }}</span>
                                        <h3 class="mb-0 mt-50">{{ $stat['value'] }}</h3>
                                    </div>
                                    <div class="avatar bg-light-{{ $stat['color'] }} p-50">
                                        <div class="avatar-content">
                                            <i data-feather="{{ $stat['icon'] }}"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Mes dernières commandes</h4>
                            <a href="{{ route('client.commande.create') }}" class="btn btn-success btn-sm">
                                <i data-feather="shopping-cart" class="mr-50"></i> Commander
                            </a>
                        </div>
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
                                        <tr><td colspan="5" class="text-center">Aucune commande pour le moment.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
