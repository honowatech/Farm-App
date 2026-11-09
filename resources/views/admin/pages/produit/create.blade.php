@extends('admin/templates/template')

@section('title')
{{'New Product'}}
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
                        <h2 class="float-left mb-0 content-header-title">Nouveau Produit</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Ajouter un Produit
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
                                    <form class="form" action="{{route('produit.store')}}" method="POST">
                                    	@csrf
                                        <div class="mx-auto row col-lg-8">
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="type_animal">Type d'animal</label>
                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" id="type_animal" name="type_animal" required>
                                                            <option value="">
                                                                Choisir un Type d'animal
                                                            </option>
                                                            @foreach($types_animal as $type_animal)
                                                                <option
                                                                    value="{{$type_animal->id}}"
                                                                    {{$type_animal->id == old('type_animal') ? 'selected' : ''}}>
                                                                    {{strtoupper($type_animal->nom)}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('type_animal')
                                                        <small class="alert alert-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="id_produit">Type de Produit</label>
                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <select  class="select2 form-control" id="type" name="type_produit" required>
                                                            <option value="" class="" data-prix="0">
                                                                Choisir un type de produit
                                                            </option>
                                                             @foreach($types_produits as $type_produit)
                                                                <option class="product"
                                                                    value="{{$type_produit->id}}"
                                                                    {{$type_produit->id == old('type_produit') ? 'selected' : ''}}>
                                                                    {{($type_produit->nom)}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div id="error_client" class="d-none">
                                                            <small class="alert alert-danger">veuillez remplir ce champ</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                        <label for="nom">Nom</label>
                                                        <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-merge @error('nom') is-invalid @enderror">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i data-feather="at-sign"></i></span>
                                                            </div>
                                                            <input type="text" id="nom" class="form-control @error('nom') is-invalid @enderror" name="nom" placeholder="Nom " value="{{ old('nom') }}" / required>
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
                                                            <textarea type="description" autocomplete="" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="Description" name="description"aria-describedby="basic-default-description1" rows="3" required>{{old('description')}}</textarea>
                                                        @error('description')
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
		document.querySelector('#ProduitAjouter').classList.add('active');
	</script>
@endsection
