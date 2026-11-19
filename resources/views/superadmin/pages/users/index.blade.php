@extends('layouts.app')

@section('title', 'Utilisateurs')

@php
    function triUrl($colonne) {
        $sort = request('sort', 'name');
        $direction = request('direction', 'asc');
        $nouvelleDirection = ($sort === $colonne && $direction === 'asc') ? 'desc' : 'asc';
        return route('admin.users.index', ['sort' => $colonne, 'direction' => $nouvelleDirection]);
    }
@endphp

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Utilisateurs</h2>
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
                                    <th>
                                        <a href="{{ triUrl('name') }}" class="d-inline-flex align-items-center text-body">
                                            Nom
                                            @if($sort === 'name')<i data-feather="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width:14px;height:14px;" class="ml-25"></i>@endif
                                        </a>
                                    </th>
                                    <th>Email</th>
                                    <th>
                                        <a href="{{ triUrl('type') }}" class="d-inline-flex align-items-center text-body">
                                            Profil global
                                            @if($sort === 'type')<i data-feather="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width:14px;height:14px;" class="ml-25"></i>@endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ triUrl('entreprise') }}" class="d-inline-flex align-items-center text-body">
                                            Entreprises
                                            @if($sort === 'entreprise')<i data-feather="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width:14px;height:14px;" class="ml-25"></i>@endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ triUrl('statut') }}" class="d-inline-flex align-items-center text-body">
                                            Statut
                                            @if($sort === 'statut')<i data-feather="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width:14px;height:14px;" class="ml-25"></i>@endif
                                        </a>
                                    </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->type_user)
                                                <span class="badge badge-glow badge-primary">{{ $user->type_user->libelle }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @forelse ($user->tenants as $tenant)
                                                <span class="badge badge-light-info mr-50">{{ $tenant->name }} ({{ $tenant->pivot->role_key }})</span>
                                            @empty
                                                <span class="text-muted">—</span>
                                            @endforelse
                                        </td>
                                        <td>
                                            <span class="badge badge-glow badge-{{ $user->statut ? 'success' : 'danger' }}">
                                                {{ $user->statut ? 'Actif' : 'Désactivé' }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-{{ $user->statut ? 'danger' : 'success' }}">
                                                    {{ $user->statut ? 'Désactiver' : 'Activer' }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted">Aucun utilisateur.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
