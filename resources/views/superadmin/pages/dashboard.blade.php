@extends('layouts.app')

@section('title', 'Tableau de bord central')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Tableau de bord central</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            {{-- Indicateurs --}}
            <div class="row match-height">
                @foreach ($stats as $stat)
                    <div class="col-xl-3 col-md-4 col-6">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <div class="avatar bg-light-{{ $stat['color'] }} mb-1 mx-auto">
                                    <div class="avatar-content">
                                        <i data-feather="{{ $stat['icon'] }}" class="avatar-icon font-medium-2"></i>
                                    </div>
                                </div>
                                <h3 class="font-weight-bolder mb-0">{{ $stat['value'] }}</h3>
                                <p class="card-text mb-0 text-muted">{{ $stat['label'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Dernières entreprises --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Dernières entreprises</h4>
                    <a href="{{ route('admin.tenants.create') }}" class="btn btn-success btn-sm">
                        <i data-feather="plus" class="mr-50"></i> Nouvelle entreprise
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Slug</th>
                                    <th>Statut</th>
                                    <th>Membres</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tenants as $tenant)
                                    <tr>
                                        <td>{{ $tenant->name }}</td>
                                        <td><code>{{ $tenant->slug }}</code></td>
                                        <td>
                                            <span class="badge badge-glow badge-{{ $tenant->status === 'active' ? 'success' : ($tenant->status === 'suspended' ? 'danger' : 'warning') }}">
                                                {{ $tenant->status }}
                                            </span>
                                            @if ($tenant->is_demo)
                                                <span class="badge badge-glow badge-warning ml-50">démo</span>
                                            @endif
                                        </td>
                                        <td>{{ $tenant->users_count }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted">Aucune entreprise.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Derniers abonnements --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Derniers abonnements</h4>
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-primary btn-sm">Voir tout</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Entreprise</th>
                                    <th>Forfait</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($subscriptions as $subscription)
                                    <tr>
                                        <td>{{ $subscription->tenant?->name ?? '—' }}</td>
                                        <td>{{ $subscription->plan?->name ?? '—' }}</td>
                                        <td>
                                            <span class="badge badge-glow badge-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'trialing' ? 'info' : 'warning') }}">
                                                {{ $subscription->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">Aucun abonnement.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
