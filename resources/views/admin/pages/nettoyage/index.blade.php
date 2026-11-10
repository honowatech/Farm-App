@extends('layouts.app')

@section('title')
{{'Liste des Séances de nettoyage '}}
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
                        <h2 class="content-header-title float-left mb-0">Liste des Séances de nettoyage</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Liste des Séances de nettoyage
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
                    <a class="btn btn-secondary btn-gradient-secondary m-1" href="{{route('nettoyage.create')}}"><i data-feather='plus' style="width: 20px;height: 20px;"></i></a>
                        <form action="{{ route('nettoyage.index') }}" class="col-lg-3 mt-1" id="search" method="GET">
                            @csrf
                            <div class="form-group">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend ">
                                        <span class="input-group-text"><i data-feather='search'></i></span>
                                    </div>
                                    <input type="search" class="form-control" name="recherche" value="{{ session()->get('search')}}" placeholder="Rechercher" autofocus>
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
                                        <th>Batiment</th>
                                        <th>Date</th>
                                        <th>Verification</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@php
                                    	$count = 20*$nettoyages->currentPage() -19 ;
                                        $table = ['secondary','danger','warning','info','primary','dark'];
                                	@endphp
                                	@foreach($nettoyages as $nettoyage)
                                        @if($nettoyage->batiment != null)
    	                                    <tr class="text-center">
    	                                        <td>
    	                                        	{{$count}}
    	                                        	@php $count++ @endphp
    	                                        </td>
                                                <td>
                                                    <span class="font-weight-bolder">
                                                        {{name($nettoyage->batiment->nom)}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-glow badge-secondary text-truncate">
                                                        Le {{Ladate($nettoyage->created_at)}} à {{Heure($nettoyage->created_at)}}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($nettoyage->verif == 1)
                                                        <div class="badge badge-glow badge-success">
                                                            Oui
                                                    @else
                                                        <div class="badge badge-glow badge-danger">
                                                            Non
                                                    @endif
                                                    </div>
                                                </td>
                                                <td>
    	                                            <div>
                                                        <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                                            <i data-feather="more-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" onclick="response_ajax('info_description',['{{$nettoyage->id}}'],'{{route('nettoyage.show',$nettoyage->id)}}','GET')" data-toggle="modal" data-target="#description">
                                                                <i data-feather='eye'></i>
                                                                <span class="ml-1">Informations</span>
                                                            </a>
                                                            <span class="dropdown-item" onclick="actions('form_delete_{{$nettoyage->id}}', 'Supprimer')">
                                                                    <i class="text-danger" data-feather='trash'></i>
                                                                    <span class="ml-1" type="button">
                                                                        Supprimer
                                                                    </span>
                                                                <form id="form_delete_{{$nettoyage->id}}" action="{{route('nettoyage.destroy',$nettoyage->id)}}" method="POST">
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
                    @if ($nettoyages->hasPages())
                        <nav class="d-flex col-12 mt-1 justify-items-center justify-content-center">
                            <div class="row mx-auto justify-content-center flex-fill d-md-none">
                                <div class="mr-1 d-md-none">
                                    <p class="small text-muted">
                                        {!! __('De') !!}
                                        <span class="fw-semibold">{{ $nettoyages->firstItem() }}</span>
                                        {!! __('à') !!}
                                        <span class="fw-semibold">{{ $nettoyages->lastItem() }}</span>
                                        {!! __('sur') !!}
                                        <span class="fw-semibold">{{ $nettoyages->total() }}</span>
                                        {!! __('resultats') !!}
                                    </p>
                                </div>
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($nettoyages->onFirstPage())
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link">Précédent</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $nettoyages->currentPage()-1 }})" rel="prev">Suivant</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($nettoyages->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $nettoyages->currentPage()+1 }})" rel="next">@lang('pagination.next')</a>
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
                                        <span class="fw-semibold">{{ $nettoyages->firstItem() }}</span>
                                        {!! __('à') !!}
                                        <span class="fw-semibold">{{ $nettoyages->lastItem() }}</span>
                                        {!! __('sur') !!}
                                        <span class="fw-semibold">{{ $nettoyages->total() }}</span>
                                        {!! __('resultats') !!}
                                    </p>
                                </div>

                                <div>
                                    <ul class="pagination">
                                        {{-- Previous Page Link --}}
                                        @if ($nettoyages->onFirstPage())
                                            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                                <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre({{ $nettoyages->currentPage()-1 }})" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($nettoyages->links()->elements as $element)
                                            {{-- "Three Dots" Separator --}}
                                            @if (is_string($element))
                                                <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                                            @endif

                                            {{-- Array Of Links --}}
                                            @if (is_array($element))
                                                @foreach ($element as $page => $url)
                                                    @if ($page == $nettoyages->currentPage())
                                                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                                    @else
                                                        <li class="page-item"><a class="page-link" onclick="mettre({{ $page }})">{{ $page }}</a></li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($nettoyages->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre({{ $nettoyages->currentPage()-1 }})" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
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
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionTitle"> Informations </h5>
                <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row p-0">
                    <div class="col-10 mx-auto" id="info_description">
                        Infos sur le nettoyage!
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
		document.querySelector('#NettoyageListe').classList.add('active');
	</script>
@endsection
