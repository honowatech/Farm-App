@extends('layouts.app')

@section('title')
{{'Editer une'}} séance de vaccination
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
                                <h2 class="content-header-title float-left mb-0">Editer une séance de vacciation </h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{route('seanceVaccination.index')}}">Liste des séance de vaccination</a>
                                    </li>
                                    <li class="breadcrumb-item active">Editer une séance de vacciation
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
                                <form class="form" action="{{route('seanceVaccination.update',$seanceVaccination->id)}}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="row col-lg-9 mx-auto">
                                            <div class="col-md-6 col-12 mx-auto">
                                                    <div class="form-group">
                                                            <label for="telephone_secondaire">Vaccin</label>
                                                            <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                                                        <div class="form-group">
                                                                <div class="input-group input-group-merge  @error('vaccin') is-invalid @enderror">
                                                                        <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="mr-1"><img src="{{asset('app-assets/images/avatars/produit.jpg ')}}" style="height: 1.5rem; width: 1.5rem; display: inline;"></i>
                                                                </span>
                                                            </div>

                                                                    <input  id="telephone_secondaire" class="form-control @error('telephone_secondaire') is-invalid @enderror" placeholder="Vaccin administé" name="vaccin" value="{{ $seanceVaccination->vaccin_attribuer }}"/>
                                                                </div>
                                                                @error('vaccin')
                                                                        <small class="alert alert-danger">{{ $message }}</small>
                                                                @enderror
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="offset-md-2 col-md-4 col-12  ">
                                                <label class="mb-2 pl-3 pl-md-3">Verification</label>
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
                                                        <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <textarea type="text" id="adresse" class="form-control @error('adresse') is-invalid @enderror" name="commentaire" rows="3" placeholder="commentaire détaillée de la Séance de vaccination"  required />{{ $seanceVaccination->description }}</textarea>
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
@section('javascript')
    <script type="text/javascript">
        document.querySelector('#SeanceVaccinationListe').classList.add('active');
    </script>
@endsection
