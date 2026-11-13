@extends('layouts.app')

@section('title', __('Dashboard Agent'))

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/dashboard-ecommerce.css') }}">
<style>
    .agent-quick-actions {
        display: flex;
        align-items: stretch;
        gap: 0.75rem;
        flex-wrap: nowrap;
    }
    .agent-quick-action {
        flex: 1 1 0;
        min-width: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem 0.5rem;
        border: 1px solid #ebe9f1;
        border-radius: 0.5rem;
        background-color: #fff;
        color: #5e5873;
        text-align: center;
        transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease, color .15s ease;
    }
    .agent-quick-action:hover,
    .agent-quick-action:focus {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(115, 103, 240, .18);
        border-color: #7367f0;
        color: #7367f0;
        text-decoration: none;
    }
    .agent-quick-action .avatar {
        margin: 0;
        flex-shrink: 0;
    }
    .agent-quick-label {
        font-weight: 600;
        font-size: 0.9rem;
        line-height: 1.2;
        white-space: nowrap;
    }
    @media (max-width: 767.98px) {
        .agent-quick-actions {
            flex-wrap: wrap;
        }
        .agent-quick-action {
            flex: 1 1 calc(33.333% - 0.75rem);
        }
    }
    @media (max-width: 575.98px) {
        .agent-quick-action {
            flex: 1 1 calc(50% - 0.75rem);
            padding: 0.85rem 0.5rem;
        }
    }
