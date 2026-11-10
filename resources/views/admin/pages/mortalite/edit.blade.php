@extends('layouts.app')

@section('title', 'Modifier la mortalité')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Modifier la mortalité</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('mortalite.update', $mortalite->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-12 col-md-6 form-group">
                                <label for="id_bande">Bande <span class="text-danger">*</span></label>
                                <select name="id_bande" id="id_bande" class="form-control @error('id_bande') is-invalid @enderror" required>
                                    @foreach($bandes as $bande)
                                        <option value="{{ $bande->id }}" {{ old('id_bande', $mortalite->id_bande) == $bande->id ? 'selected' : '' }}>{{ $bande->nom }}</option>
                                    @endforeach
                                </select>
                                @error('id_bande')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="col-12 col-md-6 form-group">
                                <label for="date">Date <span class="text-danger">*</span></label>
                                <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $mortalite->date->format('Y-m-d')) }}" required>
                                @error('date')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="col-12 col-md-6 form-group">
                                <label for="nombre_morts">Nombre de morts <span class="text-danger">*</span></label>
                                <input type="number" inputmode="numeric" name="nombre_morts" id="nombre_morts" class="form-control @error('nombre_morts') is-invalid @enderror" value="{{ old('nombre_morts', $mortalite->nombre_morts) }}" min="0" required>
                                @error('nombre_morts')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="col-12 col-md-6 form-group">
                                <label for="id_cause">Cause</label>
                                <select name="id_cause" id="id_cause" class="form-control">
                                    <option value="">— Choisir une cause —</option>
                                    @foreach($causes as $cause)
                                        <option value="{{ $cause->id }}" {{ old('id_cause', $mortalite->id_cause) == $cause->id ? 'selected' : '' }}>{{ $cause->libelle }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 form-group">
                                <label for="observation">Observation</label>
                                <textarea name="observation" id="observation" rows="2" class="form-control">{{ old('observation', $mortalite->observation) }}</textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Enregistrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
