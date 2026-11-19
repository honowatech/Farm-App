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
                    @php
                        $statusLabels = [
                            'active' => 'Actif',
                            'trialing' => 'Essai',
                            'suspended' => 'Suspendu',
                            'canceled' => 'Résilié',
                            'pending' => 'En attente',
                            'past_due' => 'Impayé',
                        ];
                        $statusBadges = [
                            'active' => 'success',
                            'trialing' => 'info',
                            'suspended' => 'warning',
                            'canceled' => 'secondary',
                            'pending' => 'primary',
                            'past_due' => 'danger',
                        ];
                    @endphp

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Entreprise</th>
                                    <th>Forfait</th>
                                    <th>Statut</th>
                                    <th>Début</th>
                                    <th>Fin de période</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($subscriptions as $subscription)
                                    <tr>
                                        <td>{{ $subscription->tenant?->name ?? '—' }}</td>
                                        <td>{{ $subscription->plan?->name ?? '—' }}</td>
                                        <td>
                                            <span class="badge badge-glow badge-{{ $statusBadges[$subscription->status] ?? 'secondary' }}">
                                                {{ $statusLabels[$subscription->status] ?? $subscription->status }}
                                            </span>
                                        </td>
                                        <td>{{ $subscription->starts_at?->format('d/m/Y') ?? '—' }}</td>
                                        <td>{{ $subscription->current_period_ends_at?->format('d/m/Y') ?? ($subscription->trial_ends_at?->format('d/m/Y') ?? '—') }}</td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i data-feather="more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if ($subscription->status !== 'active')
                                                        <a class="dropdown-item sub-action" href="#" data-action="activate"
                                                            data-url="{{ route('admin.subscriptions.status', $subscription) }}"
                                                            data-label="Activer l'abonnement de {{ $subscription->tenant?->name ?? 'cette entreprise' }} ?">
                                                            <i data-feather="check-circle" class="mr-50"></i> Activer
                                                        </a>
                                                    @endif

                                                    @if (in_array($subscription->status, ['active', 'trialing']))
                                                        <a class="dropdown-item sub-action text-warning" href="#" data-action="suspend"
                                                            data-url="{{ route('admin.subscriptions.status', $subscription) }}"
                                                            data-label="Suspendre l'abonnement de {{ $subscription->tenant?->name ?? 'cette entreprise' }} ?">
                                                            <i data-feather="pause-circle" class="mr-50"></i> Suspendre
                                                        </a>
                                                    @endif

                                                    @if ($subscription->status === 'suspended')
                                                        <a class="dropdown-item sub-action" href="#" data-action="resume"
                                                            data-url="{{ route('admin.subscriptions.status', $subscription) }}"
                                                            data-label="Reprendre l'abonnement de {{ $subscription->tenant?->name ?? 'cette entreprise' }} ?">
                                                            <i data-feather="play-circle" class="mr-50"></i> Reprendre
                                                        </a>
                                                    @endif

                                                    <a class="dropdown-item sub-action" href="#" data-action="extend"
                                                        data-url="{{ route('admin.subscriptions.extend', $subscription) }}">
                                                        <i data-feather="calendar" class="mr-50"></i> Prolonger
                                                    </a>

                                                    <a class="dropdown-item sub-action" href="#" data-action="change_plan"
                                                        data-url="{{ route('admin.subscriptions.plan', $subscription) }}">
                                                        <i data-feather="layers" class="mr-50"></i> Changer de forfait
                                                    </a>

                                                    @if (! in_array($subscription->status, ['canceled']))
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item sub-action text-danger" href="#" data-action="cancel"
                                                            data-url="{{ route('admin.subscriptions.status', $subscription) }}"
                                                            data-label="Résilier définitivement l'abonnement de {{ $subscription->tenant?->name ?? 'cette entreprise' }} ?">
                                                            <i data-feather="trash-2" class="mr-50"></i> Résilier
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted">Aucun abonnement.</td></tr>
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

@section('javascript')
<script type="text/javascript">
    var plansOptions = @json($plans->pluck('name', 'id'));

    function post(url, data) {
        return $.ajax({
            url: url,
            method: 'POST',
            data: data,
            dataType: 'json'
        });
    }

    function refresh() {
        location.reload();
    }

    $(document).on('click', '.sub-action', function (e) {
        e.preventDefault();
        var $a = $(this);
        var action = $a.data('action');
        var url = $a.data('url');
        var label = $a.data('label') || '';

        if (action === 'activate' || action === 'suspend' || action === 'resume' || action === 'cancel') {
            var isDanger = (action === 'cancel');
            Swal.fire({
                title: isDanger ? 'Résilier cet abonnement ?' : 'Confirmer l\'action',
                text: label,
                icon: isDanger ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, continuer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: isDanger ? '#ea5455' : '#7367f0',
                cancelButtonColor: '#6e6b7b'
            }).then(function (result) {
                if (result.isConfirmed) {
                    post(url, { action: action })
                        .done(function (res) {
                            toastr.success(res.message || 'Abonnement mis à jour.', 'Succès');
                            refresh();
                        })
                        .fail(function (xhr) {
                            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Une erreur est survenue.';
                            toastr.error(msg, 'Erreur');
                        });
                }
            });
            return;
        }

        if (action === 'extend') {
            Swal.fire({
                title: 'Prolonger l\'abonnement',
                text: label,
                input: 'number',
                inputLabel: 'Nombre de jours à ajouter',
                inputAttributes: { min: 1, max: 3650 },
                showCancelButton: true,
                confirmButtonText: 'Prolonger',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#7367f0'
            }).then(function (result) {
                if (result.isConfirmed && result.value) {
                    post(url, { days: result.value })
                        .done(function (res) {
                            toastr.success(res.message || 'Abonnement prolongé.', 'Succès');
                            refresh();
                        })
                        .fail(function (xhr) {
                            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Une erreur est survenue.';
                            toastr.error(msg, 'Erreur');
                        });
                }
            });
            return;
        }

        if (action === 'change_plan') {
            Swal.fire({
                title: 'Changer de forfait',
                text: label,
                input: 'select',
                inputOptions: plansOptions,
                inputPlaceholder: 'Choisir un forfait',
                showCancelButton: true,
                confirmButtonText: 'Changer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#7367f0'
            }).then(function (result) {
                if (result.isConfirmed && result.value) {
                    post(url, { plan_id: result.value })
                        .done(function (res) {
                            toastr.success(res.message || 'Forfait modifié.', 'Succès');
                            refresh();
                        })
                        .fail(function (xhr) {
                            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Une erreur est survenue.';
                            toastr.error(msg, 'Erreur');
                        });
                }
            });
            return;
        }
    });
</script>
@endsection
