@extends('layouts.app')

@section('title')
{{'Liste des Types d\'animlal'}}
@endsection
@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
            <div class="content-header row">
    	 <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Liste des Types d'animaux</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Liste des Types d'animaux
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
            </div>
    <div class="card pl-1 pr-1">

            <div class="content-body">
                <div class="row justify-content-between bg-light-secondary" >
                    <a class="btn btn-secondary btn-gradient-secondary m-1" href="{{route('type_animal.create')}}">
                        <i data-feather='plus' style="width: 20px;height: 20px;"></i> Nouveau type d'animal
                    </a>
                        <form action="{{ route('type_animal.index') }}" class="col-lg-3 mt-1" id="search" method="GET">
                            @csrf
                            <div class="form-group">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend ">
                                        <span class="input-group-text"><i data-feather='search'></i></span>
                                    </div>
                                    <input type="search" class="form-control" name="recherche" value="{{ session()->get('search')}}" placeholder="Rechercher un type animal">
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
                                        <th>Nom</th>
                                        <th>Reproduction</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@php
                                    	$count = 20*$type_animals->currentPage() -19 ;
                                	@endphp
                                	@foreach($type_animals as $type_animal)
	                                    <tr class="text-center">
	                                        <td>
	                                        	{{$count}}
	                                        	@php $count++ @endphp
	                                        </td>
	                                        <td>
	                                            <span class="font-weight-bolder">
	                                            	{{ucfirst($type_animal->nom)}}
	                                            </span>
	                                        </td>
                                            <td>
                                                <span class="badge badge-pill badge-glow badge-{{ $colors[$type_animal->type_reproduction] }}">
                                                {{ $types_reproduction[$type_animal->type_reproduction] }}
                                                 </span>
                                            </td>
	                                        <td>
	                                            <div>
	                                                <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
	                                                    <i data-feather="more-vertical"></i>
	                                                </button>
	                                                <div class="dropdown-menu">
	                                                    <a class="dropdown-item" onclick="response_ajax('info_description',['{{$type_animal->id}}'],'{{route('type_animal.show',$type_animal->id)}}','GET')" data-toggle="modal" data-target="#description">
	                                                        <i data-feather='eye'></i>
	                                                        <span class="ml-1">Informations</span>
	                                                    </a>
                                                        <a class="dropdown-item" href="{{route('type_animal.edit',$type_animal->id)}}">
                                                            <i data-feather="edit-2" class=" "></i>
                                                            <span class="ml-1">Editer</span>
                                                        </a>
                                                        <span class="dropdown-item" onclick="actions('form_delete_{{$type_animal->id}}', 'Supprimer' ,'{{$type_animal->nom}}')">
                                                                <i class="text-danger" data-feather='trash'></i>
                                                                <span class="ml-1" type="button">
                                                                    Supprimer
                                                                </span>
                                                            <form id="form_delete_{{$type_animal->id}}" action="{{route('type_animal.destroy',$type_animal->id)}}" method="POST">
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
                    <hr class="p-0 m-0">
                    <div>
                        <div class="d-flex col-md-10 mx-auto" style=" padding:1rem; overflow: auto; border-radius: 4px;">
                            <ul class="pagination"  style="margin-bottom:  0rem">
                            {{-- Previous Page Link --}}
                                @if ($type_animals->onFirstPage())
                                    <li class="page-item my-auto tex-dark" style="cursor: no-drop;">
                                        <span disabled class="page-link"><i class="mr-50" data-feather='chevrons-left'></i></span>
                                    </li>
                                @else
                                    <li class="page-item my-auto">
                                        <a class="page-link" onclick="mettre({{ $type_animals->currentPage()-1 }})" rel="prev" aria-label="@lang('pagination.previous')"><i class="mr-50" data-feather='chevrons-left'></i></a>
                                    </li>
                                @endif
                                @for($i=1;$i<=$type_animals->lastPage();$i++)
                                    <li class=" page-item my-auto @if($i==$type_animals->currentPage()) active @endif" >
                                        <a class="rounded-pill page-link" onclick="@if($i!=$type_animals->currentPage()) mettre({{$i}}) @endif" rel="prev" aria-label="@lang('pagination.previous')">{{$i}}</a>
                                    </li>
                                @endfor
                                {{-- Next Page Link --}}
                                @if ($type_animals->hasMorePages())
                                    <li class="page-item my-auto">
                                        <a class="page-link" onclick="mettre({{ $type_animals->currentPage()+1 }})" rel="next" aria-label="@lang('pagination.next')"><i class="ml-50" data-feather='chevrons-right'></i></a>
                                    </li>
                                @else
                                    <li class="page-item my-auto tex-dark" style="cursor: no-drop;">
                                        <span disabled class="page-link"><i class="ml-50" data-feather='chevrons-right'></i></span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
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
                <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-10 mx-auto" id="info_description">
                        Infos de chaque Batîment ici !
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center justify-content-md-apt-1">
                <button data-dismiss="modal" class="btn px-2 btn-success btn-gradient-success mx-auto">
                    Ok
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
		document.querySelector('#Type_animalListe').classList.add('active');
	</script>
@endsection
