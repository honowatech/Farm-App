@extends('layouts.app')

@section('title', __('Dashboard Agent'))

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/dashboard-ecommerce.css') }}">
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
<div class="row match-height">
    <!-- Actions rapides -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Actions rapides</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 col-md-3 mb-1">
                        <a href="{{ route('nutrition.index') }}" class="btn btn-primary btn-lg btn-block">
                            <i data-feather="activity"></i> Nutrition
                        </a>
                    </div>
                    <div class="col-6 col-md-3 mb-1">
                        <a href="{{ route('mortalite.create') }}" class="btn btn-danger btn-lg btn-block">
                            <i data-feather="alert-triangle"></i> Mortalité
                        </a>
                    </div>
                    <div class="col-6 col-md-3 mb-1">
                        <a href="{{ route('bande.index') }}" class="btn btn-success btn-lg btn-block">
                            <i data-feather="columns"></i> Bandes
                        </a>
                    </div>
                    <div class="col-6 col-md-3 mb-1">
                        <a href="{{ route('nettoyage.index') }}" class="btn btn-info btn-lg btn-block">
                            <i data-feather="droplet"></i> Nettoyage
                        </a>
                    </div>
                    <div class="col-6 col-md-3 mb-1">
                        <a href="{{ route('seanceVaccination.index') }}" class="btn btn-warning btn-lg btn-block">
                            <img src="{{ asset('app-assets/images/feather/vaccination.png') }}" style="height: 1.25rem; width: 1.25rem;"> Vaccination
                        </a>
                    </div>
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
                            @forelse($bandes_en_cours ?? [] as $bande)
                            <tr>
                                <td>{{ $bande->nom }}</td>
                                <td>{{ $bande->animal?->nom ?? '—' }}</td>
                                <td>{{ Carbon\Carbon::parse($bande->date_lancement_bande)->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('gestion_bande', ['id' => $bande->id]) }}" class="btn btn-sm btn-primary">
                                        <i data-feather="eye"></i>
                                    </a>
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
