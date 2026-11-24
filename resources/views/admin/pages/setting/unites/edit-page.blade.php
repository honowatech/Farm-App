@extends('layouts.app')

@section('title', 'Modifier une unité')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <h2 class="content-header-title mb-0">Modifier une unité</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('unite.index') }}">Unités</a></li>
                        <li class="breadcrumb-item active">Modification</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Modifier {{ $unite->libelle }}</h3></div>
                <div class="card-body">
                    <form action="{{ route('unite.update', $unite->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="libelle">Libellé <span class="text-danger">*</span></label>
                            <input id="libelle" name="table_data[0]" type="text" maxlength="255" class="form-control @error('table_data.0') is-invalid @enderror" value="{{ old('table_data.0', $unite->libelle) }}" required>
                            @error('table_data.0')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="abrege">Abréviation <span class="text-danger">*</span></label>
                            <input id="abrege" name="table_data[1]" type="text" maxlength="255" class="form-control @error('table_data.1') is-invalid @enderror" value="{{ old('table_data.1', $unite->abrege) }}" required>
                            @error('table_data.1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @error('table_data')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('unite.index') }}" class="btn btn-outline-secondary mr-1">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
