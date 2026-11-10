@extends('layouts.app')

@section('title')
{{'Détails sur '}} {{name($salarie->nom)}}
@endsection
@section('contenu')

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
            <div class="content-wrapper">
    	       <div class="content-header-left col-md-12 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Infos sur {{name($salarie->nom)}}</h2>
                            <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('salarie.index')}}">
                                    Liste des salariés
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Infos sur {{name($salarie->nom)}}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="app-user-view">
                    <!-- User Card & Plan Starts -->
                    <div class="row">
                        <!-- User Card starts-->
                        <div class="col-md-12">
                            <div class="card user-card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-bg-6 col-lg-6 d-flex flex-column justify-content-between border-container-lg">
                                            <div class="user-avatar-section">
                                                <div class="d-flex justify-content-start">
                                                    <img class="img-fluid rounded" src="{{asset('app-assets/images/avatars/salarie.png')}}" height="90" width="90" alt="User avatar" />
                                                    <div class="d-flex flex-column ml-1">
                                                        <div class="user-info mb-1">
                                                            <h4 class="mb-0 ml-1">{{name($salarie->nom)}}</h4>
                                                            <span class="card-text ml-1" title="{{$salarie->adresse}}">{{cutText($salarie->adresse,100)}}</span>
                                                        </div>
                                                        <div class="d-flex flex-wrap">
                                                            <a href="{{route('salarie.edit',$salarie->id)}}" class="btn btn-secondary btn-gradient-secondary btn-sm ml-1">
                                                                Modifier
                                                            </a>
                                                            <button class="btn ml-1 btn-sm @if($salarie->statut == 1) btn-gradient-danger btn-danger  @else btn-gradient-success btn-success @endif " onclick="actions('form_active', @if($salarie->statut == 1) 'Désactiver' @else 'Activer' @endif ,'{{$salarie->nom}}')">
                                                                    @if($salarie->statut == 1)
                                                                        Désactiver
                                                                    @else
                                                                        Activer
                                                                    @endif
                                                                <form id="form_active" action="{{route('salarie.destroy',$salarie->id)}}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </button>
                                                            <button class="btn ml-1 btn-sm btn-danger btn-gradient-danger" onclick="actions('form_delete_{{$salarie->id}}', 'Supprimer' ,'{{$salarie->nom}}')">
                                                                    <i data-feather='trash'></i> Supprimer
                                                                <form id="form_delete_{{$salarie->id}}" action="{{route('salarie.destroy',$salarie->id)}}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-bg-6 col-lg-6 mt-2 p-0 mt-xl-0">
                                            <div class="user-info-wrapper">
                                                <div class="d-flex flex-wrap d-sm-block d-sm-flex  d-none">
                                                    <div class="user-info-title">
                                                        <i data-feather="user" class="mr-1"></i>
                                                        <span class="card-text user-info-title font-weight-bold mb-0">Noms</span>
                                                    </div>
                                                    <p class="card-text mb-0">
                                                        <b>
                                                            {{cutText(name($salarie->nom),18)}}
                                                        </b>
                                                    </p>
                                                </div>
                                                <div class="d-flex flex-wrap my-50">
                                                    <div class="user-info-title">
                                                        <i data-feather="credit-card" class="mr-1"></i>
                                                        <span class="card-text user-info-title font-weight-bold mb-0">CNI</span>
                                                    </div>
                                                    <p class="card-text mb-0">
                                                        <b>
                                                            {{$salarie->cni}}
                                                        </b>
                                                    </p>
                                                </div>
                                                <div class="d-flex flex-wrap my-50">
                                                    <div class="user-info-title">
                                                        <i data-feather="phone" class="mr-1"></i>
                                                        <span class="card-text user-info-title font-weight-bold mb-0">Téléphone</span>
                                                    </div>
                                                    <p class="card-text mb-0">
                                                        <span>
                                                        	{{phone2($salarie->telephone)}}
                                                            @if($salarie->telephone_secondaire != null)
                                                                <br class="d-block d-lg-none">
                                                                <span class="d-none d-lg-inline-block">/</span> {{phone2($salarie->telephone_secondaire)}}
                                                            @endif
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="d-flex flex-wrap my-50">
                                                    <div class="user-info-title">
                                                         @if($salarie->statut == 0)
                                                            <i data-feather="x-circle" class="mr-1"></i>
                                                        @else
                                                            <i data-feather="check-circle" class="mr-1"></i>
                                                        @endif
                                                        <span class="card-text user-info-title font-weight-bold mb-0">Status</span>
                                                    </div>
                                                    <span class="badge badge-pill badge-glow @if($salarie->statut == 0)badge-danger @else badge-success @endif">
	                                            		@if($salarie->statut == 0)
			                                             	Désactivé
			                                            @else
			                                            	Actif
			                                            @endif
                                                	</span>
                                                </div>
                                                <div class="d-flex flex-wrap my-50">
                                                    <div class="user-info-title">
                                                        <i data-feather="dollar-sign" class="mr-1"></i>
                                                        <span class="card-text user-info-title font-weight-bold mb-0">Salaire</span>
                                                    </div>
                                                    <p class="card-text mb-0">
                                                        <span>
                                                            {{$salarie->salaire}} FCFA
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="d-flex flex-wrap my-50">
                                                    <div class="user-info-title">
                                                        <i data-feather="trending-up" class="mr-1"></i>
                                                        <span class="card-text user-info-title font-weight-bold mb-0">Fréquence</span>
                                                    </div>
                                                    <p class="card-text mb-0">
                                                        <span>
                                                            Chaque {{$salarie->accumulateur}} {{$types_periode[$salarie->type_periode]}}
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="d-flex flex-wrap my-50">
                                                    <div class="user-info-title">
                                                        <i data-feather="calendar" class="mr-1"></i>
                                                        <span class="card-text user-info-title font-weight-bold mb-0">Prochaine paie</span>
                                                    </div>
                                                    <p class="card-text mb-0">
                                                        @if($salarie->statut == 0)
                                                            <b class="text-danger">
                                                                Indéterminée...
                                                            </b>
                                                        @else
                                                            @if(strtotime(now()) >= strtotime($salarie->date_dernier_paiement))
                                                                <span class="badge badge-light-danger" title="En retart sur les paiements">
                                                                    {{Ladate($salarie->date_dernier_paiement)}} à {{Heure($salarie->date_dernier_paiement)}}
                                                                </span>
                                                            @else
                                                                <span class="badge badge-light-success" title="A jour sur les paiements">
                                                                    {{Ladate($salarie->date_dernier_paiement)}} à {{Heure($salarie->date_dernier_paiement)}}
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="d-flex flex-wrap my-50">
                                                    <div class="user-info-title">
                                                        <i data-feather="calendar" class="mr-1"></i>
                                                        <span class="card-text user-info-title font-weight-bold mb-0">Date de debut</span>
                                                    </div>
                                                    <p class="card-text mb-0">
                                                        <span class="badge badge-light-dark" title="En retart sur les paiements">
                                                            {{Ladate($salarie->date_debut_contrat)}} à {{Heure($salarie->date_debut_contrat)}}
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- zone Timeline & Permissions Starts -->
                    <div class="row">
                        <!-- zone Permissions Starts -->
                        <div class="col-md-12">
                            <!-- zone Permissions -->
                            <div class="card">
                                <div class="card-header mx-1 row" style="gap: 2rem;">
                                    <h4 class=" card-title">Tableau des paiements</h4>
                                    <div class="">
                                        @if($salarie->statut == 1)
                                            <button type="button" class="btn mr-md-1 btn-success" onclick="calculate('nbre_fois','place_nbre_fois',{{$salarie->salaire}},'place_salaire')" data-toggle="modal" data-target="#pay_salaire_complet">
                                                Payer en période
                                            </button>
                                            @if(strtotime(now()) >= strtotime($salarie->date_debut_contrat))
                                                @if($pas_avance['statut'] == true)
                                                    <button type="button" class="btn btn-gradient-warning btn-warning" style="background: #ff0000 !important" data-toggle="modal" data-target="#pay_salaire_avance" onclick="put_focus('montant')">
                                                        Faire une avance
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-gradient-warning btn-warning" style="background: #ff0000 !important" data-toggle="modal" data-target="#pay_salaire_avance">
                                                        Finir l'avance en cours
                                                    </button>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                <form action="{{ route('salarie.show',$salarie->id) }}" class="col-lg-3 mt-1" id="search" method="GET">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="custom-control custom-radio mx-auto">
                                                    <input type="radio" name="recherche" onclick="document.querySelector('#search').submit()" class="custom-control-input" id="tout" value="" @if(session()->get('search') == '') checked @endif>
                                                    <label class="custom-control-label" for="tout">Tout</label>
                                        </div>
                                                @foreach($types_Paiement as $value => $type_Paiement)
                                        <div class="custom-control custom-radio mx-auto">
                                                    <input type="radio" name="recherche" onclick="document.querySelector('#search').submit()" class="custom-control-input" id="{{$value}}" value="{{$value}}" @if(session()->get('search') == $value) checked @endif>
                                                    <label class="custom-control-label" for="{{$value}}">{{$type_Paiement}}</label>
                                        </div>
                                               @endforeach
                                    </div>
                                    <input type="hidden" name="page" id="page" value="">
                                </form>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-borderless">
                                            <thead class="thead-light">
                                                <tr class="text-center">
                                                    <th>N°</th>
                                                    <th>Type</th>
                                                    <th>Montant</th>
                                                    <th>Date debut</th>
                                                    <th>Date fin</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                    @php
                                                        $row = 10*$paiements->currentPage() -9 ;
                                                    @endphp
                                                    @foreach($paiements as $paiement)
                                                        <tr class="text-center">
                                                            <td>
                                                                {{$row}}
                                                            </td>
                                                            <td>
                                                                    @if($paiement->type == 'paiement_complet')
                                                                <div class="badge badge-glow badge-success">
                                                                    @else
                                                                <div class="badge badge-glow badge-warning" style="background: #ff0000 !important">
                                                                    @endif
                                                                    {{$types_Paiement[$paiement->type]}}
                                                                </div>
                                                            </td>
                                                            <td class="text-truncate ">
                                                                <b>{{$paiement->montant}} FCFA</b>
                                                            </td>
                                                            <td>
                                                                <div class="badge badge-glow badge-dark">
                                                                    Le {{Ladate($paiement->date_debut)}} à {{Heure($paiement->date_debut)}}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="badge badge-glow badge-dark">
                                                                    Le {{Ladate($paiement->date_fin)}} à {{Heure($paiement->date_fin)}}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                                                        <i data-feather="more-vertical"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item" onclick="response_ajax('info_description',['{{$paiement->id}}'],'{{route('paiement_salaire.show',$paiement->id)}}','GET')" data-toggle="modal" data-target="#description">
                                                                            <i data-feather='eye'></i>
                                                                            <span class="ml-1">Informations</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                    @php
                                                        $row++
                                                    @endphp
                                                    @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                    @if ($paiements->hasPages())
                        <nav class="d-flex col-12 mt-1 justify-items-center justify-content-center">
                            <div class="row mx-auto justify-content-center flex-fill d-md-none">
                                <div class="mr-1 d-md-none">
                                    <p class="small text-muted">
                                        {!! __('De') !!}
                                        <span class="fw-semibold">{{ $paiements->firstItem() }}</span>
                                        {!! __('à') !!}
                                        <span class="fw-semibold">{{ $paiements->lastItem() }}</span>
                                        {!! __('sur') !!}
                                        <span class="fw-semibold">{{ $paiements->total() }}</span>
                                        {!! __('resultats') !!}
                                    </p>
                                </div>
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($paiements->onFirstPage())
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link">Précédent</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $paiements->currentPage()-1 }})" rel="prev">Suivant</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($paiements->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $paiements->currentPage()+1 }})" rel="next">@lang('pagination.next')</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link">@lang('pagination.next')</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>

                            <div class="d-none flex-md-fill d-md-flex align-items-md-center justify-content-md-center">
                                <div class="mr-1">
                                    <p class="small text-muted">
                                        {!! __('vu de ') !!}
                                        <span class="fw-semibold">{{ $paiements->firstItem() }}</span>
                                        {!! __('à') !!}
                                        <span class="fw-semibold">{{ $paiements->lastItem() }}</span>
                                        {!! __('sur') !!}
                                        <span class="fw-semibold">{{ $paiements->total() }}</span>
                                        {!! __('resultats') !!}
                                    </p>
                                </div>

                                <div>
                                    <ul class="pagination">
                                        {{-- Previous Page Link --}}
                                        @if ($paiements->onFirstPage())
                                            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                                <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre({{ $paiements->currentPage()-1 }})" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($paiements->links()->elements as $element)
                                            {{-- "Three Dots" Separator --}}
                                            @if (is_string($element))
                                                <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                                            @endif

                                            {{-- Array Of Links --}}
                                            @if (is_array($element))
                                                @foreach ($element as $page => $url)
                                                    @if ($page == $paiements->currentPage())
                                                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                                    @else
                                                        <li class="page-item"><a class="page-link" onclick="mettre({{ $page }})">{{ $page }}</a></li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($paiements->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre({{ $paiements->currentPage()-1 }})" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                                                <span class="page-link" aria-hidden="true">&rsaquo;</span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </nav>
                    @endif
                                </div>
                             </div>
                            <!-- fin modale de sélection des viles -->
                        </div>
                    </div>
                        <!-- zone Permissions Ends -->
                </div>
                    <!-- zone Timeline & Permissions Ends -->
            </section>
        </div>
    </div>
</div>
    <!-- END: Content-->


<div class="modal fade modal-danger text-left" id="pay_salaire_complet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel120" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-secondary" id="myModalLabel120">Payer le salaire de {{name($salarie->nom)}}</h4>
                <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="pt-1">
                    <h4>Entrer le nombre de période à payer</h4>
                    <form action="{{route('paiement_salaire.store')}}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="paiement_complet">
                        <input type="hidden" name="id_salarie" value="{{$salarie->id}}">
                        <div class="col-12 my-2">
                            <div class="form-group text-center">
                                <label for="nbre_fois">Nombre de fois</label>
                                <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                                <div class="form-group">
                                    <div class="input-group w-50 bootstrap-touchspin mx-auto">
                                        <input type="number" id="nbre_fois" name="nbre_fois" onchange="calculate('nbre_fois','place_nbre_fois',{{$salarie->salaire}},'place_salaire')" oninput="calculate('nbre_fois','place_nbre_fois',{{$salarie->salaire}},'place_salaire')" class="border touchspin-color form-control" value="{{ old('nbre_fois','') == null ? 1 : old('nbre_fois') }}" min="1" data-bts-button-down-class="btn btn-dark" data-bts-button-up-class="btn btn-dark" required>
                                    </div>
                                    @error('nbre_fois')
                                        <small class="alert alert-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <span class="mt-1">
                                Vous allez payer le salaire de <b>{{name($salarie->nom)}}</b> cela va couter <b id="place_salaire"></b> pour  <b id="place_nbre_fois"></b> <b><i data-feather='x'></i></b> (<b> {{$salarie->accumulateur}} {{$types_periode[$salarie->type_periode]}} </b>)...
                            </span>
                            <div class="mt-2 row">
                                <button type="submit" class="col-md-4 col-5 mx-auto btn btn-success waves-effect waves-float waves-light">Payer</button>
                                <button type="reset" class="col-md-4 col-5 mx-auto btn btn-danger waves-effect" data-dismiss="modal" aria-label="Close">Annuler</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-danger text-left" id="pay_salaire_avance" tabindex="-1" role="dialog" aria-labelledby="myModalLabel120" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-secondary" id="myModalLabel120">
                    @if($pas_avance['statut'])
                        Avancer le salaire de {{name($salarie->nom)}}
                    @else
                        Finir l'avance du salaire en cours
                    @endif
                </h4>
                <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="pt-1">
                    <h4>
                        @if($pas_avance['statut'])
                        Entrer le montant d'argent à payer
                        @endif
                    </h4>
                    <form action="{{route('paiement_salaire.store')}}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="avance">
                        <input type="hidden" name="id_salarie" value="{{$salarie->id}}">
                        @if(!$pas_avance['statut'])
                            <input type="hidden" name="montant" value="{{$pas_avance['montant']}}">
                        @endif
                        <div class="col-12 my-2">
                            <div class="form-group text-center" >
                            @if($pas_avance['statut'])
                                <label for="montant">Montant</label>
                                <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                                <div class="form-group col-10 mx-auto">
                                    <div class="input-group input-group-merge mx-auto">
                                        <input id="montant" name="montant2" class="form-control" placeholder="Montant" value="{{ old('montant','') == null ? 500 : old('montant') }}" onkeyup="format_montant('montant');put_error('montant2',500,{{$salarie->salaire}})" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">FCFA</span>
                                        </div>
                                    </div>
                                    <input type="number" class="hidden" id="montant2"  name="montant" min="500" max="{{$salarie->salaire}}" required>
                                        <small class="alert alert-danger" id="montant2_error"></small>
                                    @error('montant')
                                        <small class="alert alert-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            @else
                            <span>En appuyant sur <b>PAYER</b> vous comblerez l'avance en cours ... <br>
                                Celà vous coutera <b> {{$pas_avance['montant']}} </b> FCFA
                            </span>
                            @endif
                            </div>
                            @if($pas_avance['statut'])
                            <span class="mt-2">
                                <u>NB</u>: L'avance ne doit pas dépasser <b> {{$salarie->salaire}} </b> FCFA...
                            </span>
                            @endif
                            <div class="mt-2 row">
                                <button type="submit" class="col-md-4 col-5 mx-auto btn btn-success waves-effect waves-float waves-light">Payer</button>
                                <button type="reset" class="col-md-4 col-5 mx-auto btn btn-danger waves-effect" data-dismiss="modal" aria-label="Close">Annuler</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="description" tabindex="-1" aria-labelledby="descriptionTitle" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionTitle"> Description </h5>
                <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-10 mx-auto py-2" id="info_description">
                        Infos de chaque paiement ici !
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center justify-content-md-apt-1">
                <button data-dismiss="modal" class="btn px-2 btn-success btn-gradient-success mx-auto">
                    <i data-feather="check"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
	<script type="text/javascript">
            function mettre(number_page){
               document.querySelector('#page').value = number_page;
               document.querySelector('#search').submit()
            }
            function put_focus(id){
                var input = document.querySelector('#'+id);
                setTimeout(function(){
                    input.focus();
                },500)
            }
            function put_error(id,min,max){
                var input = document.querySelector('#'+id),
                    error = document.querySelector('#'+id+'_error');

                if (input.value < min) {
                    error.innerHTML = 'La valeur minimale est '+min;
                    return  false;
                }else if(input.value > max){
                    error.innerHTML = 'La valeur maximale est '+max;
                    return  false;
                }else{
                    error.innerHTML = '';
                    return  true;
                }
            }
            document.querySelector('#SalarieListe').classList.add('active');
            function calculate(id_input_mois,id_place_mois,salaire,id_place_salaire){
                var input_mois = document.querySelector('#'+id_input_mois),
                    place_mois = document.querySelector('#'+id_place_mois),
                    place_salaire = document.querySelector('#'+id_place_salaire);

                    place_mois.innerHTML = input_mois.value;
                    place_salaire.innerHTML = salaire*input_mois.value+' FCFA';
            }
            @error('nbre_fois')
                $('#pay_salaire_complet').modal();
            @enderror
            @error('montant')
                $('#pay_salaire_avance').modal();
            @enderror
            format_montant('montant')
	</script>
@endsection
