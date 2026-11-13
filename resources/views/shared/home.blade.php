@extends('layouts.app')

@section('title', __('Tableau de bord'))

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">{{ __('Tableau de bord') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">{{ ucfirst($role ?? 'utilisateur') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            @if(count($stats ?? []) > 0)
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
            @endif

            @if(count($liens ?? []) > 0)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Accès rapides</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($liens as $lien)
                                <div class="col-xl-3 col-md-4 col-sm-6 col-12 mb-1">
                                    <a href="{{ route($lien['route']) }}" class="d-flex align-items-center p-1 border rounded">
                                        <span class="avatar bg-light-primary mr-1">
                                            <span class="avatar-content"><i data-feather="{{ $lien['icon'] }}"></i></span>
                                        </span>
                                        <span class="font-weight-bold">{{ $lien['label'] }}</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($facturesClient) && $facturesClient->isNotEmpty())
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Mes factures</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Matricule</th>
                                    <th>Date</th>
                                    <th class="text-right">Total TTC</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($facturesClient as $facture)
                                    <tr>
                                        <td>{{ $facture->matricule }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($facture->date_facturation)->format('d/m/Y') }}</td>
                                        <td class="text-right">{{ number_format((float) $facture->total_ttc, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if(count($stats ?? []) === 0 && count($liens ?? []) === 0 && (! isset($facturesClient) || $facturesClient->isEmpty()))
                <div class="alert alert-primary">
                    <h4 class="alert-heading">Bienvenue {{ auth()->user()->name }}</h4>
                    <p class="mb-0">Votre profil <strong>{{ $role }}</strong> est actif. Aucun module métier n'est encore associé à ce compte.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
