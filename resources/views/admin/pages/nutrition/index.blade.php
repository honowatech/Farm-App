@extends('layouts.app')
@section('title')
{{'Toute les nutritions'}}
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
                        <h2 class="float-left mb-0 content-header-title">Suivie des nutrition</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Suivie des nutritions
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
                    <a class="m-1 btn btn-secondary btn-gradient-secondary" href="{{route('nutrition.create')}}">
                        <i data-feather='plus' style="width: 20px;height: 20px;"></i> Nouvelle nutrition
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
                                        <th>aliment</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@php
                                    	$count = 20*$nutritions->currentPage() -19 ;
                                	@endphp
                                	@foreach($nutritions as $nutrition)
                                        @if($nutrition->aliment_name)
    	                                    <tr class="text-center">
    	                                        <td>
    	                                        	{{$count}}
    	                                        	@php $count++ @endphp
    	                                        </td>
    	                                        <td>
    	                                            <span class="font-weight-bolder">
                                                        {{ucfirst($nutrition->bande_name->nom)}}
    	                                            </span>
    	                                        </td>
                                                <td>
                                                    <span class="font-weight-bolder text-truncate">

                                                            {{ucfirst($nutrition->aliment_name->nom_produit)}}

                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-glow badge-secondary bg-secondary bg-lighten-3"> Le
                                                        {{Ladate($nutrition->date)}}
                                                    </span>
                                                </td>
    	                                        <td>
    	                                            <div>
    	                                                <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
    	                                                    <i data-feather="more-vertical"></i>
    	                                                </button>
    	                                                <div class="dropdown-menu">
                                                            <a class="dropdown-item" onclick="response_ajax('info_description',['{{$nutrition->id}}'],'{{route('nutrition.show',$nutrition->id)}}','GET')" data-toggle="modal" data-target="#description">
                                                                <i data-feather='eye'></i>
                                                                <span class="ml-1">Informations</span>
                                                            </a>
                                                            <a class="dropdown-item" href="{{route('nutrition.edit',$nutrition->id)}}">
                                                                <i data-feather='edit'></i>
                                                                <span class="ml-1">Editer</span>
                                                            </a>
                                                            <span class="dropdown-item" onclick="actions('form_delete_{{$nutrition->id}}', 'Supprimer le planning d\'alimentation des' ,'{{$nutrition->nom}}')">
                                                                    <i class="text-danger" data-feather='trash'></i>
                                                                    <span class="ml-1" type="button">
                                                                        Supprimer
                                                                    </span>
                                                                <form id="form_delete_{{$nutrition->id}}" action="{{route('nutrition.destroy',$nutrition->id)}}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </span>
                                                        </div>
    	                                            </div>
                                                </td>
    	                                    </tr>
                                        @endif
	                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($nutritions->hasPages())
                        <nav class="justify-items-center mt-1 d-flex col-12 justify-content-center">
                            <div class="mx-auto row justify-content-center flex-fill d-md-none">
                                <div class="mr-1 d-md-none">
                                    <p class="small text-muted">
                                        {!! __('De') !!}
                                        <span class="fw-semibold">{{ $nutritions->firstItem() }}</span>
                                        {!! __('à') !!}
                                        <span class="fw-semibold">{{ $nutritions->lastItem() }}</span>
                                        {!! __('sur') !!}
                                        <span class="fw-semibold">{{ $nutritions->total() }}</span>
                                        {!! __('resultats') !!}
                                    </p>
                                </div>
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($nutritions->onFirstPage())
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link">Précédent</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $nutritions->currentPage()-1 }})" rel="prev">Suivant</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($nutritions->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $nutritions->currentPage()+1 }})" rel="next">@lang('pagination.next')</a>
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
                                        <span class="fw-semibold">{{ $nutritions->firstItem() }}</span>
                                        {!! __('à') !!}
                                        <span class="fw-semibold">{{ $nutritions->lastItem() }}</span>
                                        {!! __('sur') !!}
                                        <span class="fw-semibold">{{ $nutritions->total() }}</span>
                                        {!! __('resultats') !!}
                                    </p>
                                </div>

                                <div>
                                    <ul class="pagination">
                                        {{-- Previous Page Link --}}
                                        @if ($nutritions->onFirstPage())
                                            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                                <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre({{ $nutritions->currentPage()-1 }})" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($nutritions->links()->elements as $element)
                                            {{-- "Three Dots" Separator --}}
                                            @if (is_string($element))
                                                <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                                            @endif

                                            {{-- Array Of Links --}}
                                            @if (is_array($element))
                                                @foreach ($element as $page => $url)
                                                    @if ($page == $nutritions->currentPage())
                                                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                                    @else
                                                        <li class="page-item"><a class="page-link" onclick="mettre({{ $page }})">{{ $page }}</a></li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($nutritions->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre({{ $nutritions->currentPage()-1 }})" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
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
<div class="modal fade" id="description" tabindex="-1" aria-labelledby="descriptionTitle" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionTitle"> Informations </h5>
                <button type="button" class="m-0 close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mx-auto col-10" id="info_description">
                        Infos de chaque Batîment ici !
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center justify-content-md-apt-1">
                <button data-dismiss="modal" class="px-2 mx-auto btn btn-success btn-gradient-success">
                    Ok
                </button>
            </div>
        </div>
    </div>
</div>
<div id="button_footer" hidden>
    <button type="button" class="mx-auto col-md-5 col-10 btn btn-gradient-danger btn-danger" data-dismiss="modal">Annuler</button>
    <form method="POST" class="p-0 m-0 mx-auto destroy_form col-md-5 col-10">
        @csrf
        @method('DELETE')
        <button type="submit" class="col-12 btn btn-gradient-success btn-success">Confirmer</button>
    </form>
</div>

@endsection
@section('javascript')
	<script type="text/javascript">
        function mettre(number_page){
           document.querySelector('#page').value = number_page;
           document.querySelector('#search').submit()
        }
    document.getElementById('NutritionListe').classList.add('active');
    $(document).ready(function() {
    function fetchTransactions() {
        var formData = $('#search').serialize();
        $.ajax({
            url: "{{ route('nutrition.search') }}",
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
});
	</script>
@endsection
