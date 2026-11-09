@extends('admin/templates/template')

@section('title')
{{'New Bande'}}
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
                        <h2 class="float-left mb-0 content-header-title">Nouvel Bande</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Ajouter une Bande
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
                                    <form class="form" action="{{route('bande.store')}}" method="POST">
                                                    @csrf
                                                    <div class="mx-auto row col-lg-10">
                                                        <!-- Bâtiment -->
                                                        <div class="mx-auto col-md-6 col-12">
                                                           <div class="form-group">
                                                                <label for="batiment">Bâtiment</label>
                                                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                                <div class="form-group">
                                                                    <select class="select2 form-control" id="batiment" name="batiment" required>
                                                                        <option value="">
                                                                            Choisir un Bâtiment
                                                                        </option>
                                                                        @foreach($batiments as $batiment)
                                                                            <option value="{{$batiment->id}}" {{ $batiment->id == old('batiment') ? 'selected' : '' }}>
                                                                                {{ strtoupper($batiment->nom) }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('batiment')
                                                                    <small class="alert alert-danger">{{ $message }}</small>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Type d'animal -->
                                                        <div class="mx-auto col-md-6 col-12">
                                                            <div class="form-group">
                                                                <label for="type_animal">Type d'animal</label>
                                                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                                <div class="form-group">
                                                                    <select class="select2 form-control" id="type_animal" name="type_animal" required>
                                                                        <option value="">Choisir un Type d'animal</option>
                                                                        @foreach($types_animal as $type_animal)
                                                                            <option value="{{$type_animal->id}}" {{ $type_animal->id == old('type_animal') ? 'selected' : '' }}>
                                                                                {{ strtoupper($type_animal->nom) }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('type_animal')
                                                                    <small class="alert alert-danger">{{ $message }}</small>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Nom -->
                                                        <div class="mx-auto col-md-6 col-12">
                                                            <div class="form-group">
                                                                <label for="nom">Nom</label>
                                                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                                <div class="input-group input-group-merge @error('nom') is-invalid @enderror">
                                                                    <input type="text" id="nom" class="form-control @error('nom') is-invalid @enderror" name="nom" placeholder="Nom" value="{{ old('nom') }}" required>
                                                                    @error('nom')
                                                                    <small class="alert alert-danger">{{ $message }}</small>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Date de lancement -->
                                                         <div class="mx-auto col-md-6 col-12">
                                                            <div class="form-group">
                                                                <label for="date">Date de lancement</label>
                                                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                                <div class="input-group input-group-merge @error('date')  is-invalid @enderror">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="mr-1" data-feather="calendar"></i></span>
                                                                    </div>
                                                                    <input onchange="seeRecap()" class=" @error('date')  is-invalid @enderror cursor-pointer form-control flatpickr-human-friendly input" placeholder="January 01, 2024" tabindex="0" type="text" id="date" readonly="readonly" name="date" required value="{{old('date')}}">
                                                                </div>
                                                                @error('date')
                                                                    <small class="alert alert-danger"> {{$message}} </small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <!-- Âge de départ et unité -->
                                                        <div class="mx-auto col-md-6 col-12 row">
                                                            <div class="mx-auto col-md-6 col-12 mt-md-1 row">
                                                                <div class="text-center form-group">
                                                                    <label for="age_depart">Age de départ</label>
                                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                                    <div class="mx-auto input-group">
                                                                        <input type="number" class="touchspin-color form-control" value="{{ old('age_depart') == null ? 1 : old('age_depart') }}" name="age_depart" id="age_depart" data-bts-button-down-class="btn btn-dark" data-bts-button-up-class="btn btn-dark" required/>
                                                                        @error('age_depart')
                                                                        <small class="alert alert-danger">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="mx-auto col-sm-6 col-12">
                                                                <div class="text-center form-group">
                                                                    <label for="unite_age_depart">Unité de mesure</label>
                                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                                    <select class="form-control" id="unite_age_depart" name="unite_age_depart" required>
                                                                        <option value="jour" {{ old('unite_age_depart') == 'jour' ? 'selected' : '' }}>Jour</option>
                                                                        <option value="semaine" {{ old('unite_age_depart') == 'semaine' ? 'selected' : '' }}>Semaine</option>
                                                                        <option value="mois" {{ old('unite_age_depart') == 'mois' ? 'selected' : '' }}>Mois</option>
                                                                        <option value="annee" {{ old('unite_age_depart') == 'annee' ? 'selected' : '' }}>Année</option>
                                                                    </select>
                                                                    @error('unite_age_depart')
                                                                    <small class="alert alert-danger">{{ $message }}</small>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Nombre de sujets et prix d'acquisition -->
                                                        <div class="mx-auto col-md-6 col-12 row">
                                                            <div class="mx-auto col-md-6 col-12 mt-md-1 row">
                                                                <div class="text-center form-group">
                                                                    <label for="nbre_sujet">Nombre de sujets</label>
                                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                                    <div class="mx-auto input-group">
                                                                        <input type="number" class="touchspin-color form-control" id="nbre_sujet" value="{{ old('nbre_sujet') == null ? 1 : old('nbre_sujet') }}" data-bts-button-down-class="btn btn-dark"  name="nbre_sujet"data-bts-button-up-class="btn btn-dark" required/>
                                                                        @error('nbre_sujet')
                                                                        <small class="alert alert-danger">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mx-auto col-sm-6 col-12">
                                                                <div class="form-group">
                                                                    <label for="prix_acquisition">Prix d'acquisition</label>
                                                                    <div class="input-group input-group-merge @error('prix_acquisition') is-invalid @enderror">
                                                                        <input type="text" class="@error('prix_acquisition')  is-invalid @enderror form-control" placeholder="1,000" id="prix_acquisition" name="prix_acquisition2" onkeyup="format_montant('prix_acquisition')" value="{{old('prix_acquisition2')}}" required />
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">FCFA</span>
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" id="prix_acquisition2" name="prix_acquisition" value="{{old('prix_acquisition')}}" hidden />
                                                                </div>
                                                                @error('prix_acquisition')
                                                                    <small class="alert alert-danger"> {{$message}} </small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <!-- Boutons -->
                                            <div class="row col-12">
                                                <div class="mx-auto text-center d-none d-md-block col-lg-8">
                                                    <button type="reset" class="btn btn-danger col-4">Effacer</button>
                                                    <button type="submit" class="btn btn-success col-4">Enregistrer</button>
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
		document.querySelector('#BandeAjouter').classList.add('active');
	</script>
@endsection
