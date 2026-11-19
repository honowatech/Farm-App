@extends('layouts.app')

@section('title', 'Forfaits')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Forfaits</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-3 col-12 text-right">
                <a href="{{ route('admin.plans.create') }}" class="btn btn-success">
                    <i data-feather="plus" class="mr-50"></i> Ajouter
                </a>
            </div>
        </div>

        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Code</th>
                                    <th>Prix</th>
                                    <th>Période</th>
                                    <th>Essai</th>
                                    <th>Fonctionnalités</th>
                                    <th>Actif</th>
                                    <th>Abonnements</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($plans as $plan)
                                    <tr>
                                        <td>{{ $plan->name }}</td>
                                        <td><code>{{ $plan->code }}</code></td>
                                        <td>{{ number_format((float) $plan->price, 0, ',', ' ') }} {{ $plan->currency }}</td>
                                        <td>{{ $plan->billing_interval === 'annually' ? 'Annuel' : 'Mensuel' }}</td>
                                        <td>{{ (int) $plan->trial_days > 0 ? $plan->trial_days.' j' : '—' }}</td>
                                        <td>
                                            @php
                                                $enabled = collect($plan->features)->filter()->keys();
                                            @endphp
                                            @if ($enabled->isNotEmpty())
                                                <span class="badge badge-light-primary">{{ $enabled->count() }} / {{ count(config('features.features', [])) }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-glow badge-{{ $plan->is_active ? 'success' : 'secondary' }}">
                                                {{ $plan->is_active ? 'Oui' : 'Non' }}
                                            </span>
                                        </td>
                                        <td>{{ $plan->subscriptions_count }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-sm btn-primary">
                                                <i data-feather="edit-2" class="mr-50"></i> Modifier
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="text-center text-muted">Aucun forfait.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $plans->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
