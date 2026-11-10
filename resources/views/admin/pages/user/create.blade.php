@extends('layouts.app')

@section('title')
{{'New User'}}
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
                        <h2 class="float-left mb-0 content-header-title">Nouvel Utilisateur</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Ajouter un Utilisateur
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
                                    <form class="form" action="{{route('user.store')}}" method="POST">
                                    	@csrf
                                        <div class="mx-auto row col-lg-10">
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="type_user">Type d'Utilisateur</label>
                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" id="type_user" name="type_user" required>
                                                            <option value="">
                                                                Choisir un type
                                                            </option>
                                                            @foreach($typesUser as $typeUser)
                                                                @if(strtoupper($typeUser->libelle) != strtoupper('client') && strtoupper($typeUser->libelle) != strtoupper('clients'))
                                                                <option
                                                                    value="{{$typeUser->id}}"
                                                                    {{$typeUser->id == old('type_user') ? 'selected' : ''}}>
                                                                    {{strtoupper($typeUser->libelle)}}
                                                                </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        	<div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                        <label for="noms">Noms et Prénoms</label>
                                                        <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-merge @error('noms') is-invalid @enderror">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i data-feather="user"></i></span>
                                                            </div>
                                                            <input type="text" id="noms" class="form-control @error('noms') is-invalid @enderror" name="noms" placeholder="Noms et Prénoms" value="{{ old('noms') }}" / required>
                                                        </div>
                                                        @error('noms')
                                                        <small class="alert alert-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                        <label for="email-icon">Email</label>
                                                        <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-merge  @error('email') is-invalid @enderror">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i data-feather="mail"></i></span>
                                                            </div>
                                                            <input type="email" autocomplete="" id="email-icon" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{ old('email') }}"  required/>
                                                        </div>
                                                        @error('email')
                                                            	<small class="alert alert-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="password">Mot de Passe</label>
                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
						                                <div class="input-group input-group-merge form-password-toggle @error('password') is-invalid @enderror">
						                                	<div class="input-group-prepend">
						                                		<span class="cursor-pointer input-group-text"><i data-feather="eye"></i></span>
	                                                        </div>
						                                    <input type="password" autocomplete="" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Mot de Passe" value="" name="password"aria-describedby="basic-default-password1"  required/>
						                                </div>
						                                @error('password')
						                                	<small class="alert alert-danger"> {{$message}} </small>
						                                @enderror
						                            </div>
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
		document.querySelector('#UserAjouter').classList.add('active');
	</script>
@endsection
