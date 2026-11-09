@extends('admin/templates/template')

@section('title')
Informations
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
                        <h2 class="float-left mb-0 content-header-title">Informations</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('ramassage.index')}}">Liste des Ramassages</a>
                            </li>
                            <li class="breadcrumb-item active">Informations
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="py-1 card">
            <div class="content-body">
                <section>
                        <!-- Détails sur le ramassage -->
                        <div class="card-body">
                            <div class="">
                                <h4 class='text-center text-danger'> Attention !</h4><div class='text-center w-100'>Pas d'informations disponibles pour le moment...</div>
                        </div>
                        <!-- Détails sur le ramassage ends -->
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
        document.querySelector('#RamassageListe').classList.add('active');
    </script>
@endsection
