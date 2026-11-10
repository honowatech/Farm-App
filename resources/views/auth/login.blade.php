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
                                <p class="mb-2 text-center card-text" style="color:black;">Connectez vous pour accèder à l'application</p>

                                <form class="mt-2 auth-login-form" method="POST" action="{{ route('login') }}">
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
                                    <button class="text-black btn btn-secondary btn-gradient-secondary btn-block" tabindex="4">{{ __('Se Connecter') }}</button>
                                </form>
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
