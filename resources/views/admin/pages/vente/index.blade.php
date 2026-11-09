@extends('admin/templates/template')
@section('title')
{{'Tous les ventes'}}
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
                        <h2 class="float-left mb-0 content-header-title">Liste des ventes</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Liste des ventes
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
            </div>
    <div class="pr-1 pl-1 card">

            <div class="content-body">
                <div class="row bg-light-secondary">
                    <div class="col-12">
                        <a class="m-1 btn btn-secondary btn-gradient-secondary" href="{{route('vente.create')}}">
                            <i data-feather='plus' style="width: 20px;height: 20px;"></i>
                        </a>
                    </div>

                    <form  class="mt-1 col-12" id="search" method="GET">
                        @csrf
                        <div class="row">
                            <!-- Date de debut -->
                            <div class="form-group col-12 col-md-2">
                                <label>Date de début</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather='search'></i></span>
                                    </div>
                                    <input type="text" name="date_debut" class="bg-white form-control date_debut flatpickr-basic flatpickr-input" placeholder="Date début" id="date_debut">
                                </div>
                            </div>

                            <!-- Date de fin -->
                            <div class="form-group col-12 col-md-2">
                                <label>Date de fin</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather='search'></i></span>
                                    </div>
                                    <input type="text" name="date_fin" class="bg-white form-control date_fin flatpickr-basic flatpickr-input" placeholder="Date fin" id="date_fin">
                                </div>
                            </div>

                            <!-- Produit -->
                            <div class="form-group col-12 col-md-2">
                                <label>Produit</label>
                                <select id="produit" class="select2 produit form-control @error('num_produit') is-invalid @enderror" name="id_produit" required>
                                    <option selected value="">Choix produit</option>
                                    @foreach($produits as $produit)
                                        <option value="{{$produit->id}}"> {{$produit->nom}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Client -->
                            <div class="form-group col-12 col-md-2">
                                <label>Client</label>
                                <select id="client" class="select2 client form-control @error('num_agent') is-invalid @enderror" name="id_client" required>
                                    <option selected value="">Choix client</option>
                                    @foreach($clients as $client)
                                        <option class="client" value="{{$client->id}}">{{$client->nom}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Recherche -->
                            <div class="form-group col-12 col-md-2">
                                <label>Recherche</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather='search'></i></span>
                                    </div>
                                    <input type="search" class="form-control" name="recherche" value="{{ session()->get('search')}}" placeholder="Rechercher une vente">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="page" id="page" value="">
                    </form>
                </div>
            </div>
                <!-- Table without card start -->
                <div class="row" id="table-without-card">
                        <div class="table-responsive" id = "results">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th>N°</th>
                                        <th>Nom client</th>
                                        <th>Nom Fournisseur</th>
                                        <th>Date </th>
                                        <th>Total </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@php
                                    	$count = 20*$ventes->currentPage() -19 ;
                                        $img = [
                                            'mobilemoney' => 'app-assets/images/logo/mtn.svg',
                                            'orangemoney' => 'app-assets/images/logo/orange.png',
                                            'espece' => 'app-assets/images/logo/espece.webp',
                                            'bank' => 'app-assets/images/logo/bank.png',
                                        ];
                                        $title = [
                                            'mobilemoney' => 'Mtn MOMO',
                                            'orangemoney' => 'Orange Money',
                                            'espece' => 'Espèce',
                                            'bank' => 'Paiement Bancaire'
                                        ];
                                	@endphp
                                	@foreach($ventes as $vente)
	                                    <tr class="text-center">
	                                        <td>
	                                        	{{$count}}
	                                        	@php $count++ @endphp
	                                        </td>
	                                        <td>
	                                            <span class="font-weight-bolder">
                                                    {{ucfirst($vente->facture->client_facture->nom)}}
	                                            </span>
	                                        </td>
                                            <td>
                                                <span class="text-center text-dark font-weight-bolder">
                                                    {{name($vente->saver->name)}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-glow badge-secondary bg-secondary bg-lighten-3"> Le
                                                    {{Ladate($vente->date_vente)}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bolder text-truncate">
                                                    {{ucfirst($vente->facture->total_ttc)}} FCFA
                                                </span>
                                            </td>
	                                        <td>
	                                            <div>
	                                                <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
	                                                    <i data-feather="more-vertical"></i>
	                                                </button>
	                                                <div class="dropdown-menu">
	                                                    <a href="{{route('facturation',$vente->id)}}">
	                                                        <i data-feather='archive'></i>
	                                                        <span class="ml-1">Detail transaction</span>
	                                                    </a>
                                                    </div>
	                                            </div>
	                                        </td>
	                                    </tr>
	                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($ventes->hasPages())
                        <nav class="justify-items-center mt-1 d-flex col-12 justify-content-center">
                            <div class="mx-auto row justify-content-center flex-fill d-md-none">
                                <div class="mr-1 d-md-none">
                                    <p class="small text-muted">
                                        {!! __('De') !!}
                                        <span class="fw-semibold">{{ $ventes->firstItem() }}</span>
                                        {!! __('à') !!}
                                        <span class="fw-semibold">{{ $ventes->lastItem() }}</span>
                                        {!! __('sur') !!}
                                        <span class="fw-semibold">{{ $ventes->total() }}</span>
                                        {!! __('resultats') !!}
                                    </p>
                                </div>
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($ventes->onFirstPage())
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link">Précédent</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $ventes->currentPage()-1 }})" rel="prev">Suivant</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($ventes->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre({{ $ventes->currentPage()+1 }})" rel="next">@lang('pagination.next')</a>
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
                                        <span class="fw-semibold">{{ $ventes->firstItem() }}</span>
                                        {!! __('à') !!}
                                        <span class="fw-semibold">{{ $ventes->lastItem() }}</span>
                                        {!! __('sur') !!}
                                        <span class="fw-semibold">{{ $ventes->total() }}</span>
                                        {!! __('resultats') !!}
                                    </p>
                                </div>

                                <div>
                                    <ul class="pagination">
                                        {{-- Previous Page Link --}}
                                        @if ($ventes->onFirstPage())
                                            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                                <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre({{ $ventes->currentPage()-1 }})" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($ventes->links()->elements as $element)
                                            {{-- "Three Dots" Separator --}}
                                            @if (is_string($element))
                                                <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                                            @endif

                                            {{-- Array Of Links --}}
                                            @if (is_array($element))
                                                @foreach ($element as $page => $url)
                                                    @if ($page == $ventes->currentPage())
                                                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                                    @else
                                                        <li class="page-item"><a class="page-link" onclick="mettre({{ $page }})">{{ $page }}</a></li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($ventes->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre({{ $ventes->currentPage()-1 }})" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
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
                <button type="button" class="m-0 close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mx-auto col-10" id="info_description">
                        Une ligne D'ajout de stock s'affichera ici
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center justify-content-md-apt-1">
                <button data-dismiss="modal" class="px-2 mx-auto btn btn-success btn-gradient-success">
                    <i data-feather='check' style="width: 25px;height: 25px;"></i>
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

    $(document).ready(function() {
    function fetchTransactions() {
        var formData = $('#search').serialize();
        $.ajax({
            url: "{{ route('vente.search') }}",
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

    // Optionnel : déclencher une recherche au chargement de la page
    fetchTransactions();
});

    document.getElementById('venteListe').classList.add('active');
	</script>
@endsection