</style>
@endsection

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">{{ __('Tableau de Bord') }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
<div class="row">
    <!-- Actions rapides -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Actions rapides</h4>
            </div>
            <div class="card-body">
                <div class="agent-quick-actions">
                    <a href="{{ route('nutrition.index') }}" class="agent-quick-action">
                        <span class="avatar bg-light-primary">
                            <span class="avatar-content"><i data-feather="activity"></i></span>
                        </span>
                        <span class="agent-quick-label">Nutrition</span>
                    </a>
                    <a href="{{ route('mortalite.create') }}" class="agent-quick-action">
                        <span class="avatar bg-light-danger">
                            <span class="avatar-content"><i data-feather="alert-triangle"></i></span>
                        </span>
                        <span class="agent-quick-label">Mortalité</span>
                    </a>
                    <a href="{{ route('bande.index') }}" class="agent-quick-action">
                        <span class="avatar bg-light-success">
                            <span class="avatar-content"><i data-feather="columns"></i></span>
                        </span>
                        <span class="agent-quick-label">Bandes</span>
                    </a>
                    <a href="{{ route('nettoyage.index') }}" class="agent-quick-action">
                        <span class="avatar bg-light-info">
                            <span class="avatar-content"><i data-feather="droplet"></i></span>
                        </span>
                        <span class="agent-quick-label">Nettoyage</span>
                    </a>
                    <a href="{{ route('seanceVaccination.index') }}" class="agent-quick-action">
                        <span class="avatar bg-light-warning">
                            <span class="avatar-content">
                                <img src="{{ asset('app-assets/images/feather/vaccination.png') }}" style="height: 1.25rem; width: 1.25rem;">
                            </span>
                        </span>
                        <span class="agent-quick-label">Vaccination</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques Principales -->
    <div class="col-xl-12 col-md-12 col-12">
        <div class="card card-statistics">
            <div class="card-header">
                <h4 class="card-title">{{ __('Statistiques Générales') }}</h4>
                <div class="d-flex align-items-center">
                    <p class="card-text font-small-2 mr-25 mb-0">{{ __('Dernière mise à jour') }}: {{ now()->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="card-body statistics-body">
                <div class="row">
                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                        <div class="media">
                            <div class="avatar bg-light-primary mr-2">
                                <div class="avatar-content">
                                    <i data-feather="columns" class="avatar-icon"></i>
                                </div>
                            </div>
                            <div class="media-body my-auto">
                                <h4 class="font-weight-bolder mb-0">{{ $bandes_en_cours->count() ?? 0 }}</h4>
                                <p class="card-text font-small-3 mb-0">{{ __('Bandes en Cours') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                        <div class="media">
                            <div class="avatar bg-light-info mr-2">
                                <div class="avatar-content">
                                    <i data-feather="activity" class="avatar-icon"></i>
                                </div>
                            </div>
                            <div class="media-body my-auto">
                                <h4 class="font-weight-bolder mb-0">{{ $nutritions_total ?? 0 }}</h4>
                                <p class="card-text font-small-3 mb-0">{{ __('Nutritions') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                        <div class="media">
                            <div class="avatar bg-light-success mr-2">
                                <div class="avatar-content">
                                    <i data-feather="droplet" class="avatar-icon"></i>
                                </div>
                            </div>
                            <div class="media-body my-auto">
                                <h4 class="font-weight-bolder mb-0">{{ $nettoyages_total ?? 0 }}</h4>
                                <p class="card-text font-small-3 mb-0">{{ __('Nettoyages') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="media">
                            <div class="avatar bg-light-warning mr-2">
                                <div class="avatar-content">
                                    <img src="{{asset('app-assets/images/feather/vaccination.png')}}" class="avatar-icon">
                                </div>
                            </div>
                            <div class="media-body my-auto">
                                <h4 class="font-weight-bolder mb-0">{{ $vaccinations_total ?? 0 }}</h4>
                                <p class="card-text font-small-3 mb-0">{{ __('Vaccinations') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Statistiques Principales -->

    <!-- Bandes en Cours -->
    <div class="col-lg-8 col-12">
        <div class="card card-company-table">
            <div class="card-header">
                <h4 class="card-title">{{ __('Bandes en Cours') }}</h4>
            </div>
            <div class="card-body p-0">
                <div data-ajax-table>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('Nom') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Date Lancement') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bandes_paginees ?? [] as $bande)
                            <tr>
                                <td>{{ $bande->nom }}</td>
                                <td>{{ $bande->animal?->nom ?? '—' }}</td>
                                <td>{{ Carbon\Carbon::parse($bande->date_lancement_bande)->format('d/m/Y') }}</td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('gestion_bande', ['id' => $bande->id]) }}"
                                           class="btn btn-sm btn-icon btn-outline-primary rounded-circle"
                                           title="Consulter la bande" aria-label="Consulter la bande">
                                            <i data-feather="eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">{{ __('Aucune bande en cours') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-1 pb-1">
                    @if(!empty($bandes_paginees))
                        {{ $bandes_paginees->links() }}
                    @endif
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dernières Activités -->
    <div class="col-lg-4 col-12">
        <div class="card card-transaction">
            <div class="card-header">
                <h4 class="card-title">{{ __('Dernières Activités') }}</h4>
            </div>
            <div class="card-body">
                <div class="transaction-item">
                    <div class="media">
                        <div class="avatar bg-light-primary rounded">
                            <div class="avatar-content">
                                <i data-feather="activity" class="avatar-icon"></i>
                            </div>
                        </div>
                        <div class="media-body">
                            <h6 class="transaction-title">{{ __('Nutrition') }}</h6>
                            <small>{{ __('Dernière mise à jour') }}</small>
                        </div>
                    </div>
                    <div class="font-weight-bolder text-success">
                        {{ $derniere_nutrition ?? __('N/A') }}
                    </div>
                </div>
                <div class="transaction-item">
                    <div class="media">
                        <div class="avatar bg-light-success rounded">
                            <div class="avatar-content">
                                <i data-feather="droplet" class="avatar-icon"></i>
                            </div>
                        </div>
                        <div class="media-body">
                            <h6 class="transaction-title">{{ __('Nettoyage') }}</h6>
                            <small>{{ __('Dernière mise à jour') }}</small>
                        </div>
                    </div>
                    <div class="font-weight-bolder text-success">
                        {{ $dernier_nettoyage ?? __('N/A') }}
                    </div>
                </div>
                <div class="transaction-item">
                    <div class="media">
                        <div class="avatar bg-light-warning rounded">
                            <div class="avatar-content">
                                <img src="{{asset('app-assets/images/feather/vaccination.png')}}" class="avatar-icon">
                            </div>
                        </div>
                        <div class="media-body">
                            <h6 class="transaction-title">{{ __('Vaccination') }}</h6>
                            <small>{{ __('Dernière mise à jour') }}</small>
                        </div>
                    </div>
                    <div class="font-weight-bolder text-success">
                        {{ $derniere_vaccination ?? __('N/A') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="{{ asset('app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>

@endsection
