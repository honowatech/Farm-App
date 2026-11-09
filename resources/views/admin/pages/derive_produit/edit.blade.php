@extends('admin/templates/template')

@section('title')
{{'Editer '}} {{$derive_produit->nom}}
@endsection
@if(auth()->user()->type_user->libelle === 'admin')
    @include('admin/menu/menu')
@elseif(auth()->user()->type_user->libelle === 'agent')
    @include('agent/menu/menu')
@endif
@section('contenu')

<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
            <div class="content-header row">
    	 <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Editer {{$derive_produit->nom}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('produit.index')}}">Liste des produits</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('produit.show',$derive_produit->produit->id)}}">{{ucfirst($derive_produit->produit->nom)}}</a>
                            </li>
                            <li class="breadcrumb-item active">Editer {{$derive_produit->nom}}
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
                                    <form class="form" action="{{route('derive_produit.update',$derive_produit->id)}}" method="POST">
                                    	@csrf
                                        @method('PATCH')
                                        <div class="row col-12 col-lg-10 mx-auto">
                                        	<div class="col-md-6 col-12 mx-auto">
                                                <div class="form-group">
                                                        <label for="nom">Nom</label>
                                                        <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-merge @error('nom') is-invalid @enderror">
                                                            <div class="input-group-prepend ">
                                                                <span class="input-group-text">
                                                                    {{$derive_produit->produit->nom}}_
                                                                </span>
                                                            </div>
                                                            <input type="text" id="nom" class=" border-left pl-1 form-control @error('nom') is-invalid @enderror" name="nom" placeholder="Nom" value="{{ old('nom') == null ? $derive_produit->nom : old('nom') }}" / required>
                                                        </div>
                                                        @error('nom')
                                                        <small class="alert alert-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12 mx-auto">
                                                <div class="form-group">
                                                    <label for="prix_unitaire">Prix unitaire</label>
                                                    <div class="input-group input-group-merge @error('prix_unitaire') is-invalid @enderror">
                                                        <input type="text" class="@error('prix_unitaire')  is-invalid @enderror form-control" placeholder="1,000" id="prix_unitaire" name="prix_unitaire2" onkeyup="format_montant('prix_unitaire')" value="{{ old('prix_unitaire') == null ? $derive_produit->prix_unitaire : old('prix_unitaire') }}" required />
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">FCFA</span>
                                                        </div>
                                                        @error('prix_unitaire') 
                                                            <small class="alert alert-danger"> {{$message}} </small>
                                                        @enderror
                                                        </div>
                                                    <input type="text" id="prix_unitaire2" name="prix_unitaire" value="{{old('prix_unitaire')}}" hidden />
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12 mx-auto">
                                                <div class="form-group text-center">  
                                                    <label for="min">Minimum</label>
                                                    <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                                                    <div class="input-group mx-auto">
                                                        <input type="number" class="touchspin-color form-control" value="{{ old('min') == null ? $derive_produit->min : old('min') }}" name="min" id="min" data-bts-button-down-class="btn btn-dark" data-bts-button-up-class="btn btn-dark" required/>
                                                    </div>
                                                @error('min')
                                                    <small class="alert alert-danger mx-auto">{{ $message }}</small>
                                                @enderror                                                            
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12 mx-auto">
                                                <div class="form-group text-center">  
                                                    <label for="max">Maximum</label>
                                                    <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                                                    <div class="input-group mx-auto">
                                                        <input type="number" class="touchspin-color form-control" value="{{ old('max') == null ? $derive_produit->max : old('max') }}" name="max" id="max" data-bts-button-down-class="btn btn-dark" data-bts-button-up-class="btn btn-dark" required/>
                                                    </div>
                                                @error('max')
                                                    <small class="alert alert-danger mx-auto">{{ $message }}</small>
                                                @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12 mx-auto">
                                                <div class="form-group">
                                                    <label for="unite"> Choisir une unité</label>
                                                    <select class="select2 form-control" id="unite" name="unite" required>
                                                    @foreach($unites as $unite)
                                                        <option value="{{$unite->id}}" {{$unite->id == old('unite') ? 'selected' : ''}}*

                                                            @if(old('unite')==null)
                                                                @if($unite->id == $derive_produit->unite->id)
                                                                    selected
                                                                @endif
                                                            @else 
                                                                @if($unite->id == old('unite'))
                                                                    selected
                                                                @endif
                                                            @endif >
                                                            {{$unite->abrege}}___{{$unite->libelle}}
                                                        </option>
                                                    @endforeach
                                                    </select>
                                                    @error('unite')
                                                    <small class="alert alert-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                           </div>
                                        </div>
                                        <div class="row col-12 mx-auto">
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
@if(auth()->user()->type_user->libelle === 'admin')
    @include('admin/menu/menu')
@elseif(auth()->user()->type_user->libelle === 'agent')
    @include('agent/menu/menu')
@endif

@section('javascript')
	<script type="text/javascript">
        document.querySelector('#ProduitListe').classList.add('active');
        format_montant('prix_unitaire');
	</script>
@endsection
@if(auth()->user()->type_user->libelle === 'admin')
    @include('admin/menu/menu')
@elseif(auth()->user()->type_user->libelle === 'agent')
    @include('agent/menu/menu')
@endif
