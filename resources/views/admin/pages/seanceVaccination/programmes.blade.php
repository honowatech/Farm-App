@extends('layouts.app')

@section('title', 'Programmes de vaccination')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="mb-2 content-header-left col-md-12 col-12">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="float-left mb-0 content-header-title">Programmes de vaccination</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                                <li class="breadcrumb-item active">Programmes de vaccination</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div data-ajax-table>
            @forelse($programmes->getCollection() as $typeAnimalId => $lignes)
                @php $typeAnimal = $lignes->first()->typeAnimal; @endphp
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $typeAnimal->nom ?? 'Type d\'animal #'.$typeAnimalId }}</h4>
                        <span class="badge badge-pill badge-glow badge-primary">{{ $lignes->count() }} étape(s)</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Âge (jours)</th>
                                    <th>Vaccin</th>
                                    <th>Rappel (jours)</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lignes as $programme)
                                    <tr>
                                        <td>{{ $programme->age_jour }}</td>
                                        <td class="font-weight-bold">{{ $programme->nom_vaccin }}</td>
                                        <td>{{ $programme->rappel_jours ?? '—' }}</td>
                                        <td>{{ $programme->description ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="alert alert-primary">
                    Aucun programme de vaccination n'est configuré.
                </div>
            @endforelse
            <div class="px-1">
                {{ $programmes->links() }}
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
