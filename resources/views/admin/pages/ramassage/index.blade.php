@extends('admin/templates/template')

@section('title')
{{'Liste des Ramassages'}}
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
                        <h2 class="float-left mb-0 content-header-title">Liste des Ramassages</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Liste des Ramassages
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pr-1 pl-1 card">
            <div class="content-body">
                <div class="row justify-content-between bg-light-secondary" >
                    <a class="m-1 btn btn-secondary btn-gradient-secondary" href="{{route('ramassage.create')}}">
                        <i data-feather='plus' style="width: 20px;height: 20px;"></i>
                    </a>
                        <form class="mt-1 col-12" id="search" method="GET">
                            @csrf
                            <div class="mx-auto row">
                                <!-- type -->
                                <div class="mx-auto form-group col-12 col-md-3">
                                    <label>{{ __('message.Bande') }}</label>
                                    <select id="bande" class="select2 type form-control" name="id_bande" required>
                                        <option selected value="">{{ __('message.Choix de la bande') }}</option>
                                        @foreach($bandes as $bande)
                                            <option value="{{$bande->id}}">{{ $bande->nom}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Date de debut -->
                                <div class="mx-auto form-group col-12 col-md-3">
                                    <label>{{ __('depenses::translated.Date de début') }}</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i data-feather='search'></i></span>
                                        </div>
                                        <input type="text" name="date_debut" class="bg-white form-control date_debut flatpickr-date-time flatpickr-input" placeholder="{{ __('depenses::translated.Date de début') }}" id="date_debut">
                                    </div>
                                </div>

                                <!-- Date de fin -->
                                <div class="mx-auto form-group col-12 col-md-3">
                                    <label>{{ __('depenses::translated.Date de fin') }}</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i data-feather='search'></i></span>
                                        </div>
                                        <input type="text" name="date_fin" class="bg-white form-control date_fin flatpickr-date-time flatpickr-input" placeholder="{{ __('depenses::translated.Date de fin') }}" id="date_fin">
                                    </div>
                                </div>
                                {{--
                                    user
                                    <div class="form-group col-12 col-md-2">
                                        <label>{{ __('depenses::translated.utilisateur') }}</label>
                                        <select id="user" class="select2 user form-control @error('num_agent') is-invalid @enderror" name="id_user" required>
                                            <option selected value="">{{ __('depenses::translated.Choix de l\'utilisateur') }}</option>
                                            @foreach($users as $user)
                                                <option class="user" value="{{ $user->id }}">{{ $user->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div> <!-- Recherche -->
                                    <div class="form-group col-12 col-md-2">
                                        <label>{{ __('depenses::translated.Recherche') }}</label>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i data-feather='search'></i></span>
                                            </div>
                                            <input type="search" class="form-control" name="recherche" value="{{ session()->get('search') }}" placeholder="{{ __('depenses::translated.Rechercher une dépense') }}">
                                        </div>
                                    </div>
                                --}}

                            </div>
                            <input type="hidden" name="page" id="page" value="">
                        </form>
                    </div>
                </div>
                <!-- Table without card start -->
                <div class="row" id="table-without-card">
                        <div class="table-responsive" id="results">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th>N°</th>
                                        <th>Bande</th>
                                        <th>Produit</th>
                                        <th>Type d'animal</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@php
                                    	$count = 20*$ramassages->currentPage() -19 ;
                                	@endphp
                                	@foreach($ramassages as $ramassage)
	                                    <tr class="text-center">
	                                        <td>
	                                        	{{$count}}
	                                        	@php $count++ @endphp
	                                        </td>
                                            <td>
                                                <span class="font-weight-bolder">
                                                    {{ucfirst($ramassage->bande->nom)}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bolder">
                                                    {{ucfirst($ramassage->produit->nom)}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bolder">
                                                    {{ucfirst($ramassage->produit->type_animal->nom)}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-glow badge-light-dark">
                                                    @php
                                                        $date = new DateTime($ramassage->to_do_date);
                                                    @endphp
                                                    {{$date->format('d-M-Y')}}
                                                </span> à
                                                <span class="badge badge-glow badge-light-dark">
                                                    {{$date->format('H')}}h:{{$date->format('i')}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-glow @if($ramassage->statut == 0) badge-danger @else badge-success @endif">
                                                    @if($ramassage->statut == 0)
                                                        Supprimé
                                                    @else
                                                        Effectué
                                                    @endif
                                                </span>
                                            </td>
	                                        <td>
	                                            <div>
	                                                <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
	                                                    <i data-feather="more-vertical"></i>
	                                                </button>
	                                                <div class="dropdown-menu">
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#infos{{$ramassage->id}}">
                                                            <i data-feather='shopping-bag'></i>
                                                            <span class="ml-1">Détails</span>
                                                        </a>
                                                        {{-- <a class="dropdown-item" href="{{route('ramassage.edit',$ramassage->id)}}">
                                                            <i data-feather='edit-2'></i>
                                                            <span class="ml-1">Modifier</span>
                                                        </a> --}}
                                                        @if($ramassage->statut == 1)
                                                            <span class="dropdown-item" onclick="actions('form_delete_{{$ramassage->id}}', 'Supprimer' )">
                                                                    <i class="text-danger" data-feather='trash'></i>
                                                                    <span class="ml-1" type="button">
                                                                        Supprimer
                                                                    </span>
                                                                <form id="form_delete_{{$ramassage->id}}" action="{{route('ramassage.destroy',$ramassage->id)}}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </span>
                                                        @endif
                                                    </div>
	                                            </div>
	                                        </td>
	                                    </tr>
	                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($ramassages->hasPages())
                        <nav class="justify-items-center mt-1 d-flex col-12 justify-content-center">
                            <div class="mx-auto row justify-content-center flex-fill d-md-none">
                                <div class="mr-1 d-md-none">
                                    <p class="small text-muted">
                                        {!! __('De') !!}
                                        <span class="fw-semibold">{{ $ramassages->firstItem() }}</span>
                                        {!! __('à') !!}
                                        <span class="fw-semibold">{{ $ramassages->lastItem() }}</span>
                                        {!! __('sur') !!}
                                        <span class="fw-semibold">{{ $ramassages->total() }}</span>
                                        {!! __('resultats') !!}
                                    </p>
                                </div>
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($ramassages->onFirstPage())
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link">Précédent</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $ramassages->currentPage()-1 }})" rel="prev">Suivant</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($ramassages->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $ramassages->currentPage()+1 }})" rel="next">@lang('pagination.next')</a>
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
                                        <span class="fw-semibold">{{ $ramassages->firstItem() }}</span>
                                        {!! __('à') !!}
                                        <span class="fw-semibold">{{ $ramassages->lastItem() }}</span>
                                        {!! __('sur') !!}
                                        <span class="fw-semibold">{{ $ramassages->total() }}</span>
                                        {!! __('resultats') !!}
                                    </p>
                                </div>

                                <div>
                                    <ul class="pagination">
                                        {{-- Previous Page Link --}}
                                        @if ($ramassages->onFirstPage())
                                            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                                <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre({{ $ramassages->currentPage()-1 }})" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($ramassages->links()->elements as $element)
                                            {{-- "Three Dots" Separator --}}
                                            @if (is_string($element))
                                                <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                                            @endif

                                            {{-- Array Of Links --}}
                                            @if (is_array($element))
                                                @foreach ($element as $page => $url)
                                                    @if ($page == $ramassages->currentPage())
                                                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                                    @else
                                                        <li class="page-item"><a class="page-link" onclick="mettre({{ $page }})">{{ $page }}</a></li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($ramassages->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre({{ $ramassages->currentPage()-1 }})" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
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
        </div>
    </div>
</div>
</div>
</div>
@foreach($ramassages as $ramassage)
    <div class="modal fade" id="infos{{$ramassage->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">infos sur le ramassage de {{ucfirst($ramassage->produit->nom)}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                       <b>nom du produit: </b> <span class="badge badge-glow badge-light-dark">{{ucfirst($ramassage->produit->nom)}}</span><br>
                       <b>date et heure du rammassage: </b><span class="badge badge-glow badge-light-dark">
                                                    @php
                                                        $date = new DateTime($ramassage->to_do_date);
                                                    @endphp
                                                    {{$date->format('d-M-Y')}}
                                                </span> à
                                                <span class="badge badge-glow badge-light-dark">
                                                    {{$date->format('H')}}h:{{$date->format('i')}}
                                                </span> <br>
                       <b>nombre de produit ramassé: </b><span class="badge badge-glow badge-light-dark"> {{ json_decode($ramassage->valeurs_derive, true)[0] }}</span><br>
                       {{-- <b>Agent impliqué: </b><span class="badge badge-glow badge-light-dark">{{$ramassage->agent_name->nom}} </span><br> --}}
                       <b>Provenance: </b><span class="badge badge-glow badge-light-dark">{{$ramassage->bande->nom}} </span>
                    </p>
                </div>
                <div class="text-center modal-footer">
                    <button type="button" class="text-center btn btn-success" data-dismiss="modal">ok</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
@section('javascript')
	<script type="text/javascript">
        function mettre(number_page){
           document.querySelector('#page').value = number_page;
           document.querySelector('#search').submit()
        }
		document.querySelector('#RamassageListe').classList.add('active');
        function fetchTransactions() {
            var formData = $('#search').serialize();
            $.ajax({
                url: "{{ route('ramassage.search') }}",
                method: 'GET',
                data: formData,
                success: function(response) {
                    $('#results').html(response); // Insérer le HTML généré par Blade
                    feather.replace(); // Recharger les icônes de Feather
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    // Gérer les erreurs éventuelles
                }
            });
        }

    // Écouter les événements de changement sur les inputs
    $('#search input, #search select').on('change', function() {
        fetchTransactions(); // Appel de la fonction d'actualisation des résultats
    });
	</script>
@endsection
