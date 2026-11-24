<!DOCTYPE html>
<html class="loading" lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1, minimal-ui">
    <title>Connexion Farm App</title>

    <link rel="apple-touch-icon" href="{{ logo() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ logo() }}">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    {{-- Vendors CSS --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}">

    {{-- Theme CSS (Bootstrap 4 / Vuexy) --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/components.css') }}">

    {{-- Page CSS (auth) --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/form-validation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/page-auth.css') }}">

    <style>
        .auth-tabs {
            display: flex;
            border: 1px solid #ebe9f1;
            border-radius: .5rem;
            padding: 4px;
            background: #f8f8fb;
            margin-bottom: 1rem;
        }
        .auth-tabs .nav-item {
            flex: 1;
            margin: 0;
        }
        .auth-tabs .nav-link {
            border-radius: .4rem !important;
            text-align: center;
            color: #6e6b7b;
            font-weight: 600;
            font-size: .9rem;
            padding: .55rem .5rem;
            transition: all .18s ease;
        }
        .auth-tabs .nav-link.active {
            background: #7367f0 !important;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(115, 103, 240, .3);
        }
        .auth-tabs .nav-link:hover:not(.active) {
            background: #ecebf7;
        }
        .auth-pane {
            text-align: center;
            padding: .5rem 0;
        }
        .auth-pane .pane-icon {
            font-size: 2.4rem;
            color: #7367f0;
        }
        .auth-pane .pane-title {
            font-weight: 600;
            color: #5e5873;
            margin-bottom: .25rem;
        }
        .auth-pane .pane-text {
            color: #6e6b7b;
            font-size: .88rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="px-2 auth-wrapper auth-v1">
                    <div class="py-2 auth-inner">
                        <!-- Login v1 -->
                        <div class="mb-0 card">
                            <div class="card-body">
                                <a href="javascript:void(0);" class="brand-logo">
                                    <img src="{{logo()}}" alt="Logo" class="mx-2" style="width: 98px !important;max-width: 120px !important; height: 80px;" >
                                </a>
                                <h4 class="mb-1 text-center card-title" style="color:black;">Bienvenue Sur Farm app 👋</h4>
                                <p class="mb-2 text-center card-text" style="color:black;">Choisissez une option pour continuer</p>

                                {{-- Onglets : Démo / Connexion / Inscription --}}
                                <ul class="nav nav-pills auth-tabs" id="auth-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tab-demo" data-toggle="pill" href="#pane-demo" role="tab">Démo</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-login" data-toggle="pill" href="#pane-login" role="tab">Connexion</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-register" data-toggle="pill" href="#pane-register" role="tab">Inscription</a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    {{-- Démo (par défaut) --}}
                                    <div class="tab-pane fade show active" id="pane-demo" role="tabpanel">
                                        <div class="auth-pane">
                                            <div class="pane-icon"><i data-feather="play-circle"></i></div>
                                            <div class="pane-title">Découvrir la démo</div>
                                            <p class="pane-text">Explorez l'application avec des données de démonstration, sans créer de compte.</p>
                                            <a href="{{ route('demo.login') }}" class="btn btn-success btn-block">
                                                <i data-feather="play" class="mr-50"></i> Voir la démo
                                            </a>
                                            @if(request()->cookie('farmapp_demo_access') || request()->cookie('farmapp_demo_recovery'))
                                                <a href="{{ route('demo.subscribe') }}" class="btn btn-outline-primary btn-block mt-1">
                                                    Voir les forfaits
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Connexion --}}
                                    <div class="tab-pane fade" id="pane-login" role="tabpanel">
                                        <form class="mt-1 auth-login-form" method="POST" action="{{ route('login') }}">
                                            @csrf
                                            <div class="form-group">
                                                <label for="login-email" class="text-black form-label" style="color:black;">E-mail</label>
                                                <input id="login-email" aria-describedby="login-email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="farmapp@example.com" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <div class="d-flex justify-content-between" >
                                                    <label for="login-password" class="text-black" style="color:black;">Mot de Passe</label>
                                                    @if (Route::has('password.request'))
                                                        <a class="text-black btn btn-link" href="{{ route('password.request') }}">
                                                            <small>{{ __('Mot de passe Oublié?') }}</small>
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="input-group input-group-merge form-password-toggle">
                                                    <input id="login-password" type="password" tabindex="2" class="form-control-merge form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="login-password">

                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="input-group-append">
                                                        <span class="cursor-pointer input-group-text"><i data-feather="eye"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                    <label class="text-black form-check-label" for="remember" style="color:black;">
                                                        {{ __('Se souvenir') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <button class="text-black btn btn-primary btn-block" tabindex="4">{{ __('Se Connecter') }}</button>
                                        </form>
                                    </div>

                                    {{-- Inscription --}}
                                    <div class="tab-pane fade" id="pane-register" role="tabpanel">
                                        <div class="auth-pane">
                                            <div class="pane-icon"><i data-feather="user-plus"></i></div>
                                            <div class="pane-title">Créer votre entreprise</div>
                                            <p class="pane-text">Inscrivez-vous pour créer votre espace et gérer votre exploitation.</p>
                                            <a href="{{ route('register') }}" class="btn btn-primary btn-block">
                                                <i data-feather="arrow-right" class="mr-50"></i> Créer un compte
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Login v1 -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/pages/page-auth-login.js') }}"></script>

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
</body>
<!-- END: Body-->

</html>
