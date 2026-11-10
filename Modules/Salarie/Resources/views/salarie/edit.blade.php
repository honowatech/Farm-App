@extends('layouts.app')

@section('title')
{{'Editer '.$salarie->nom}}
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
                            <h2 class="float-left mb-0 content-header-title">Editer {{$salarie->nom}}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                                </li>
                                <li class="breadcrumb-item active">Editer {{$salarie->nom}}
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
                                    <form class="form" id="formulaire" action="{{route('salarie.update',$salarie->id)}}" method="POST" onsubmit="ajax();return false;">
                                        @csrf
                                        @method('PATCH')
                                        <div class="mx-auto row col-lg-8">
                                            <div class="mx-auto col-12 col-md-7">
                                                <div class="form-group">
                                                        <label for="nom">Nom te Prénoms</label>
                                                        <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-merge @error('nom') is-invalid @enderror">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i data-feather="at-sign"></i></span>
                                                            </div>
                                                            <input type="text" id="nom" class="form-control @error('nom') is-invalid @enderror " name="nom" placeholder="Nom et Prénoms" value="{{ old('nom') == null ? $salarie->nom : old('nom') }}" / required autofocus>
                                                        </div>
                                                        <small class="alert alert-danger" id="nom_error" hidden>Entrer le Nom et le Prénom</small>
                                                        @error('nom')
                                                        <small class="alert alert-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mx-auto col-12 col-md-5">
                                                <div class="form-group">
                                                        <label for="cni">Numéro de CNI</label>
                                                        <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-merge @error('cni') is-invalid @enderror">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i data-feather='credit-card'></i></span>
                                                            </div>
                                                            <input type="text" id="cni" class="form-control @error('cni') is-invalid @enderror " name="cni" placeholder="Numéro de CNI" value="{{ old('cni') == null ? $salarie->cni : old('cni') }}" / required autofocus>
                                                        </div>
                                                        <small class="alert alert-danger" id="cni_error" hidden>Entrer le numéro de CNI </small>
                                                        @error('cni')
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
                                                            <input type="telephone" id="telephone" class="form-control @error('telephone') is-invalid @enderror" placeholder="Numéro de téléphone" value="{{ old('telephone') == null ? $salarie->telephone : old('telephone') }}" onkeyup="phone('telephone');" required/>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i data-feather="phone"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <input type="text" hidden id="telephone2" name="telephone"/>
                                                        <small class="alert alert-danger" id="telephone_error" hidden>Entrer le Numéro de téléphone</small>
                                                        @error('telephone')
                                                                <small class="alert alert-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                        <label for="telephone_secondaire">Deuxième numéro de téléphone</label>
                                                        <span class="cursor-pointer text-info" title="Optionnel">Optionnel</span>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-merge  @error('telephone_secondaire') is-invalid @enderror">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="mr-1 flag-icon flag-icon-cm"></i>
                                                                    +237
                                                                </span>
                                                            </div>
                                                            <input type="telephone_secondaire" id="telephone_secondaire" class="optionnel form-control @error('telephone_secondaire') is-invalid @enderror" placeholder="Numéro de téléphone sécondaire" value="{{ old('telephone_secondaire') == null ? $salarie->telephone_secondaire : old('telephone_secondaire') }}" onkeyup="phone('telephone_secondaire');"/>
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
                                                        <textarea type="text" id="adresse" class="form-control @error('adresse') is-invalid @enderror" name="adresse" rows="3" placeholder="Adresse détaillée du Salarié"  required />{{ old('adresse') == null ? $salarie->adresse : old('adresse') }}</textarea>
                                                        <small class="alert alert-danger" id="adresse_error" hidden>Entrer l' Adresse</small>
                                                        @error('adresse')
                                                            <small class="alert alert-danger">{{ $message }}</small>
                                                        @enderror('adresse')
                                                    </div>
                                                </div>
                                            </div>
                                            {{--
                                                <div class="mx-auto col-sm-6">
                                                    <div class="text-center form-group">
                                                            <label for="accumulateur">Fréquence</label>
                                                            <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                        <div class="form-group">
                                                            <div class="mx-auto input-group w-50 bootstrap-touchspin">
                                                                <input type="text" id="accumulateur" name="accumulateur" class="border touchspin-color form-control" value="{{ old('accumulateur')  == null ? $salarie->accumulateur : old('accumulateur') }}" min="1"data-bts-button-down-class="btn btn-dark" data-bts-button-up-class="btn btn-dark" required>
                                                            </div>
                                                            <small class="alert alert-danger" id="accumulateur_error" hidden>Entrer une Fréquence</small>
                                                            @error('accumulateur')
                                                            <small class="alert alert-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mx-auto col-sm-6">
                                                    <div class="form-group">
                                                        <label for="type_periode">Type de Période</label>
                                                        <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                        <div class="form-group">
                                                            <select class="select2 form-control" id="type_periode" name="type_periode" required>
                                                                <option value="">
                                                                    Choisir un Type de Période
                                                                </option>
                                                                @foreach($types_periode as $key => $type_periode)
                                                                    <option class="type_periode"
                                                                        value="{{$key}}"
                                                                        {{$key == old('type_periode') ? 'selected' :  '' }}{{ $key == $salarie->type_periode ? 'selected' : ''}}>
                                                                        {{$type_periode}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <small class="alert alert-danger" id="type_periode_error" hidden>Sélectionner la période</small>
                                                            @error('type_periode')
                                                            <small class="alert alert-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            --}}
                                            <div class="mx-auto col-sm-6">
                                                <div class="form-group">
                                                    <label for="salaire">Salaire</label>
                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <div class="input-group input-group-merge @error('salaire') is-invalid @enderror">
                                                            <input type="text" id="salaire" class="form-control @error('salaire') is-invalid @enderror " name="salaire2" placeholder="10,000" onkeyup="format_montant('salaire')" value="{{ old('salaire2') == null ? $salarie->salaire : old('salaire')  }}" / required autofocus>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">FCFA</span>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="salaire2" name="salaire" value="{{ old('salaire') }}" autofocus />
                                                        <small class="alert alert-danger" id="salaire_error" hidden>Entrer un Salaire</small>
                                                        @error('salaire')
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
                                                <button type="button" onclick="ajax()" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success mt-md-2">
                                                    <i class="mr-1" data-feather='download'></i> Enregistrer
                                                </button>
                                            </div>
                                            <div class="mx-auto text-center d-block d-md-none col-bg-5 col-lg-8 col-md-12 col-12">
                                                <button type="button" onclick="ajax()" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success mt-md-2 mr-md-2">
                                                    <i class="mr-1" data-feather='download'></i> Enregistrer
                                                </button>
                                                <button type="reset" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-danger mt-md-2">
                                                    <i class="mr-1" data-feather='x'></i> Effacer
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="modal fade" id="recap" tabindex="-1" aria-labelledby="descriptionTitle" aria-modal="true" role="dialog">
                                                <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document" id="modifiated">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="descriptionTitle">Boite de Confirmation</h5>
                                                            <button type="button" class="m-0 bg-danger close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true" class="text-white">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="py-1 mx-auto col-11" id="recapitulatif">
                                                                    Le Récap s'affichera ici !!!
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-center justify-content-md-apt-1">
                                                            <div class="mx-auto text-center d-none d-lg-block col-12">
                                                                 <button class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-8 btn-danger mt-lg-2 mr-lg-4" data-dismiss="modal" aria-label="Close">
                                                                    <i class="mr-1" data-feather='x'></i> Annuler
                                                               </button>
                                                                <button type="button" onclick="valider('formulaire')" data-toggle="modal" data-target="#setting" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-8 btn-success mt-lg-2">
                                                                    <i class="mr-1" data-feather='download'></i> Confirmer
                                                                </button>
                                                            </div>
                                                            <div class="mx-auto text-center d-block d-lg-none col-12">
                                                                <button type="button" onclick="valider('formulaire')" data-toggle="modal" data-target="#setting" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-8 btn-success mt-lg-2 mr-lg-2mx-auto">
                                                                    <i class="mr-1" data-feather='download'></i> Confirmer
                                                                </button>
                                                                <button class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-8 btn-danger mt-lg-2mx-auto" data-dismiss="modal" aria-label="Close">
                                                                    <i class="mr-1" data-feather='x'></i> Anuller
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
        function ajax(){
            recap = recapituler('modal','recap');
            if(!recap){
                response_ajax('recapitulatif',datas,'{{route('ajax_recap_salarie_edit')}}','POST')
            }
        }
        document.querySelector('#SalarieAjouter').classList.add('active');
        phone('telephone_secondaire');
        phone('telephone');
        format_montant('salaire')
    </script>
@endsection
