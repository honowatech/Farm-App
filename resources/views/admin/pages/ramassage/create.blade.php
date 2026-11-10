@extends('layouts.app')

@section('title')
{{'New Ramassage'}}
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
                        <h2 class="float-left mb-0 content-header-title">Nouveau Ramassage</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Ajouter un Ramassage
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
                                <b id="nbre_ramassage" class="text-center">
                                </b>
                                <div class="card-body" onkeyup="put_data()" onclick="put_data()">
                                    <form class="form" action="{{route('ramassage.store')}}" method="POST">
                                    	@csrf
                                        <div class="mx-auto row col-lg-10">
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="id_bande">Bandes</label>
                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" onchange="call_ajax2();call_ajax3()" id="id_bande" name="id_bande" required>
                                                            <option value="">
                                                                Choisir La bande
                                                            </option>
                                                            @foreach($bandes as $bande)
                                                                 @if($bande->statut_bande != 'terminee')
                                                                    <option class="product"
                                                                        value="{{$bande->id}}"
                                                                        {{$bande->id == old('id_bande') ? 'selected' : ''}}>
                                                                        {{ucfirst($bande->nom)}}
                                                                    </option>
                                                                @endif
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
                                                    <label for="id_produit">Produit</label>
                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" id="id_produit" name="id_produit" onchange="call_ajax();call_ajax3()" required>
                                                            <option value="">
                                                                Choisir Le produit
                                                            </option>
                                                            @foreach($produits as $produit)
                                                                @if($produit->derives->count() > 0)
                                                                    <option class="product"
                                                                        value="{{$produit->id}}"
                                                                        {{$produit->id == old('id_produit') ? 'selected' : ''}}>
                                                                        {{ucfirst($produit->nom)}}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        @error('id_produit')
                                                            <small class="alert alert-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-6 form-group">
                                                        <label for="date">Date</label>
                                                        <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                        <div class="form-group">
                                                            <input onchange="call_ajax3()" class="form-control flatpickr-human-friendly input" name="date" placeholder="January 01, 2024" id="date" value="{{old('date')}}" tabindex="0" type="text" readonly="readonly" required>
                                                            @error('date')
                                                                <small class="alert alert-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-6 form-group">
                                                        <label for="heure">Heure</label>
                                                        <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                        <div class="form-group">
                                                            <input type="text" id="heure" name="heure" class="text-left form-control flatpickr-time flatpickr-input" value="{{old('heure')}}" placeholder="HH:MM" readonly="readonly" style="height: inherit;" required>
                                                            @error('heure')
                                                                <small class="alert alert-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="id_prestataire">Agent</label>
                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <select class="select2 form-control" id="id_prestataire" name="id_prestataire" required>
                                                            <option value="">
                                                                Choisir L'Agent
                                                            </option>
                                                            @foreach($agents as $agent)
                                                                <option
                                                                    value="{{$agent->id}}"
                                                                    {{$agent->id == old('id_prestataire') ? 'selected' : ''}}>
                                                                    {{ucfirst($agent->nom)}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_prestatire')
                                                        <small class="alert alert-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="my-auto col-md-6 col-12" id="result_ajax">
                                            @if(isset($derives))
                                                @php
                                                    $row = 0;
                                                @endphp
                                                @foreach($derives as $derive)
                                                    @if($row != 0)
                                                        <hr>
                                                    @else
                                                        <div class="row form-group">
                                                            <div class="col-6 col-md-8">
                                                                <label for="derives">Dérivées</label>
                                                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                            </div>
                                                            <div class="col-md-4 col-6">
                                                                <label for="derives">Quantité</label>
                                                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                        <div class="row form-group">
                                                            <div class="col-6 col-md-8">
                                                                <label for="{{'derive_'.$derive->id}}">
                                                                    <span class="text-dark font-weight-bolder">{{ucfirst($derive->nom)}}</span>
                                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                                </label>
                                                            </div>
                                                            <div class="form-group col-md-4 col-6" style="margin-bottom: 0;">
                                                                <div class="mx-auto input-group">
                                                                    <input type="number" data-id="{{$derive->id}}" onclick="put_data()" onkeyup="put_data()" class="text-center qantite form-control form-control-sm" value="{{ old('derive_'.$derive->id) == null ? 0 : old('derive_'.$derive->id) }}" name="{{'derive_'.$derive->id}}" id="{{'derive_'.$derive->id}}" required/>
                                                                </div>
                                                            </div>
                                                            <div class="text-right w-100">
                                                                @error('derive_'.$derive->id)
                                                                    <small class="alert alert-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @php
                                                        $row ++;
                                                    @endphp
                                                @endforeach
                                                @error('full_error')
                                                    <small class="alert alert-danger">Au moins une valeur doit dépasser 0</small>
                                                @enderror
                                                <div class="row form-group">
                                                    <div class="form-group col-6">
                                                        <input type="hidden" id="ids" name="ids" class="form-control" readonly>
                                                    </div>
                                                    <div class="form-group col-6">
                                                            <input type="hidden" id="valeurs" name="valeurs" class="form-control" readonly>
                                                    </div>
                                                    @error('valeurs')
                                                        <small class="alert alert-danger">Remplissez tous les champs obligatoires</small>
                                                    @enderror
                                                </div>
                                            @endif
                                            </div>
                                            <div class="m-auto col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="commentaire">Commentaires / Observations</label>
                                                    <span class="cursor-pointer text-info" title="Optionnel">Optionnel</span>
                                                    <div class="form-group">
                                                            <textarea type="commentaire" autocomplete="" class="form-control @error('commentaire') is-invalid @enderror" id="commentaire" placeholder="Commentaires" name="commentaire"aria-describedby="basic-default-commentaire1" rows="4" style="height: 100%;">{{old('commentaire')}}</textarea>
                                                        @error('commentaire')
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
        function put_data(){
            var quantites = document.querySelectorAll('.qantite'),
                valeurs = [],
                count = 0,
                ids = [];
            //id_quartier_colis.value = option.dataset.id;
                quantites.forEach((qantite) => {
                    if(count == quantites.length - 1){
                        valeurs += qantite.value;
                        ids += qantite.dataset.id;
                    }else{
                        valeurs += qantite.value +'_';
                        ids += qantite.dataset.id +'_';
                    }
                    count ++ ;
                });
            if(quantites.length > 0){
                document.querySelector('#ids').value = ids;
                document.querySelector('#valeurs').value = valeurs;
            }
        }
        function call_ajax(){
            var valeur = document.querySelector('#id_produit').value,
                id_produit = valeur == '' ? 0 : valeur;
            response_ajax('result_ajax',[id_produit],'{{route('ajax_result_derive_ramassage')}}','GET');
        }
        function call_ajax2(){
            var valeur = document.querySelector('#id_bande').value,
                id_bande = valeur == '' ? 0 : valeur;
            response_ajax('id_produit',[id_bande],'{{route('ajax_result_produit')}}','GET',true);
        }
        function call_ajax3(){
            var valeur = document.querySelector('#id_bande').value,
                id_bande = valeur == '' ? 0 : valeur,
                valeur2 = document.querySelector('#id_produit').value,
                id_produit = valeur2 == '' ? 0 : valeur2;
                valeur3 = document.querySelector('#date').value,
                ladate = valeur3 == '' ? 0 : valeur3;
            response_ajax('nbre_ramassage',[id_bande,id_produit,ladate],'{{route('ajax_number_ramassage')}}','GET',true);
        }
        @if(!isset($derives))
            call_ajax();
            call_ajax2();
        @endif
        call_ajax3()
        put_data();
        window.addEventListener('load', function() {
            document.querySelector('#heure').value = "{{old('heure')}}";
        });
		document.querySelector('#RamassageAjouter').classList.add('active');
	</script>
@endsection
