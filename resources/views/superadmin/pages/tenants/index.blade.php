@extends('layouts.app')

@section('title', 'Entreprises')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Entreprises</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-3 col-12 text-right">
                <a href="{{ route('admin.tenants.create') }}" class="btn btn-success">
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
                                    <th>Slug</th>
                                    <th>Statut</th>
                                    <th>Démo</th>
                                    <th>Membres</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tenants as $tenant)
                                    <tr>
                                        <td>{{ $tenant->name }}</td>
                                        <td><code>{{ $tenant->slug }}</code></td>
                                        <td>
                                            <span class="badge badge-glow badge-{{ $tenant->status === 'active' ? 'success' : ($tenant->status === 'suspended' ? 'danger' : 'warning') }}">
                                                {{ $tenant->status }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($tenant->is_demo)
                                                <span class="badge badge-glow badge-warning">oui</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>{{ $tenant->users_count }}</td>
                                        <td>
                                            <form action="{{ route('admin.tenants.toggle', $tenant) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-{{ $tenant->status === 'active' ? 'danger' : 'success' }}">
                                                    {{ $tenant->status === 'active' ? 'Suspendre' : 'Activer' }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted">Aucune entreprise.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $tenants->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
