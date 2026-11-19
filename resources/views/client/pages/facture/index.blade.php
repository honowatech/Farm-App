@extends('layouts.app')

@section('title', 'Mes factures')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Mes factures</h2>
                    </div>
                </div>
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
                                <th class="text-right">Total TTC</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($factures as $facture)
                                <tr>
                                    <td>{{ $facture->matricule }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($facture->date_facturation)->format('d/m/Y') }}</td>
                                    <td class="text-right">{{ number_format((float) $facture->total_ttc, 0, ',', ' ') }} FCFA</td>
                                    <td class="text-right">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('client.factures.show', $facture) }}">Voir</a>
                                        <a class="btn btn-sm btn-outline-danger" href="{{ route('client.factures.pdf', $facture) }}"><i data-feather="download" class="mr-25"></i>PDF</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">Aucune facture.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($factures->hasPages())
                    <div class="card-footer">
                        {{ $factures->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
