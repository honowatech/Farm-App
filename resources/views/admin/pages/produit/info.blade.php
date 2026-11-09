@extends('admin/templates/template')

@section('title')
Infos sur {{ucfirst($produit->nom)}}
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
                        <h2 class="float-left mb-0 content-header-title">Infos sur {{ucfirst($produit->nom)}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('produit.index')}}">Liste des produits</a>
                            </li>
                            <li class="breadcrumb-item active">Infos sur {{ucfirst($produit->nom)}}
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
                                        <i data-feather='shopping-bag' class="w-50 h-50"></i>
                                    </div>
                                </div>
                                <div class="mx-auto col-12 col-md-7">
                                    <h1>{{ucfirst($produit->nom)}}</h1>
                                    <span class="card-text item-company">
                                        Produit par
                                        <a href="{{route('home')}}" class="company-name">Farm App</a>
                                    </span>
                                    <div class="mt-1 card-text">
                                        <span class="badge badge-pill badge-glow @if($produit->statut == 0) badge-danger @else badge-success @endif">
                                        @if($produit->statut == 0)
                                            Invisible
                                        @else
                                            Visible
                                        @endif
                                        </span>
                                        <span class="px-1">-</span>
                                        <span class="text-{{Stockproduit($produit)['valeur_en_stock'] > 0 ? 'success' : 'danger'}} font-weight-bolder">
                                            {{Stockproduit($produit)['statut_stock']}} ({{Stockproduit($produit)['valeur_en_stock']}})
                                        </span></p>
                                        <hr>
                                        <h5>Description:</h5>
                                        <p class="card-text">
                                        {{$produit->description}}....
                                    </div>
                                    <hr/>
                                    <div class="product-color-options">
                                        <h5>Variétés</h5>
                                    @if($produit->derives->count() != 0)
                                        <ul class="mb-0 list-unstyled">
                                        @foreach ($produit->derives as $derive)
                                            <li class="d-inline-block selected">
                                                <div class="color-option b-primary">
                                                    <div class="filloption">
                                                        <a href="{{route('derive_produit.show',$derive->id)}}" style="text-decoration: underline;">
                                                            {{$derive->nom}} </a> <b>,</b>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                        </ul>
                                    @else
                                        <i data-feather='slash' class="text-danger"></i>
                                    @endif
                                    </div>
                                    <hr/>
                                    <div class="pt-1 row">
                                        <form action="{{route('derive_produit.create')}}" method="GET">
                                            @csrf
                                            <input type="hidden" name="produit" value="{{$produit->id}}">
                                        <button type="submit" class="mr-1 btn btn-secondary">
                                            <i data-feather='share-2' class="mr-50"></i>
                                            <span>Ajouter des dérivées</span>
                                        </button>
                                        </form>
                                        <a href="{{route('produit.edit',$produit->id)}}" title="Modifier" type="button" class="mr-1 btn btn-icon btn-secondary waves-effect">
                                            <i data-feather='edit-2'></i>
                                        </a>
                                        <button @if($produit->statut == 1) title="Rendre Invisible" @else title="Rendre Visible" @endif class="btn btn-icon mr-1 waves-effect @if($produit->statut == 1) btn-danger @else btn-success @endif " onclick="actions('form_active_{{$produit->id}}', @if($produit->statut == 1) 'Rendre Invisible' @else 'Rendre Visible' @endif ,'{{$produit->nom}}')">
                                            @if($produit->statut == 1)
                                                <i data-feather='eye-off'></i>
                                            @else
                                                <i data-feather='eye'></i>
                                            @endif
                                            <form id="form_active_{{$produit->id}}" action="{{route('produit.destroy',$produit->id)}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </button>
                                    </div>
                                    @if($produit->derives->count() == 0)
                                        <span class="text-danger">Attention !</span> <small class="text-muted">Enregistrer au moins une dérivée pour gérer les stocks</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- Product Details ends -->
                </section>
                <!-- app e-commerce details end -->
            </div>
    </div>
    <div class="py-1 card">
            <div class="content-body">
                <section>
                        <!-- Related Products starts -->
                        <div class="card-body">
                            <div class="mt-2 text-center">
                                <h4>Produits Dérivés</h4>
                                <p class="card-text">
                                @if($produit->derives->count() == 0)
                                    <i class="text-danger" data-feather='alert-circle'></i> Aucune information disponible...
                                @else
                                    Ce sont les variantes de {{ucfirst($produit->nom)}}
                                @endif

                                </p>
                            </div>
                        @if($produit->derives->count() != 0)
                            <div class="px-4 py-2 swiper-responsive-breakpoints swiper-container">
                                <div class="swiper-wrapper">
                                    @foreach($produit->derives as $derivee)
                                    <div class="py-1 swiper-slide">
                                        <a href="{{route('derive_produit.show',$derivee->id)}}">
                                            <div class="text-center item-heading">
                                                <h4 class="mb-0 text-truncate">
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
                                            <div class="mx-auto text-center img-container w-50 py-50">
                                                <i data-feather='bookmark' class="w-50 h-25 text-secondary"></i>
                                            </div>
                                            <div class="mt-1 text-center item-meta">
                                                <span class="badge badge-pill badge-glow @if($derivee->statut == 0) badge-danger @else badge-success @endif">
                                                @if($derivee->statut == 0)
                                                    Invisible
                                                @else
                                                    Visible
                                                @endif
                                                </span>
                                                <h5 class="m-1 card-text text-dark">{{$derivee->prix_unitaire}} FCFA</h5>
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
