@extends('layouts.app')

@section('title', 'Détail d’une unité')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <h2 class="content-header-title mb-0">Détail de l'unité</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('unite.index') }}">Unités</a></li>
                        <li class="breadcrumb-item active">{{ $unite->libelle }}</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <dl class="row mb-2">
                        <dt class="col-sm-3">Libellé</dt>
                        <dd class="col-sm-9">{{ $unite->libelle }}</dd>
                        <dt class="col-sm-3">Abréviation</dt>
                        <dd class="col-sm-9">{{ $unite->abrege }}</dd>
                    </dl>
                    <a href="{{ route('unite.index') }}" class="btn btn-outline-secondary">Retour à la liste</a>
                    <a href="{{ route('unite.edit', $unite->id) }}" class="btn btn-primary">Modifier</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
