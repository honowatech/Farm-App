@extends('layouts.app')
@section('title')
{{'Infos planning d\'alimentation'}}
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
                        <h2 class="float-left mb-0 content-header-title">Planning Alimentaire</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Liste des Plannings
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
                    <a class="m-1 btn btn-secondary btn-gradient-secondary" href="{{route('foods-setting.create')}}">
                        <i data-feather='plus' style="width: 20px;height: 20px;"></i>
                        
                    </a>
                        <form action="{{ route('foods-setting.index') }}" class="mt-1 col-lg-3" id="search" method="GET">
                            @csrf
                            <div class="form-group">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather='search'></i></span>
                                    </div>
                                    <input type="search" class="form-control" name="recherche" value="{{ session()->get('search')}}" placeholder="Rechercher un planning">
                                </div>
                            </div>
                            <input type="hidden" name="page" id="page" value="">
                        </form>
                    </div>
                </div>
                <!-- Table without card start -->
                <div class="row" id="table-without-card">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th>N°</th>
                                        <th>Nom Animal</th>
                                        <th>Type de reproduction</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@php
                                    	$count = 20*$types_annimals->currentPage() -19 ;
                                        $table_color = [
                                            'encours' => 'success',
                                            'terminee' => 'danger',
                                        ];
                                	@endphp
                                	@foreach($types_annimals as $types_annimal)
	                                    <tr class="text-center">
	                                        <td>
	                                        	{{$count}}
	                                        	@php $count++ @endphp
	                                        </td>
	                                        <td>
	                                            <span class="font-weight-bolder">
	                                            	{{ucfirst($types_annimal->nom)}}
	                                            </span>
	                                        </td>
                                            <td>
                                                <span class="badge badge-glow badge-light-dark">
                                                      {{ucfirst($types_annimal->type_reproduction)}}

                                                </span>
                                            </td>

	                                        <td>
	                                            <div>
	                                                <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
	                                                    <i data-feather="more-vertical"></i>
	                                                </button>
	                                                <div class="dropdown-menu">
	                                                    <a class="dropdown-item" onclick="response_ajax('info_description',['{{$types_annimal->id}}'],'{{route('foods-setting.show',$types_annimal->id)}}','GET')" data-toggle="modal" data-target="#description">
	                                                        <i data-feather='eye'></i>
	                                                        <span class="ml-1">Informations</span>
	                                                    </a>
                                                        {{-- MODIFICATION ICI : Ouvrir la modale de sélection d'aliment --}}
                                                        <a class="dropdown-item edit-food-setting" data-type-animal-id="{{ $types_annimal->id }}" data-type-animal-name="{{ $types_annimal->nom }}" data-toggle="modal" data-target="#selectFoodModal">
                                                            <i data-feather='edit'></i>
                                                            <span class="ml-1">Editer un planning spécifique</span>
                                                        </a>
                                                        {{-- Garder l'ancienne édition si elle avait un autre but ou la supprimer si elle est remplacée --}}
                                                        {{-- <a class="dropdown-item" href="{{route('foods-setting.edit',$types_annimal->id)}}">
                                                            <i data-feather='edit'></i>
                                                            <span class="ml-1">Editer (ancien)</span>
                                                        </a> --}}
                                                        <span class="dropdown-item" onclick="actions('form_delete_{{$types_annimal->id}}', 'Supprimer le planning d\'alimentation des' ,'{{$types_annimal->nom}}')">
                                                                <i class="text-danger" data-feather='trash'></i>
                                                                <span class="ml-1" type="button">
                                                                    Supprimer
                                                                </span>
                                                            <form id="form_delete_{{$types_annimal->id}}" action="{{route('foods-setting.destroy',$types_annimal->id)}}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </span>
                                                    </div>
	                                            </div>
	                                        </td>
	                                    </tr>
	                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($types_annimals->hasPages())
                        <nav class="justify-items-center mt-1 d-flex col-12 justify-content-center">
                            <div class="mx-auto row justify-content-center flex-fill d-md-none">
                                <div class="mr-1 d-md-none">
                                    <p class="small text-muted">
                                        {!! __('De') !!}
                                        <span class="fw-semibold">{{ $types_annimals->firstItem() }}</span>
                                        {!! __('à') !!}
                                        <span class="fw-semibold">{{ $types_annimals->lastItem() }}</span>
                                        {!! __('sur') !!}
                                        <span class="fw-semibold">{{ $types_annimals->total() }}</span>
                                        {!! __('resultats') !!}
                                    </p>
                                </div>
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($types_annimals->onFirstPage())
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link">Précédent</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $types_annimals->currentPage()-1 }})" rel="prev">Suivant</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($types_annimals->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $types_annimals->currentPage()+1 }})" rel="next">@lang('pagination.next')</a>
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
                                        <span class="fw-semibold">{{ $types_annimals->firstItem() }}</span>
                                        {!! __('à') !!}
                                        <span class="fw-semibold">{{ $types_annimals->lastItem() }}</span>
                                        {!! __('sur') !!}
                                        <span class="fw-semibold">{{ $types_annimals->total() }}</span>
                                        {!! __('resultats') !!}
                                    </p>
                                </div>

                                <div>
                                    <ul class="pagination">
                                        {{-- Previous Page Link --}}
                                        @if ($types_annimals->onFirstPage())
                                            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                                <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre({{ $types_annimals->currentPage()-1 }})" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($types_annimals->links()->elements as $element)
                                            {{-- "Three Dots" Separator --}}
                                            @if (is_string($element))
                                                <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                                            @endif

                                            {{-- Array Of Links --}}
                                            @if (is_array($element))
                                                @foreach ($element as $page => $url)
                                                    @if ($page == $types_annimals->currentPage())
                                                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                                    @else
                                                        <li class="page-item"><a class="page-link" onclick="mettre({{ $page }})">{{ $page }}</a></li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($types_annimals->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre({{ $types_annimals->currentPage()-1 }})" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
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

{{-- Modale existante pour les informations --}}
<div class="modal fade" id="description" tabindex="-1" aria-labelledby="descriptionTitle" aria-modal="true" role="dialog">
    {{-- On enlève "modal-dialog-scrollable" ici --}}
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionTitle">Détails du Planning</h5>
                <button type="button" class="m-0 close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- On applique le style directement sur le conteneur du contenu --}}
                <div id="info_description" style="max-height: 60vh; overflow-y: auto; padding-right: 15px;">
                    Chargement des informations du planning...
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button data-dismiss="modal" class="btn btn-secondary">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Nouvelle Modale pour sélectionner l'aliment --}}
<div class="modal fade" id="selectFoodModal" tabindex="-1" aria-labelledby="selectFoodModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectFoodModalTitle">Sélectionner l'aliment pour <span id="animalTypeName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="foodListBody" style="max-height: 400px; overflow-y: auto;">
                {{-- La liste des aliments sera chargée ici via AJAX --}}
                <div class="text-center">Chargement des aliments...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
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
        //activation du menu setting
        document.querySelector('#Settings').classList.add('active');

        // Gérer l'ouverture de la modale de sélection d'aliment
        $(document).on('click', '.edit-food-setting', function() {
            const typeAnimalId = $(this).data('type-animal-id');
            const typeAnimalName = $(this).data('type-animal-name');

            $('#animalTypeName').text(typeAnimalName);
            $('#foodListBody').html('<div class="text-center">Chargement des aliments...</div>'); // Message de chargement

            // Appel AJAX pour récupérer les aliments associés à ce type d'animal
            $.ajax({
                url: `/settings/foods-setting/${typeAnimalId}/get-foods`, // Nouvelle route à créer
                method: 'GET',
                success: function(response) {
                    let foodListHtml = '';
                    if (response.aliments && response.aliments.length > 0) {
                        response.aliments.forEach(function(food) {
                            foodListHtml += `
                                <a href="/settings/foods-setting/${typeAnimalId}/edit/${food.id}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    ${food.nom_produit}
                                    <i data-feather="chevron-right"></i>
                                </a>
                            `;
                        });
                        $('#foodListBody').html(`<div class="list-group">${foodListHtml}</div>`);
                        feather.replace(); // Réinitialiser les icônes Feather
                    } else {
                        $('#foodListBody').html('<div class="text-center">Aucun planning d\'alimentation défini pour ce type d\'animal.</div>');
                    }
                },
                error: function(xhr) {
                    $('#foodListBody').html('<div class="text-center text-danger">Erreur lors du chargement des aliments.</div>');
                    console.error("Erreur AJAX:", xhr);
                }
            });
        });
    </script>
@endsection