@extends('layouts.app')

@section('title')
Infos sur {{ucfirst($matierePremiere->nom_produit)}}
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
                        <h2 class="float-left mb-0 content-header-title">Infos sur {{ucfirst($matierePremiere->nom_produit)}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('matiere_premiere.index')}}">Liste des Matiere premiere</a>
                            </li>
                            <li class="breadcrumb-item active">Infos sur {{ucfirst($matierePremiere->nom_produit)}}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="py-1 card">
            <div class="content-body">
                <!-- app e-commerce details start -->
                <section class="app-ecommerce-details">
                        <!-- Product Details starts -->
                        <div class="card-body">
                            <div class="my-2 row">
                                <div class="mb-2 col-12 col-md-5 d-flex align-items-center justify-content-center mb-md-0">
                                    <div class="col-10 d-flex align-items-center justify-content-center d-none">
                                        <i data-feather='pie-chart' class="w-50 h-50"></i>
                                    </div>
                                </div>
                                <div class="mx-auto col-12 col-md-7">
                                    <h1>
                                        {{ucfirst($matierePremiere->nom_produit)}}
                                    </h1>
                                    <span class="card-text item-company">
                                       {{$matierePremiere->infos->count()}}
                                         Fournisseur(s) Enregistré
                                    </span>
                                    <div class="mt-1 card-text">
                                        <span class="badge badge-pill badge-glow @if($matierePremiere->statut == 0) badge-danger @else badge-success @endif">
                                        @if($matierePremiere->statut == 0)
                                            Désactiver
                                        @else
                                            Activer
                                        @endif
                                        </span>
                                        <span class="px-1">-</span>
                                        <span class="text-{{StockMatierepremiere($matierePremiere)['valeur_en_stock'] > 0 ? 'success' : 'danger'}} font-weight-bolder">
                                            {{StockMatierepremiere($matierePremiere)['statut_stock']}} ({{StockMatierepremiere($matierePremiere)['valeur_en_stock']}})
                                        </span></p>
                                        <hr>
                                        <h5>Description:</h5>
                                        <p class="card-text">
                                        {{$matierePremiere->description}}....
                                        <hr>
                                        <h5>Prix:</h5>
                                        {{$matierePremiere->prix_unitaire}} FCFA<hr/>
                                        <div class="pt-1 row">
                                            <form action="{{route('stock_produit.index')}}" method="GET">
                                                @csrf
                                                <input type="hidden" name="matiere_premiere" value="{{$matierePremiere->id}}" title="Fonctionnalité pas encore disponible">
                                            <button type="submit" class="mr-1 btn btn-warning" disabled>
                                                <i data-feather='archive' class="mr-50"></i>
                                                <span>Gérer le stock</span>
                                            </button>
                                            </form>
                                             <a href="{{route('matiere_premiere.edit',$matierePremiere->id)}}" title="Modifier" type="button" class="mr-1 btn btn-icon btn-secondary waves-effect">
                                                <i data-feather='edit-2'></i>
                                            </a>
                                            <button @if($matierePremiere->statut == 1) title="Désactiver" @else title="Activer" @endif class="btn btn-icon mr-1 waves-effect mr-1 @if($matierePremiere->statut == 1) btn-danger @else btn-success @endif " onclick="actions('form_active', @if($matierePremiere->statut == 1) 'Désactiver' @else 'Activer' @endif ,'{{$matierePremiere->nom_produit}}')">
                                                @if($matierePremiere->statut == 1)
                                                    <i data-feather='shield-off'></i>
                                                @else
                                                    <i data-feather='shield'></i>
                                                @endif
                                                <form id="form_active" action="{{route('matiere_premiere.destroy',$matierePremiere->id)}}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Product Details ends -->
                </section>
                <!-- app e-commerce details end -->
            </div>
    </div>
</div>
</div>
</div>

@endsection
@section('javascript')
    <script src="{{asset('app-assets/vendors/js/ui/jquery.sticky.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/extensions/swiper.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/extensions/toastr.min.js')}}"></script>

    <script src="{{asset('app-assets/js/scripts/pages/app-ecommerce-details.js')}}"></script>
    <script src="{{asset('app-assets/js/scripts/forms/form-number-input.js')}}"></script>
    <script type="text/javascript">
        function mettre(number_page){
           document.querySelector('#page').value = number_page;
           document.querySelector('#search').submit()
        }
    </script>
@endsection
