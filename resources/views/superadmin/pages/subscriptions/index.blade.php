@extends('layouts.app')

@section('title', 'Abonnements')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Abonnements</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Entreprise</th>
                                    <th>Forfait</th>
                                    <th>Statut</th>
                                    <th>Début</th>
                                    <th>Fin de période</th>
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
                                        <td>{{ $subscription->starts_at?->format('d/m/Y') ?? '—' }}</td>
                                        <td>{{ $subscription->current_period_ends_at?->format('d/m/Y') ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">Aucun abonnement.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $subscriptions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
