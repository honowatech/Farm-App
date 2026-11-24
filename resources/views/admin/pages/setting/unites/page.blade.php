@extends('layouts.app')

@section('title', 'Unités de mesure')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Unités de mesure</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                                <li class="breadcrumb-item active">Unités</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            @if(session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h3 class="card-title mb-25">Unités utilisées dans l'entreprise</h3>
                        <p class="text-muted mb-0">Gérez les libellés et abréviations utilisés dans les formulaires.</p>
                    </div>
                    <a href="{{ route('unite.create') }}" class="btn btn-primary mt-1 mt-md-0">
                        <i data-feather="plus" class="mr-25"></i> Ajouter une unité
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive" data-ajax-table>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Libellé</th>
                                    <th>Abréviation</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unites as $unite)
                                    <tr>
                                        <td>{{ ucfirst($unite->libelle) }}</td>
                                        <td><span class="font-weight-bolder">{{ ucfirst($unite->abrege) }}</span></td>
                                        <td class="text-right">
                                            <a href="{{ route('unite.show', $unite->id) }}" class="btn btn-sm btn-outline-secondary" aria-label="Voir {{ $unite->libelle }}">
                                                <i data-feather="eye"></i>
                                            </a>
                                            <a href="{{ route('unite.edit', $unite->id) }}" class="btn btn-sm btn-outline-primary" aria-label="Modifier {{ $unite->libelle }}">
                                                <i data-feather="edit-2"></i>
                                            </a>
                                            <form method="POST" action="{{ route('unite.destroy', $unite->id) }}" class="d-inline" onsubmit="return confirm('Supprimer cette unité ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Supprimer {{ $unite->libelle }}">
                                                    <i data-feather="trash-2"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-2 text-center text-muted">Aucune unité enregistrée pour cette entreprise.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-1">
                        {{ $unites->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
