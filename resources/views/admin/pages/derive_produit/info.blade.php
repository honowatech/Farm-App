@extends('layouts.app')

@section('title')
Infos sur {{ucfirst($derive_produit->nom)}}
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
                        <h2 class="content-header-title float-left mb-0">Infos sur {{ucfirst($derive_produit->nom)}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('produit.index')}}">Liste des produits</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('produit.show',$produit->id)}}">{{ucfirst($produit->nom)}}</a>
                            </li>
                            <li class="breadcrumb-item active">Infos sur {{ucfirst($derive_produit->nom)}}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card py-1">
            <div class="content-body"> 
                <!-- app e-commerce details start -->
                <section class="app-ecommerce-details">
                        <!-- Product Details starts -->
                        <div class="card-body">
                            <div class="row my-2">
                                <div class="col-12 col-md-5 d-flex align-items-center justify-content-center mb-2 mb-md-0">
                                    <div class="col-10 d-flex align-items-center justify-content-center d-none">
                                        <i data-feather='bookmark' class="w-50 h-50"></i>
                                    </div>
                                </div>
                                <div class="col-12 col-md-7 mx-auto">
                                    <h1>
                                        {{ucfirst($produit->nom)}}_{{ucfirst($derive_produit->nom)}}
                                    </h1>
                                    <span class="card-text item-company">
                                        derive_produit par 
                                        <a href="{{route('home')}}" class="company-name">Farm App</a>
                                    </span>
                                    <div class="mt-1 card-text">
                                        <span class="badge badge-pill badge-glow @if($derive_produit->statut == 0) badge-danger @else badge-success @endif">
                                        @if($derive_produit->statut == 0)
                                            Invisible
                                        @else 
                                            Visible
                                        @endif
                                        </span>
                                        <span class="px-1">-</span>
                                        <span class="text-{{StockDerive($derive_produit)['valeur_en_stock'] > 0 ? 'success' : 'danger'}} font-weight-bolder">
                                            {{StockDerive($derive_produit)['statut_stock']}} ({{StockDerive($derive_produit)['valeur_en_stock']}})
                                        </span></p>
                                        <hr>
                                        <h5>Infos:</h5>
                                        <p class="card-text">
                                        @if($derive_produit->min != $derive_produit->max)
                                            ( {{$derive_produit->min}} - {{$derive_produit->max}} )
                                        @else
                                            {{$derive_produit->min}}
                                        @endif
                                        {{$derive_produit->unite->abrege}}
                                        <hr>
                                        <h5>Prix:</h5>
                                        {{$derive_produit->prix_unitaire}} FCFA<hr/>
                                        <div class="pt-1 row">
                                            <form action="{{route('stock_produit.index')}}" method="GET">
                                                @csrf
                                                <input type="hidden" name="produit" value="{{$produit->id}}" title="Fonctionnalité pas encore disponible">
                                            <button type="submit" class="btn btn-warning mr-1" disabled>
                                                <i data-feather='archive' class="mr-50"></i>
                                                <span>Gérer le stock</span>
                                            </button>
                                            </form>
                                            <a href="{{route('derive_produit.edit',$derive_produit->id)}}" title="Modifier" type="button" class="btn btn-icon btn-secondary waves-effect mr-1">
                                                <i data-feather='edit-2'></i>
                                            </a>
                                        <button @if($derive_produit->statut == 1) title="Rendre Invisible" @else title="Rendre Visible" @endif class="btn btn-icon mr-1 waves-effect @if($derive_produit->statut == 1) btn-danger @else btn-success @endif " onclick="actions('form_active_{{$derive_produit->id}}', @if($derive_produit->statut == 1) 'Rendre Invisible' @else 'Rendre Visible' @endif ,'{{$derive_produit->produit->nom}}_{{$derive_produit->nom}}')">
                                            @if($derive_produit->statut == 1)
                                                <i data-feather='eye-off'></i>
                                            @else
                                                <i data-feather='eye'></i>
                                            @endif
                                            <form id="form_active_{{$derive_produit->id}}" action="{{route('derive_produit.destroy',$derive_produit->id)}}" method="POST">
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
    <div class="card py-1">
            <div class="content-body"> 
                <section>
                        <!-- Related Products starts -->
                        <div class="card-body">
                            <div class="mt-2  text-center">
                                <h4>Autre Dérivés</h4>
                                <p class="card-text">
                                @if($produit->derives->count() <= 1)
                                    <i class="text-danger" data-feather='alert-circle'></i> Aucune information disponible...
                                @else
                                    Ce sont les variantes du même produit ({{$produit->nom}})
                                @endif
                                </p>
                            </div>
                        @if($produit->derives->count() > 1)
                            <div class="swiper-responsive-breakpoints swiper-container px-4 py-2">
                                <div class="swiper-wrapper">
                                @foreach($produit->derives as $derivee)
                                @if($derivee->id == $derive_produit->id)
                                    @continue
                                @endif
                                    <div class="swiper-slide py-1 ">
                                        <a href="{{route('derive_produit.show',$derivee->id)}}">
                                            <div class="item-heading text-center">
                                                <h4 class="text-truncate mb-0"> 
                                                    {{ucfirst($produit->nom)}}_{{ucfirst($derivee->nom)}}
                                                </h4>
                                                <small class="text-body">
                                                    @if($derivee->min != $derivee->max)
                                                        ( {{$derivee->min}} - {{$derivee->max}} )
                                                    @else
                                                        {{$derivee->min}}
                                                    @endif
                                                    {{$derivee->unite->abrege}}
                                                </small>
                                                <span class="text-{{StockDerive($derivee)['valeur_en_stock'] > 0 ? 'success' : 'danger'}} font-weight-bolder">
                                                    {{StockDerive($derivee)['statut_stock']}} ({{StockDerive($derivee)['valeur_en_stock']}})
                                                </span>
                                            </div>
                                            <div class="img-container w-50 mx-auto py-50 text-center">
                                                <i data-feather='bookmark' class="w-50 h-25 text-secondary"></i>
                                            </div>
                                            <div class="item-meta text-center mt-1">
                                                <span class="badge badge-pill badge-glow @if($derivee->statut == 0) badge-danger @else badge-success @endif">
                                                @if($derivee->statut == 0)
                                                    Invisible
                                                @else 
                                                    Visible
                                                @endif
                                                </span>
                                                <h5 class="card-text text-dark m-1">{{$derivee->prix_unitaire}} FCFA</h5>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                                </div>
                                <!-- Add Arrows -->
                                <div class="swiper-button-next text-dark"></div>
                                <div class="swiper-button-prev text-dark"></div>
                            </div>
                        @endif
                        </div>
                        <!-- Related Products ends -->
                </section>
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
        document.querySelector('#ProduitListe').classList.add('active');
    </script>
@endsection
