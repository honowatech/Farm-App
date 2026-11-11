@extends('layouts.app')

@section('title')
{{'Modifier la Mortalité'}}
@endsection

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="mb-2 content-header-left col-md-12 col-12">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="float-left mb-0 content-header-title">Modifier la Mortalité</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('mortalite.index') }}">Mortalité</a></li>
                                <li class="breadcrumb-item active">Modifier</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section id="multiple-column-form">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Formulaire de modification</h3>
                            </div>
                            <div class="card-body">
                                <form class="form" action="{{ route('mortalite.update', $mortalite->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mx-auto row col-lg-10">
                                        <div class="mx-auto col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="id_bande">Bande</label>
                                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                <div class="form-group">
                                                    <select class="select2 form-control @error('id_bande') is-invalid @enderror" id="id_bande" name="id_bande" required>
                                                        <option value="">Choisir une bande</option>
                                                        @foreach($bandes as $bande)
                                                            <option value="{{ $bande->id }}" {{ old('id_bande', $mortalite->id_bande) == $bande->id ? 'selected' : '' }}>{{ $bande->nom }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_bande')
                                                    <small class="alert alert-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mx-auto col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="date">Date</label>
                                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                <div class="form-group">
                                                    <div class="input-group input-group-merge @error('date') is-invalid @enderror">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i data-feather="calendar"></i></span>
                                                        </div>
                                                        <input type="date" id="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date', $mortalite->date->format('Y-m-d')) }}" required>
                                                    </div>
                                                    @error('date')
                                                    <small class="alert alert-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mx-auto col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="nombre_morts">Nombre de morts</label>
                                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                <div class="form-group">
                                                    <div class="input-group input-group-merge @error('nombre_morts') is-invalid @enderror">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i data-feather="alert-triangle"></i></span>
                                                        </div>
                                                        <input type="number" inputmode="numeric" id="nombre_morts" class="form-control @error('nombre_morts') is-invalid @enderror" name="nombre_morts" placeholder="Ex : 5" value="{{ old('nombre_morts', $mortalite->nombre_morts) }}" min="0" required>
                                                    </div>
                                                    @error('nombre_morts')
                                                    <small class="alert alert-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mx-auto col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="id_cause">Cause</label>
                                                <span class="cursor-pointer text-info" title="Optionnel">Optionnel</span>
                                                <div class="form-group">
                                                    <select class="select2 form-control" id="id_cause" name="id_cause">
                                                        <option value="">Choisir une cause</option>
                                                        @foreach($causes as $cause)
                                                            <option value="{{ $cause->id }}" {{ old('id_cause', $mortalite->id_cause) == $cause->id ? 'selected' : '' }}>{{ $cause->libelle }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mx-auto col-md-8 col-12">
                                            <div class="form-group">
                                                <label for="observation">Observation</label>
                                                <span class="cursor-pointer text-info" title="Optionnel">Optionnel</span>
                                                <div class="form-group">
                                                    <textarea class="form-control @error('observation') is-invalid @enderror" id="observation" placeholder="Observation" name="observation" rows="3">{{ old('observation', $mortalite->observation) }}</textarea>
                                                    @error('observation')
                                                    <small class="alert alert-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row col-12">
                                        <div class="mx-auto text-center d-none d-md-block col-lg-8 col-md-12 col-12">
                                            <a href="{{ route('mortalite.index') }}" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-danger mt-md-2 mr-md-4">
                                                <i class="mr-1" data-feather="x"></i> Annuler
                                            </a>
                                            <button type="submit" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success mt-md-2">
                                                <i class="mr-1" data-feather="save"></i> Enregistrer
                                            </button>
                                        </div>
                                        <div class="mx-auto text-center d-block d-md-none col-lg-8 col-md-12 col-12">
                                            <button type="submit" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success mt-md-2 mr-md-2">
                                                <i class="mr-1" data-feather="save"></i> Enregistrer
                                            </button>
                                            <a href="{{ route('mortalite.index') }}" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-danger mt-md-2">
                                                <i class="mr-1" data-feather="x"></i> Annuler
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

