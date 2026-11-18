@extends('layouts.app')

@section('title', 'Rôles et permissions')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Rôles et permissions</h2>
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
                                    <th>Profil</th>
                                    <th>Utilisateurs</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                    <tr>
                                        <td>
                                            <span class="badge badge-glow badge-primary">{{ $role->libelle }}</span>
                                            @if ($role->libelle === 'superadmin')
                                                <span class="badge badge-glow badge-dark ml-50">plateforme</span>
                                            @endif
                                        </td>
                                        <td>{{ $role->users_count }}</td>
                                        <td>{{ $role->permissions_count }}</td>
                                        <td>
                                            <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-primary">Voir</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted">Aucun profil.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
