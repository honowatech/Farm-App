@extends('layouts.app')

@section('title', 'Paie')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="float-left mb-0 content-header-title">Paie des salariés</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                                <li class="breadcrumb-item active">Paie</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-xl-4 col-md-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="text-muted">Total des paiements du mois</span>
                            <h3 class="mb-0 mt-50">{{ number_format($total_mois, 0, ',', ' ') }} FCFA</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="text-muted">Paiements enregistrés</span>
                            <h3 class="mb-0 mt-50">{{ $paiements->total() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Historique des paiements</h4>
                    <a href="{{ route('salarie.index') }}" class="btn btn-primary btn-sm">
                        <i data-feather="award" style="width: 16px; height: 16px;"></i> Gérer les salariés
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Salarié</th>
                                <th>Type</th>
                                <th>Période</th>
                                <th class="text-right">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paiements as $paiement)
                                <tr>
                                    <td>{{ \Illuminate\Support\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $paiement->salarie->nom ?? '—' }}</td>
                                    <td>
                                        <span class="badge badge-pill badge-glow {{ $paiement->type === 'avance' ? 'badge-warning' : 'badge-success' }}">
                                            {{ $paiement->type === 'avance' ? 'Avance' : 'Paiement complet' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ \Illuminate\Support\Carbon::parse($paiement->date_debut)->format('d/m/Y') }}
                                        au
                                        {{ \Illuminate\Support\Carbon::parse($paiement->date_fin)->format('d/m/Y') }}
                                    </td>
                                    <td class="text-right font-weight-bold">{{ number_format((float) $paiement->montant, 0, ',', ' ') }} FCFA</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Aucun paiement enregistré.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    {{ $paiements->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
