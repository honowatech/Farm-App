@extends('layouts.app')

@section('title', 'Détail d’une dépense')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <h2 class="content-header-title mb-0">Détail de la dépense</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('depenses.index') }}">Dépenses</a></li>
                        <li class="breadcrumb-item active">Détail</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    @include('depenses::depense.show')
                    <a href="{{ route('depenses.index') }}" class="btn btn-outline-secondary">Retour à la liste</a>
                    <a href="{{ route('depenses.edit', $depense->id) }}" class="btn btn-primary">Modifier</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
