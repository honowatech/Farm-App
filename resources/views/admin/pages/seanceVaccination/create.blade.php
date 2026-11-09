@extends('admin/templates/template')

@section('title')
{{'New Séance de vaccination'}}
@endsection
@section('menu')
    @php
        $role = auth()->user()->type_user->libelle;
    @endphp
    @include($role.'.menu.menu')
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
                                <h2 class="float-left mb-0 content-header-title">Nouvelle Séance de vaccination</h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                                    </li>
                                    <li class="breadcrumb-item active">Ajouter une Séance de vaccination
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
                                        <form class="form" action="{{route('seanceVaccination.store')}}" method="POST">
                                        	@csrf
                                            <div class="mx-auto row col-lg-9">
                                                    <div class="mx-auto col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="bande">Bandes</label>
                                                            <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                            <select class="select2 form-control" id="id_bande" name="bande">
                                                                <option value="">
                                                                        Choisir une bande
                                                                </option>
                                                                @foreach($bandes as $bande)
                                                                    @if($bande->statut_bande != 'terminee')
                                                                        <option {{old('bande') == $bande->id ? 'selected' : ''}} value="{{$bande->id}}">
                                                                        {{$bande->nom}}
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                            @error('bande')
                                                                <small class="alert alert-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                <div class="mx-auto col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="veterinaire">veterinaires</label>
                                                            <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                            <select class="select2 form-control" id="id_veterinaire" name="veterinaire">
                                                                <option value="">
                                                                    Choisir Le vétérinaire
                                                                </option>
                                                                @foreach($veterinaires as $veterinaire)
                                                                    @if($veterinaire->statut == 1)
                                                                    <option {{old('veterinaire') == $veterinaire->id ? 'selected' : ''}} value="{{$veterinaire->id}}">
                                                                        {{$veterinaire->nom}}
                                                                    </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                            @error('veterinaire')
                                                                <small class="alert alert-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                </div>

                                            <div class="mx-auto col-md-6 col-12">
                                                    <div class="form-group">
                                                            <label for="vaccin">Vaccin</label>
                                                            <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                        <div class="form-group">
                                                                <div class="input-group input-group-merge  @error('vaccin') is-invalid @enderror">
                                                                        <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="mr-1"><img src="{{asset('app-assets/images/avatars/produit.jpg ')}}" style="height: 1.5rem; width: 1.5rem; display: inline;"></i>
                                                                </span>
                                                            </div>

                                                                    <input  id="vaccin" class="form-control @error('vaccin') is-invalid @enderror" placeholder="Vaccin administé" name="vaccin" value="{{ old('vaccin') }}"/>
                                                                </div>
                                                                @error('vaccin')
                                                                        <small class="alert alert-danger">{{ $message }}</small>
                                                                @enderror
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="offset-md-2 col-md-4 col-12">
                                                <label class="pl-3 mb-2">Verification</label>
                                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="mx-auto form-group row">
                                                        <div class="custom-control custom-radio col-6">
                                                            <input type="radio" id="choix1" name="verif" class="m-auto verification custom-control-input" value = 1 checked>
                                                            <label class="custom-control-label" for="choix1">
                                                                Oui
                                                            </label>
                                                        </div>
                                                        <div class="custom-control custom-radio col-6">
                                                            <input type="radio" id="choix" name="verif" class="m-auto verification custom-control-input" value = 0>
                                                            <label class="custom-control-label" for="choix">
                                                                Non
                                                            </label>
                                                        </div>
                                                    </div>
                                            </div>

                                            <div class="mx-auto col-12 col-md-12">
                                                        <label for="adresse">Commentaire</label>
                                                        <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <textarea type="text" id="adresse" class="form-control @error('adresse') is-invalid @enderror" name="commentaire" rows="3" placeholder="Commentaire détaillé de la Séance de vaccination"  required />{{ old('commentaire') }}</textarea>
                                                        @error('commentaire')
                                                            <small class="alert alert-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                            </div>
                                                </div>

                                            <div class="row col-12">
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
		document.querySelector('#SeanceVaccinationAjouter').classList.add('active');
	</script>
@endsection
