@extends('layouts.app')

@section('title')
{{'New Séance de nettoyage'}}
@endsection
@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
            <div class="content-header row">
        	   <div class="content-header-left col-md-12 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                                <h2 class="content-header-title float-left mb-0">Nouvelle Séance de nettoyage</h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                                    </li>
                                    <li class="breadcrumb-item active">Ajouter une Séance de nettoyage
                                    </li>
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
                                        <h3 class="card-title">Formulaire de création</h3>
                                    </div>
                                    <div class="card-body">
                                        <form class="form" action="{{route('nettoyage.store')}}" method="POST">
                                        	@csrf
                                            <div class="row col-lg-9 mx-auto">
                                                    <div class="col-md-6 col-12 mx-auto">
                                                        <div class="form-group">
                                                            <label for="batiment">Batiments</label>
                                                            <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                                                            <select class="select2 form-control" id="id_batiment" name="batiment" required>
                                                                <option value="">
                                                                        Choisir un batiment
                                                                </option>
                                                                @foreach($batiments as $batiment)
                                                                        <option {{old('batiment') == $batiment->id ? 'selected' : ''}} value="{{$batiment->id}}">
                                                                        {{$batiment->nom}}
                                                                        </option>

                                                                @endforeach
                                                            </select>
                                                            @error('batiment')
                                                                <small class="alert alert-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                <div class="col-md-6 col-12 mx-auto">
                                                        <div class="form-group">
                                                            <label for="agent">Agent</label>
                                                            <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                                                            <select class="select2 form-control"placeholder="bfiqunf" id="id_agent" name="agent[]"  multiple="multiple" required>
                                                                @foreach($agents as $agent)
                                                                    @if($agent->statut == 1)
                                                                    <option {{ in_array($agent->id,$old_agent) ? 'selected' : ''}} value="{{$agent->id}}">
                                                                        {{$agent->nom}}
                                                                    </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                            @error('agent')
                                                                <small class="alert alert-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                </div>

                                            <div class="col-md-6 col-12 mx-auto">
                                                <div class="form-group">
                                                    <label for="date">Date de nettoyage</label>
                                                    <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                                                    <div class="input-group input-group-merge @error('date')  is-invalid @enderror">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="mr-1" data-feather="calendar"></i></span>
                                                        </div>
                                                        <input class=" @error('date') is-invalid @enderror cursor-pointer form-control flatpickr-human-friendly input" placeholder="January 01, 2024" type="text" id="date" name="date" required value="{{old('date')}}">
                                                    </div>
                                                    @error('date')
                                                        <small class="alert alert-danger"> {{$message}} </small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="offset-md-2 col-md-4 col-12  ">
                                                <label class="mb-2 pl-3">Verification</label>
                                                <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                                                    <div class="form-group row mx-auto ">
                                                        <div class="custom-control custom-radio col-6">
                                                            <input type="radio" id="choix1" name="verif" class="verification m-auto custom-control-input" value = 1 checked>
                                                            <label class="custom-control-label" for="choix1">
                                                                Oui
                                                            </label>
                                                        </div>
                                                        <div class="custom-control custom-radio col-6">
                                                            <input type="radio" id="choix" name="verif" class="verification m-auto custom-control-input" value = 0>
                                                            <label class="custom-control-label" for="choix">
                                                                Non
                                                            </label>
                                                        </div>
                                                    </div>
                                            </div>

                                            <div class="col-12 col-md-12 mx-auto">
                                                        <label for="adresse">Commentaire</label>
                                                        <span class="text-info cursor-pointer" title="Optionnel">Optionnel</span>
                                                    <div class="form-group">
                                                        <textarea type="text" id="adresse" class="form-control @error('adresse') is-invalid @enderror" name="commentaire" rows="3" placeholder="Commentaire détaillé de la Séance de nettoyage"/>{{ old('commentaire') }}</textarea>
                                                        @error('commentaire')
                                                            <small class="alert alert-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                            </div>
                                                </div>

                                            <div class="row col-12">
                                                <div class="d-none d-md-block text-center col-bg-5 col-lg-8 col-md-12 col-12 mx-auto">
                                                     <button type="reset" class="btn col-8 col-sm-7 col-lg-5  col-xl-3 col-md-4 btn-danger mt-1 mt-md-2 mr-md-4">
                                                        <i class="mr-1" data-feather='x'></i> Effacer
                                                   </button>
                                                    <button type="submit" class="btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success mt-1 mt-md-2">
                                                        <i class="mr-1" data-feather='download'></i> Enregistrer
                                                    </button>
                                                </div>
                                                <div class="d-block d-md-none text-center col-bg-5 col-lg-8 col-md-12 col-12 mx-auto">
                                                    <button type="submit" class="btn col-8 col-sm-7 col-lg-5  col-xl-3 col-md-4 btn-success mt-1 mt-md-2 mr-md-2">
                                                        <i class="mr-1" data-feather='download'></i> Enregistrer
                                                    </button>
                                                    <button type="reset" class="btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-danger mt-1 mt-md-2">
                                                        <i class="mr-1" data-feather='x'></i> Effacer
                                                    </button>
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

