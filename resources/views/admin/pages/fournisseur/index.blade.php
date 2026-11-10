@extends('layouts.app')

@section('title')
{{'Liste des fournisseurs '}}
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
                        <h2 class="float-left mb-0 content-header-title">Liste des Fournisseurs</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Liste des Fournisseurs
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
                    <a class="m-1 btn btn-secondary btn-gradient-secondary" href="{{route('fournisseur.create')}}">
                        <i data-feather='plus' style="width: 20px;height: 20px;"></i> Nouveau fournisseur
                    </a>
                    <a class="m-1 btn btn-success btn-gradient-success" href="{{ route('fournisseur.export') }}">
                        <i class="fa-solid fa-file-excel mr-50"></i> Exporter CSV
                    </a>
                    <a class="m-1 btn btn-danger btn-gradient-danger" href="{{ route('fournisseur.export.pdf') }}">
                        <i class="fa-solid fa-file-pdf mr-50"></i> Exporter PDF
                    </a>
                        <form action="{{ route('fournisseur.index') }}" class="mt-1 col-lg-3" id="search" method="GET">
                            @csrf
                            <div class="form-group">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather='search'></i></span>
                                    </div>
                                    <input type="search" class="form-control" name="recherche" value="{{ session()->get('search')}}" placeholder="Rechercher un fournisseur">
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
                                        <th>Noms Et Prénoms</th>
                                        <th>Telephone (s)</th>
                                        {{--<th>Avatar</th>--}}
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@php
                                    	$count = 20*$fournisseurs->currentPage() -19 ;
                                        $table = ['secondary','danger','warning','info','primary','dark'];
                                	@endphp
                                	@foreach($fournisseurs as $fournisseur)
	                                    <tr class="text-center">
	                                        <td>
	                                        	{{$count}}
	                                        	@php $count++ @endphp
	                                        </td>
	                                        <td>
	                                            <span class="font-weight-bolder">
	                                            	{{name($fournisseur->nom)}}
	                                            </span>
	                                        </td>
	                                        <td>
	                                            <span class="font-weight-bolder">
	                                            	{{phone($fournisseur->telephone)}}
                                                    @if($fournisseur->telephone_secondaire != null)
                                                        / {{phone($fournisseur->telephone_secondaire)}}
                                                    @endif
	                                            </span>
	                                        </td>
                                            {{--
                                                <td>
                                                    <div class="avatar badge-glow bg-{{ $table[strlen($fournisseur->prenom.$fournisseur->nom) % count($table)] }} avatar-bg">
                                                        <span class="avatar-content">
                                                            {{$fournisseur->nom[0]}}{{$fournisseur->nom[1]}}
                                                        </span>
                                                    </div>
                                                </td>
                                            --}}
                                            <td>
                                                <span class="badge badge-pill badge-glow @if($fournisseur->statut == 0) badge-danger @else badge-success @endif">
                                            		@if($fournisseur->statut == 0)
		                                             	Désactivé
		                                            @else
		                                            	Actif
		                                            @endif
                                                </span>
                                            </td>
	                                        <td>
	                                            <div>
	                                                <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
	                                                    <i data-feather="more-vertical"></i>
	                                                </button>
	                                                <div class="dropdown-menu">
	                                                    <a class="dropdown-item" href="{{route('fournisseur.show',$fournisseur->id)}}">
	                                                        <i data-feather='eye'></i>
	                                                        <span class="ml-1">Détails</span>
	                                                    </a>
                                                        <a class="dropdown-item" href="{{route('fournisseur.edit',$fournisseur->id)}}">
                                                            <i data-feather="edit-2" class="mr-50"></i>
                                                            <span class="ml-1">Editer</span>
                                                        </a>
                                                        <span class="dropdown-item" onclick="actions('form_active_{{$fournisseur->id}}', @if($fournisseur->statut == 1) 'Désactiver' @else 'Activer' @endif ,'{{$fournisseur->nom}}')">
                                                            @if($fournisseur->statut == 1)
                                                                <i class="text-danger" data-feather='user-minus'></i>
                                                                <span class="ml-1" type="button">
                                                                    Désactiver
                                                                </span>
                                                            @else
                                                                <i class="text-success" data-feather='user-check'></i>
                                                                <span class="ml-1" type="button">
                                                                    Activer
                                                                </span>
                                                            @endif
                                                            <form id="form_active_{{$fournisseur->id}}" action="{{route('fournisseur.destroy',$fournisseur->id)}}" method="POST">
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
                    @if ($fournisseurs->hasPages())
                        <nav class="justify-items-center mt-1 d-flex col-12 justify-content-center">
                            <div class="mx-auto row justify-content-center flex-fill d-md-none">
                                <div class="mr-1 d-md-none">
                                    <p class="small text-muted">
                                        {!! __('De') !!}
                                        <span class="fw-semibold">{{ $fournisseurs->firstItem() }}</span>
                                        {!! __('à') !!}
                                        <span class="fw-semibold">{{ $fournisseurs->lastItem() }}</span>
                                        {!! __('sur') !!}
                                        <span class="fw-semibold">{{ $fournisseurs->total() }}</span>
                                        {!! __('resultats') !!}
                                    </p>
                                </div>
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($fournisseurs->onFirstPage())
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link">Précédent</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $fournisseurs->currentPage()-1 }})" rel="prev">Suivant</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($fournisseurs->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $fournisseurs->currentPage()+1 }})" rel="next">@lang('pagination.next')</a>
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
                                        <span class="fw-semibold">{{ $fournisseurs->firstItem() }}</span>
                                        {!! __('à') !!}
                                        <span class="fw-semibold">{{ $fournisseurs->lastItem() }}</span>
                                        {!! __('sur') !!}
                                        <span class="fw-semibold">{{ $fournisseurs->total() }}</span>
                                        {!! __('resultats') !!}
                                    </p>
                                </div>

                                <div>
                                    <ul class="pagination">
                                        {{-- Previous Page Link --}}
                                        @if ($fournisseurs->onFirstPage())
                                            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                                <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre({{ $fournisseurs->currentPage()-1 }})" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($fournisseurs->links()->elements as $element)
                                            {{-- "Three Dots" Separator --}}
                                            @if (is_string($element))
                                                <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                                            @endif

                                            {{-- Array Of Links --}}
                                            @if (is_array($element))
                                                @foreach ($element as $page => $url)
                                                    @if ($page == $fournisseurs->currentPage())
                                                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                                    @else
                                                        <li class="page-item"><a class="page-link" onclick="mettre({{ $page }})">{{ $page }}</a></li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($fournisseurs->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre({{ $fournisseurs->currentPage()-1 }})" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
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
@endsection
@section('javascript')
	<script type="text/javascript">
        function mettre(number_page){
           document.querySelector('#page').value = number_page;
           document.querySelector('#search').submit()
        }
		document.querySelector('#FournisseurListe').classList.add('active');
	</script>
@endsection
