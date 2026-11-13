@extends('layouts.app')

@section('title', 'Historique des actions')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Historique des actions</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Journal d'audit</h4>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-1">
                        <div class="row">
                            <div class="col-12 col-md-3 form-group">
                                <label for="evenement">Action</label>
                                <select name="evenement" id="evenement" class="form-control" onchange="this.form.submit()">
                                    <option value="">Toutes les actions</option>
                                    <option value="created" {{ request('evenement') === 'created' ? 'selected' : '' }}>Création</option>
                                    <option value="updated" {{ request('evenement') === 'updated' ? 'selected' : '' }}>Modification</option>
                                    <option value="deleted" {{ request('evenement') === 'deleted' ? 'selected' : '' }}>Suppression</option>
                                </select>
                            </div>
                        </div>
                    </form>

                    <div data-ajax-table>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Utilisateur</th>
                                    <th>Action</th>
                                    <th>Élément</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $activity)
                                    @php
                                        $badges = ['created' => 'success', 'updated' => 'info', 'deleted' => 'danger'];
                                        $labels = ['created' => 'Création', 'updated' => 'Modification', 'deleted' => 'Suppression'];
                                        $badge = $badges[$activity->event] ?? 'secondary';
                                        $label = $labels[$activity->event] ?? $activity->event;
                                    @endphp
                                    <tr>
                                        <td>{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $activity->causer?->name ?? '—' }}</td>
                                        <td>
                                            <span class="badge badge-glow badge-{{ $badge }}">{{ $label }}</span>
                                        </td>
                                        <td>{{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Aucune activité enregistrée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-1">
                        {{ $activities->links() }}
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

