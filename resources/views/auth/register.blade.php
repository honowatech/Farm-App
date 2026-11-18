<!DOCTYPE html>
<html class="loading" lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-textdirection="ltr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1, minimal-ui">
    <title>Créer votre entreprise — Farm App</title>

    <link rel="apple-touch-icon" href="{{ logo() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ logo() }}">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/page-auth.css') }}">
</head>
<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row"></div>
            <div class="content-body">
                <div class="px-2 auth-wrapper auth-v1">
                    <div class="py-2 auth-inner">
                        <div class="mb-0 card">
                            <div class="card-body">
                                <a href="javascript:void(0);" class="brand-logo">
                                    <img src="{{ logo() }}" alt="Logo" class="mx-2" style="width: 98px !important;max-width: 120px !important; height: 80px;">
                                </a>
                                <h4 class="mb-1 text-center card-title" style="color:black;">Créer votre entreprise 👋</h4>
                                <p class="mb-2 text-center card-text" style="color:black;">Inscrivez-vous et devenez administrateur de votre espace</p>

                                <form class="mt-2 auth-login-form" method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label for="company_name" class="text-black form-label">Nom de l'entreprise</label>
                                        <input id="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}" placeholder="Ferme Avicole du Littoral" required autofocus>
                                        @error('company_name')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="text-black form-label">Votre nom</label>
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Votre nom complet" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="text-black form-label">E-mail</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="vous@exemple.com" required autocomplete="email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="text-black form-label">Mot de passe</label>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input id="password" type="password" class="form-control-merge form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="8 caractères minimum">
                                            <div class="input-group-append">
                                                <span class="cursor-pointer input-group-text"><i data-feather="eye"></i></span>
                                            </div>
                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation" class="text-black form-label">Confirmer le mot de passe</label>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input id="password_confirmation" type="password" class="form-control-merge form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmer">
                                            <div class="input-group-append">
                                                <span class="cursor-pointer input-group-text"><i data-feather="eye"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <button class="btn btn-success btn-block" tabindex="4">Créer mon entreprise</button>
                                </form>

                                <p class="mt-2 text-center">
                                    <span style="color:black;">Déjà un compte ?</span>
                                    <a href="{{ route('login') }}">Se connecter</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app.js') }}"></script>
    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({ width: 14, height: 14 });
            }
        });
    </script>
</body>
</html>
