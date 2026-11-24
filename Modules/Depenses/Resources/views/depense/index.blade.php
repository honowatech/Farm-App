@extends('layouts.app')

@section('title')
{{'Toute les dépenses'}}
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
                            <h2 class="content-header-title float-left mb-0">{{ __('depenses::translated.Liste des Dépenses') }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('depenses::translated.Acceuil') }}</a></li>
                                    <li class="breadcrumb-item active">{{ __('depenses::translated.Liste des Dépenses') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card pl-1 pr-1">
                <div class="content-body">
                    @if(session('message'))
                        <div class="alert alert-success">{{ session('message') }}</div>
                    @endif
                    <div id="searchError" class="alert alert-danger d-none" role="alert"></div>
                    <div class="row bg-light-secondary">
                        <div class="col-12">
                            <a data-tour-step="create" class="btn btn-secondary btn-gradient-secondary m-1" href="{{ route('depenses.create') }}">
                                <i data-feather='plus' style="width: 20px;height: 20px;"></i> Nouvelle dépense
                            </a>
                        </div>

                        <form data-tour-step="search" class="col-12 mt-1" id="search" method="GET">
                            @csrf
                            <div class="row mx-auto">

                                <!-- type -->
                                <div class="form-group col-12 col-md-3">
                                    <label>{{ __('depenses::translated.Catégorie') }}</label>
                                    <select id="type" class="select2 type form-control" name="id_type" required>
                                        <option selected value="">{{ __('depenses::translated.Choix de la Catégorie') }}</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label>{{ __('depenses::translated.Fournisseur') }}</label>
                                    <select id="fournisseur" class="select2 type form-control" name="fournisseur" required>
                                        <option selected value="">{{ __('depenses::translated.Choix du Fournisseur') }}</option>
                                        <option value="autre">{{ __('depenses::translated.Autre') }}</option>
                                        @foreach($fournisseurs as $fournisseur)
                                            <option value="{{ $fournisseur }}">{{ $fournisseur }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Date de debut -->
                                <div class="form-group col-12 col-md-3">
                                    <label>{{ __('depenses::translated.Date de début') }}</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i data-feather='search'></i></span>
                                        </div>
                                        <input type="text" name="date_debut" class="form-control date_debut bg-white flatpickr-date-time flatpickr-input" placeholder="{{ __('depenses::translated.Date de début') }}" id="date_debut">
                                    </div>
                                </div>

                                <!-- Date de fin -->
                                <div class="form-group col-12 col-md-3">
                                    <label>{{ __('depenses::translated.Date de fin') }}</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i data-feather='search'></i></span>
                                        </div>
                                        <input type="text" name="date_fin" class="form-control date_fin bg-white flatpickr-date-time flatpickr-input" placeholder="{{ __('depenses::translated.Date de fin') }}" id="date_fin">
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
                <div data-tour-step="table" class="row" id="table-without-card">
                    <div class="table-responsive" id="results">
                        <table class="table">
                            <thead>
                                <tr class="text-center">
                                    <th>N°</th>
                                    <th>{{ __('depenses::translated.Motif de la dépense') }}</th>
                                    <th>{{ __('depenses::translated.Montant') }}</th>
                                    <th>{{ __('depenses::translated.Fournisseur') }}</th>
                                    <th>{{ __('depenses::translated.Date') }}</th>
                                    <th>{{ __('depenses::translated.Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($depenses as $depense)
                                    <tr class="text-center">
                                        @php
                                            $count = 20 * $depenses->currentPage() - 19 + $loop->index;
                                        @endphp
                                        <td>{{ $count }}</td>
                                        <td>
                                            <span class="font-weight-bolder">{{ ucfirst($depense->motif_depense) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-center text-dark font-weight-bolder">{{ name($depense->montant) }} FCFA</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-glow badge-secondary bg-secondary bg-lighten-3">{{ ucfirst($depense->fournisseur) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-glow badge-secondary bg-secondary bg-lighten-3">
                                                Le {{ Ladate($depense->date_depense) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                                    <i data-feather="more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('depenses.edit', $depense->id) }}">
                                                        <i data-feather="edit-2"></i>
                                                        <span class="ml-1">Modifier</span>
                                                    </a>
                                                    <a class="dropdown-item" href="#" onclick="response_ajax('info_description', [], '{{ route('depenses.show', $depense->id) }}', 'GET'); return false;" data-toggle="modal" data-target="#description">
                                                        <i data-feather="eye"></i>
                                                        <span class="ml-1">Informations</span>
                                                    </a>
                                                    <span class="dropdown-item" onclick="actions('form_delete_{{ $depense->id }}', '{{ __('depenses::translated.Effacer') }}')">
                                                        <i class="text-danger" data-feather='trash'></i>
                                                        <span class="ml-1">{{ __('depenses::translated.Effacer') }}</span>
                                                        <form id="form_delete_{{ $depense->id }}" action="{{ route('depenses.destroy', $depense->id) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-2 text-center text-muted">Aucune dépense trouvée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
                @if ($depenses->hasPages())
                    <nav class="d-flex col-12 mt-1 justify-items-center justify-content-center">
                        <div class="row mx-auto justify-content-center flex-fill d-md-none">
                            <div class="mr-1 d-md-none">
                                <p class="small text-muted">
                                    {!! __('De') !!}
                                    <span class="fw-semibold">{{ $depenses->firstItem() }}</span>
                                    {!! __('à') !!}
                                    <span class="fw-semibold">{{ $depenses->lastItem() }}</span>
                                    {!! __('sur') !!}
                                    <span class="fw-semibold">{{ $depenses->total() }}</span>
                                    {!! __('resultats') !!}
                                </p>
                            </div>
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($depenses->onFirstPage())
                                    <li class="page-item disabled" aria-disabled="true">
                                        <span class="page-link">Précédent</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" onclick="mettre({{ $depenses->currentPage()-1 }})" rel="prev">Précédent</a>
                                    </li>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($depenses->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" onclick="mettre({{ $depenses->currentPage()+1 }})" rel="next">@lang('pagination.next')</a>
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
                                    <span class="fw-semibold">{{ $depenses->firstItem() }}</span>
                                    {!! __('à') !!}
                                    <span class="fw-semibold">{{ $depenses->lastItem() }}</span>
                                    {!! __('sur') !!}
                                    <span class="fw-semibold">{{ $depenses->total() }}</span>
                                    {!! __('resultats') !!}
                                </p>
                            </div>

                            <div>
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($depenses->onFirstPage())
                                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                            <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $depenses->currentPage()-1 }})" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($depenses->links()->elements as $element)
                                        {{-- "Three Dots" Separator --}}
                                        @if (is_string($element))
                                            <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                                        @endif

                                        {{-- Array Of Links --}}
                                        @if (is_array($element))
                                            @foreach ($element as $page => $url)
                                                @if ($page == $depenses->currentPage())
                                                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                                @else
                                                    <li class="page-item"><a class="page-link" onclick="mettre({{ $page }})">{{ $page }}</a></li>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($depenses->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $depenses->currentPage()+1 }})" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
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
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionTitle">Ligne de stock</h5>
                <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-10 mx-auto" id="info_description">
                        Une ligne D'ajout de stock s'affichera ici
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center justify-content-md-apt-1">
                <button data-dismiss="modal" class="btn px-2 btn-success btn-gradient-success mx-auto">
                    <i data-feather='check' style="width: 25px;height: 25px;"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@include('partials.modals.confirm-delete')

@endsection
@section('javascript')
    <script type="text/javascript">
        function mettre(number_page){
            document.querySelector('#page').value = number_page;
            fetchTransactions();
        }

        function fetchTransactions() {
            var formData = $('#search').serialize();
            $.ajax({
                url: "{{ route('depense.search') }}",
                method: 'GET',
                data: formData,
                success: function(response) {
                    $('#results').html(response); // Insérer le HTML généré par Blade
                    $('#searchError').addClass('d-none').text('');
                    feather.replace(); // Recharger les icônes de Feather
                },
                error: function(xhr) {
                    var message = 'Impossible de charger les dépenses.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        message = Object.values(xhr.responseJSON.errors)[0][0];
                    }
                    $('#searchError').text(message).removeClass('d-none');
                }
            });
        }

        $(document).ready(function() {
            $('#search input, #search select').on('change', function() {
                document.querySelector('#page').value = 1;
                fetchTransactions();
            });
        });
    </script>
@endsection
