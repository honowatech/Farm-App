@extends('layouts.app')

@section('title')
{{'Editer '}} {{ucfirst(($veterinaire->nom))}}
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
                        <h2 class="float-left mb-0 content-header-title">Editer {{name($veterinaire->nom)}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('veterinaire.index')}}">
                                    Liste des vétérinaires
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Editer {{name($veterinaire->nom)}}
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
                                    <form class="form" action="{{route('veterinaire.update',$veterinaire->id)}}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="mx-auto row col-12 col-lg-8">
                                            <div class="mx-auto col-12">
                                                <div class="form-group">
                                                        <label for="name">Nom et Prénoms</label>
                                                        <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-merge @error('nom') is-invalid @enderror">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i data-feather="at-sign"></i></span>
                                                            </div>
                                                            <input type="text" id="nom" class="form-control @error('nom') is-invalid @enderror" name="nom" placeholder="Nom et Prénoms" value="{{ old('nom') == null ? $veterinaire->nom : old('nom') }}" / required autofocus>
                                                        </div>
                                                        @error('nom')
                                                        <small class="alert alert-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                        <label for="telephone">Numéro de téléphone</label>
                                                        <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-merge  @error('telephone') is-invalid @enderror">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="mr-1 flag-icon flag-icon-cm"></i>
                                                                    +237
                                                                </span>
                                                            </div>
                                                            <input type="telephone" id="telephone" class="form-control @error('telephone') is-invalid @enderror" placeholder="Numéro de téléphone" value="{{ old('telephone') == null ? $veterinaire->telephone : old('telephone') }}" onkeyup="phone('telephone');" required/>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i data-feather="phone"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <input type="text" hidden id="telephone2" name="telephone"/>
                                                        @error('telephone')
                                                                <small class="alert alert-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                        <label for="telephone_secondaire">Deuxième numéro de téléphone</label>
                                                        <span class="cursor-pointer text-info" title="Optionel">Optionel</span>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-merge  @error('telephone_secondaire') is-invalid @enderror">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="mr-1 flag-icon flag-icon-cm"></i>
                                                                    +237
                                                                </span>
                                                            </div>
                                                            <input type="telephone_secondaire" id="telephone_secondaire" class="form-control @error('telephone_secondaire') is-invalid @enderror" placeholder="Numéro de téléphone" value="{{ old('telephone_secondaire') == null ? $veterinaire->telephone_secondaire : old('telephone_secondaire') }}" onkeyup="phone('telephone_secondaire');"/>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i data-feather="phone"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <input type="text" hidden id="telephone_secondaire2" name="telephone_secondaire"/>
                                                        @error('telephone_secondaire')
                                                                <small class="alert alert-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mx-auto col-12">
                                                <div class="form-group">
                                                        <label for="adresse">Adresse</label>
                                                        <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                            <textarea type="text" id="adresse" class="form-control @error('adresse') is-invalid @enderror" name="adresse" rows="3" placeholder="Adresse détaillée de la Boutique" / required>{{ old('adresse') == null ? $veterinaire->adresse : old('adresse') }}</textarea>
                                                        @error('adresse')
                                                        <small class="alert alert-danger">{{ $message }}</small>
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
        document.querySelector('#VeterinaireListe').classList.add('active');
        phone('telephone_secondaire');
        phone('telephone');
    </script>
@endsection
