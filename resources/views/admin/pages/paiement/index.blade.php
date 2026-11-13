@extends('layouts.app')

@section('title', 'Transactions de paiement')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Transactions de paiement</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between w-100">
                        <h4 class="card-title">Transactions MaMoNi</h4>
                        <a class="btn btn-success btn-gradient-success" href="{{ route('paiement.test') }}">
                            <i data-feather="play"></i> Tester un paiement
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div data-ajax-table>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Référence</th>
                                    <th>Téléphone</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $badges = [
                                        'initiated' => 'secondary',
                                        'pending' => 'warning',
                                        'successful' => 'success',
                                        'failed' => 'danger',
                                        'cancelled' => 'danger',
                                        'expired' => 'danger',
                                    ];
                                @endphp
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $transaction->external_reference }}</td>
                                        <td>{{ $transaction->phone_number }}</td>
                                        <td>{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</td>
                                        <td>
                                            <span class="badge badge-glow badge-{{ $badges[$transaction->status] ?? 'secondary' }}">
                                                {{ $transaction->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Aucune transaction.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-1">
                        {{ $transactions->links() }}
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

