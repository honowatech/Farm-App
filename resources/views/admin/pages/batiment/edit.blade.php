@extends('layouts.app')

@section('title')
{{'Editer '}} {{$batiment->nom}}
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
                        <h2 class="float-left mb-0 content-header-title">Editer {{$batiment->nom}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('batiment.index')}}">Liste des Bâtiments</a>
                            </li>
                            <li class="breadcrumb-item active">Editer {{$batiment->nom}}
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
                                    <h4 class="card-title">Formulaire</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form" action="{{route('batiment.update',$batiment->id)}}" method="POST">
                                    	@csrf
                                        @method('PATCH')
                                        <div class="mx-auto row col-12 col-lg-10">
                                        	<div class="mx-auto col-md-8 col-12">
                                                <div class="form-group">
                                                        <label for="nom">Nom</label>
                                                        <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-merge @error('nom') is-invalid @enderror">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i data-feather="map-pin"></i></span>
                                                            </div>
                                                            <input type="text" id="nom" class="form-control @error('nom') is-invalid @enderror" name="nom" placeholder="Nom" value="{{ old('nom') == null ? $batiment->nom : old('nom') }}" / required>
                                                        </div>
                                                        @error('nom')
                                                        <small class="alert alert-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mx-auto col-md-8 col-12">
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                            <textarea type="description" autocomplete="" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="Description" name="description"aria-describedby="basic-default-description1" rows="3" required>{{ old('description') == null ? $batiment->description : old('description') }}</textarea>
                                                        @error('description')
                                                            <small class="alert alert-danger"> {{$message}} </small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mx-auto row col-12">
                                            <div class="mx-auto text-center d-none d-md-block col-bg-5 col-lg-8 col-md-12 col-12">
                                                <button type="reset" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-danger mt-md-2 mr-md-4">
                                                    <i class="mr-1" data-feather='x'></i> Effacer
                                                </button>
                                                <button type="submit" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success mt-md-2">
                                                    <i class="mr-1" data-feather='download'></i> Enregistrer
                                                </button>
                                            </div>
                                            <div class="mx-auto text-center d-block d-md-none col-bg-5 col-lg-8 col-md-12 col-12">
                                                <button type="submit" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success mt-md-2 mr-md-2">
                                                        <i class="mr-1" data-feather='download'></i> Enregistrer
                                                </button>
                                                <button type="reset" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-danger mt-md-2">
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

@section('javascript')
	<script type="text/javascript">
		document.querySelector('#BatimentListe').classList.add('active');
	</script>
@endsection
