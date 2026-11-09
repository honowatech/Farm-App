@extends('admin/templates/template')

@section('menu')
    @include('admin.menu.menu')
@endsection

@section('title')
{{'Liste des types de dépense'}}
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
                        <h2 class="content-header-title float-left mb-0">{{ __('depenses::translated.Liste des catégories de dépense') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">{{ __('depenses::translated.Acceuil') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('depenses::translated.Liste des catégories de dépense') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card pl-1 pr-1">
            <div class="content-body">
                <div class="row justify-content-between bg-light-secondary" >
                    <a class="btn btn-secondary btn-gradient-secondary m-1" href="{{route('categorie.create')}}">
                        <i data-feather='plus' style="width: 20px;height: 20px;"></i>
                    </a>
                        <form action="{{ route('categorie.index') }}" class="col-lg-3 mt-1" id="search" method="GET">
                            @csrf
                            <div class="form-group">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend ">
                                        <span class="input-group-text"><i data-feather='search'></i></span>
                                    </div>
                                    <input type="search" class="form-control" name="recherche" value="{{ session()->get('search')}}" placeholder="Rechercher une catégories">
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
                                    <th>{{ __('depenses::translated.Nom') }}</th>
                                    <th>{{ __('depenses::translated.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $count = 20 * $categories->currentPage() - 19;
                                @endphp
                                @foreach($categories as $categorie)
                                    <tr class="text-center">
                                        <td>
                                            {{ $count }}
                                            @php $count++ @endphp
                                        </td>
                                        <td>
                                            <span class="font-weight-bolder">{{ ucfirst($categorie->nom) }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                                    <i data-feather="more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" onclick="response_ajax('info_description',['{{ $categorie->id }}'],'{{ route('categorie.show', $categorie->id) }}','GET')" data-toggle="modal" data-target="#description">
                                                        <i data-feather='eye'></i>
                                                        <span class="ml-1">{{ __('depenses::translated.Informations') }}</span>
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('categorie.edit', $categorie->id) }}">
                                                        <i data-feather="edit-2"></i>
                                                        <span class="ml-1">{{ __('depenses::translated.Editer') }}</span>
                                                    </a>
                                                    <span class="dropdown-item" onclick="actions('form_delete_{{ $categorie->id }}', '{{ __('depenses::translated.Supprimer') }}', '{{ $categorie->nom }}')">
                                                        <i class="text-danger" data-feather='trash'></i>
                                                        <span class="ml-1">{{ __('depenses::translated.Supprimer') }}</span>
                                                        <form id="form_delete_{{ $categorie->id }}" action="{{ route('categorie.destroy', $categorie->id) }}" method="POST" style="display: none;">
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

                @if ($categories->hasPages())
                    <nav class="d-flex col-12 mt-1 justify-items-center justify-content-center">
                        <div class="row mx-auto justify-content-center flex-fill d-md-none">
                            <div class="mr-1 d-md-none">
                                <p class="small text-muted">
                                    {!! __('De') !!}
                                    <span class="fw-semibold">{{ $categories->firstItem() }}</span>
                                    {!! __('à') !!}
                                    <span class="fw-semibold">{{ $categories->lastItem() }}</span>
                                    {!! __('sur') !!}
                                    <span class="fw-semibold">{{ $categories->total() }}</span>
                                    {!! __('resultats') !!}
                                </p>
                            </div>
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($categories->onFirstPage())
                                    <li class="page-item disabled" aria-disabled="true">
                                        <span class="page-link">Précédent</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" onclick="mettre({{ $categories->currentPage()-1 }})" rel="prev">Suivant</a>
                                    </li>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($categories->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" onclick="mettre({{ $categories->currentPage()+1 }})" rel="next">@lang('pagination.next')</a>
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
                                    <span class="fw-semibold">{{ $categories->firstItem() }}</span>
                                    {!! __('à') !!}
                                    <span class="fw-semibold">{{ $categories->lastItem() }}</span>
                                    {!! __('sur') !!}
                                    <span class="fw-semibold">{{ $categories->total() }}</span>
                                    {!! __('resultats') !!}
                                </p>
                            </div>

                            <div>
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($categories->onFirstPage())
                                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                            <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $categories->currentPage()-1 }})" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($categories->links()->elements as $element)
                                        {{-- "Three Dots" Separator --}}
                                        @if (is_string($element))
                                            <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                                        @endif

                                        {{-- Array Of Links --}}
                                        @if (is_array($element))
                                            @foreach ($element as $page => $url)
                                                @if ($page == $categories->currentPage())
                                                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                                @else
                                                    <li class="page-item"><a class="page-link" onclick="mettre({{ $page }})">{{ $page }}</a></li>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($categories->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $categories->currentPage()-1 }})" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
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
<div id="button_footer" hidden>
    <button type="button" class="col-md-5 col-10 mx-auto btn btn-gradient-danger btn-danger" data-dismiss="modal">Annuler</button>
    <form method="POST" class="destroy_form m-0 p-0 col-md-5 col-10 mx-auto">
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
		document.querySelector('#CategorieListe').classList.add('active');
	</script>
@endsection
