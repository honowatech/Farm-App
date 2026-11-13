@extends('layouts.app')

@section('title', 'Suivi de mortalité')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Suivi de mortalité</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between w-100">
                        <h4 class="card-title">Mortalités enregistrées</h4>
                        <a class="btn btn-secondary btn-gradient-secondary" href="{{ route('mortalite.create') }}">
                            <i data-feather="plus" style="width: 20px;height: 20px;"></i> Nouvelle mortalité
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-1">
                        <div class="row">
                            <div class="col-12 col-md-4 form-group">
                                <label for="id_bande">Bande</label>
                                <select name="id_bande" id="id_bande" class="form-control" onchange="this.form.submit()">
                                    <option value="">Toutes les bandes</option>
                                    @foreach($bandes as $bande)
                                        <option value="{{ $bande->id }}" {{ request('id_bande') == $bande->id ? 'selected' : '' }}>{{ $bande->nom }}</option>
                                    @endforeach
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
                                    <th>Bande</th>
                                    <th>Morts</th>
                                    <th>Cause</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mortalites as $mortalite)
                                    <tr>
                                        <td>{{ $mortalite->date->format('d/m/Y') }}</td>
                                        <td>{{ $mortalite->bande?->nom ?? '—' }}</td>
                                        <td>{{ $mortalite->nombre_morts }}</td>
                                        <td>{{ $mortalite->cause?->libelle ?? '—' }}</td>
                                        <td>
                                            <div class="table-actions">
                                                <a class="btn btn-sm btn-icon btn-outline-primary rounded-circle"
                                                   href="{{ route('mortalite.edit', $mortalite->id) }}"
                                                   title="Modifier" aria-label="Modifier">
                                                    <i data-feather="edit-2"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-icon btn-outline-danger rounded-circle"
                                                        title="Supprimer" aria-label="Supprimer"
                                                        onclick="actions('form_delete_{{ $mortalite->id }}', 'Supprimer', 'cette mortalité')">
                                                    <i data-feather="trash-2"></i>
                                                </button>
                                                <form id="form_delete_{{ $mortalite->id }}" action="{{ route('mortalite.destroy', $mortalite->id) }}" method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Aucune mortalité enregistrée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-1">
                        {{ $mortalites->links() }}
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
