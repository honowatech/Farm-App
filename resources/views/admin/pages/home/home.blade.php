@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/dashboard-ecommerce.css') }}">
@endsection
@section('title')
    {{ __('Tableau de bord') }}
@endsection
@section('contenu')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">{{ __('Tableau de bord') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="dashboard-ecommerce">
                    {{-- Indicateurs clés --}}
                    <div class="row match-height">
                        <div class="col-xl-3 col-lg-6 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h2 class="font-weight-bolder mb-0">{{ $bandes_en_cours->count() }}</h2>
                                            <p class="card-text mb-0">{{ __('Bandes en cours') }}</p>
                                        </div>
                                        <div class="avatar bg-light-primary">
                                            <div class="avatar-content"><i data-feather="columns" class="avatar-icon"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h2 class="font-weight-bolder mb-0">{{ number_format($sujets_en_elevage, 0, ',', ' ') }}</h2>
                                            <p class="card-text mb-0">{{ __('Sujets en élevage') }}</p>
                                        </div>
                                        <div class="avatar bg-light-info">
                                            <div class="avatar-content"><i data-feather="users" class="avatar-icon"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h2 class="font-weight-bolder mb-0">{{ number_format($ca_du_mois, 0, ',', ' ') }} <small class="font-small-3">FCFA</small></h2>
                                            <p class="card-text mb-0">{{ __('Chiffre d\'affaires du mois') }}</p>
                                        </div>
                                        <div class="avatar bg-light-success">
                                            <div class="avatar-content"><i data-feather="trending-up" class="avatar-icon"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h2 class="font-weight-bolder mb-0">{{ $ventes_du_mois }}</h2>
                                            <p class="card-text mb-0">{{ __('Ventes du mois') }}</p>
                                        </div>
                                        <div class="avatar bg-light-warning">
                                            <div class="avatar-content"><i data-feather="shopping-cart" class="avatar-icon"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- /Indicateurs clés --}}

                    <div class="row match-height">
                        {{-- Alertes --}}
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('Alertes') }}</h4>
                                </div>
                                <div class="card-body">
                                    @php
                                        $function_matieres_premiere = Matierepremiere($matiere_premieres);
                                        $a_des_alertes = $bandes_nutrition_alerte->isNotEmpty() || $function_matieres_premiere['pas_en_stock'] > 0;
                                    @endphp

                                    @forelse($bandes_nutrition_alerte as $bande)
                                        <div class="alert alert-warning p-1" role="alert">
                                            <div class="d-flex align-items-center">
                                                <i data-feather="alert-triangle" class="mr-1"></i>
                                                <span>{{ __('Nutrition incomplète') }} : <strong>{{ $bande->nom }}</strong></span>
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse

                                    @if($function_matieres_premiere['pas_en_stock'] > 0)
                                        <div class="alert alert-danger p-1" role="alert">
                                            <div class="d-flex align-items-center">
                                                <i data-feather="shield-off" class="mr-1"></i>
                                                <span>
                                                    {{ $function_matieres_premiere['pas_en_stock'] }} {{ __('matière(s) première(s) épuisée(s)') }}
                                                    <a href="#" data-toggle="modal" data-target="#informations"
                                                       onclick="response_ajax('result_informations','{{ $function_matieres_premiere['ids_pas_en_stock'] }}','{{ route('matiere_premiere.index') }}','GET')">
                                                        {{ __('Voir') }}
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    @if(! $a_des_alertes)
                                        <p class="card-text text-muted">{{ __('Aucune alerte pour aujourd\'hui.') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{-- /Alertes --}}

                        {{-- Bandes en cours --}}
                        <div class="col-lg-8 col-md-6 col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">{{ __('Bandes en cours') }}</h4>
                                    <a href="{{ route('bande.index') }}" class="btn btn-sm btn-outline-primary">{{ __('Toutes les bandes') }}</a>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Nom') }}</th>
                                                    <th>{{ __('Type') }}</th>
                                                    <th>{{ __('Effectif') }}</th>
                                                    <th>{{ __('Âge') }}</th>
                                                    <th>{{ __('Nutrition du jour') }}</th>
                                                    <th class="text-right">{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($bandes_en_cours as $bande)
                                                    <tr>
                                                        <td>{{ $bande->nom }}</td>
                                                        <td>{{ $bande->animal?->nom ?? '—' }}</td>
                                                        <td>{{ $bande->nbre_sujet }}</td>
                                                        <td>{{ $bande->age_formate }}</td>
                                                        <td>
                                                            @php $statut = $bande->statut_nutrition_aujourdhui; @endphp
                                                            @switch($statut)
                                                                @case('Complet')
                                                                    <span class="badge badge-light-success">{{ __('Complet') }}</span>
                                                                    @break
                                                                @case('Incomplet')
                                                                    <span class="badge badge-light-warning">{{ __('Incomplet') }}</span>
                                                                    @break
                                                                @case('Aucune')
                                                                    <span class="badge badge-light-danger">{{ __('Aucune') }}</span>
                                                                    @break
                                                                @default
                                                                    <span class="badge badge-light-secondary">{{ $statut }}</span>
                                                            @endswitch
                                                        </td>
                                                        <td class="text-right">
                                                            <a href="{{ route('gestion_bande', ['id' => $bande->id]) }}" class="btn btn-sm btn-primary">
                                                                <i data-feather="eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted">{{ __('Aucune bande en cours') }}</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- /Bandes en cours --}}
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- Modale info-->
    <div class="modal fade" id="informations" tabindex="-1" aria-labelledby="informationsTitle" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="informationsTitle">{{ __('Plus d\'informations') }}</h5>
                    <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mx-auto" id="result_informations">
                            {{ __('Aucune information disponible.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Modale info-->
@endsection
@section('javascript')
    <script src="{{ asset('app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>
    <script type="text/javascript">
        document.querySelector('#Acceuil').classList.add('active');
    </script>
@endsection
