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
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/sweetalert2.min.css') }}">

    <style>
        .step-pane { display: none; }
        .step-pane.active { display: block; }

        .plan-card {
            border: 2px solid #e4e6ea;
            border-radius: 12px;
            padding: 16px;
            cursor: pointer;
            transition: all .15s ease;
            height: 100%;
            background: #fff;
        }
        .plan-card:hover { border-color: #b9b9f0; }
        .plan-card.selected { border-color: #7367f0; box-shadow: 0 8px 18px rgba(115,103,240,.18); }
        .plan-card .plan-price { font-size: 1.5rem; font-weight: 600; color: #5e5873; }
        .plan-card .plan-price small { font-size: .85rem; font-weight: 500; }
        .plan-features { padding-left: 1rem; margin: .5rem 0 0; list-style: none; font-size: .85rem; color: #6e6b7b; }
        .plan-features li { margin-bottom: .25rem; }
        .plan-features svg { width: 14px; height: 14px; color: #28c76f; margin-right: .4rem; }

        .step-indicator { display: flex; justify-content: center; margin-bottom: 1.25rem; }
        .step-indicator .dot {
            width: 34px; height: 34px; border-radius: 50%;
            background: #e4e6ea; color: #6e6b7b; font-weight: 600;
            display: flex; align-items: center; justify-content: center; margin: 0 8px;
        }
        .step-indicator .dot.active { background: #7367f0; color: #fff; }
        .step-indicator .bar { width: 32px; height: 3px; background: #e4e6ea; align-self: center; border-radius: 2px; }
        .step-indicator .bar.done { background: #7367f0; }

        /* Élargir la carte d'inscription pour afficher 3 forfaits sur une ligne (PC). */
        .register-auth-inner {
            max-width: 960px !important;
            width: 100% !important;
        }
    </style>
</head>
<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row"></div>
            <div class="content-body">
                <div class="px-2 auth-wrapper auth-v1">
                    <div class="py-2 auth-inner register-auth-inner">
                        <div class="mb-0 card">
                            <div class="card-body">
                                <a href="javascript:void(0);" class="brand-logo">
                                    <img src="{{ logo() }}" alt="Logo" class="mx-2" style="width: 98px !important;max-width: 120px !important; height: 80px;">
                                </a>
                                <h4 class="mb-1 text-center card-title" style="color:black;">Créer votre entreprise 👋</h4>
                                <p class="mb-2 text-center card-text" style="color:black;">Choisissez un forfait, créez votre compte et démarrez.</p>
                                @if($fromDemo)
                                    <div class="alert alert-info" role="status">
                                        Choisissez un forfait pour créer votre espace FarmApp.
                                    </div>
                                @endif

                                <div class="step-indicator">
                                    <div class="dot active" id="dot-1">1</div>
                                    <div class="bar" id="bar-1"></div>
                                    <div class="dot" id="dot-2">2</div>
                                    <div class="bar" id="bar-2"></div>
                                    <div class="dot" id="dot-3">3</div>
                                </div>

                                <form class="mt-2 auth-login-form" id="register-form" method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id" id="plan_id" value="{{ old('plan_id') }}">
                                    <input type="hidden" name="billing_mode" id="billing_mode" value="">
                                    <input type="hidden" name="payment_transaction_id" id="payment_transaction_id" value="">
                                    <input type="hidden" name="payment_registration_token" id="payment_registration_token" value="">
                                    {{-- Étape 1 : choix du forfait --}}
                                    <div class="step-pane active" id="step-1">
                                        <div class="row">
                                            @forelse ($plans as $plan)
                                                <div class="col-12 col-md-4 mb-1">
                                                    <div class="plan-card" data-plan-id="{{ $plan->id }}">
                                                        <h5 class="mb-50" style="color:black;">{{ $plan->name }}</h5>
                                                        <div class="plan-price">
                                                            {{ number_format((int) round((float) $plan->price), 0, ',', ' ') }} <small>FCFA</small>
                                                            <span class="text-muted" style="font-size:.8rem;">/ {{ $plan->billing_interval === 'annually' ? 'an' : 'mois' }}</span>
                                                        </div>
                                                        @if ((int) $plan->trial_days > 0)
                                                            <span class="badge badge-success">{{ $plan->trial_days }} jours d'essai gratuit</span>
                                                        @elseif ((int) round((float) $plan->price) <= 0)
                                                            <span class="badge badge-primary">Gratuit</span>
                                                        @endif
                                                        @if ($plan->description)
                                                            <p class="mb-0 mt-50 text-muted" style="font-size:.85rem;">{{ $plan->description }}</p>
                                                        @endif
                                                        @php
                                                            $enabledFeatures = collect($plan->features)->filter(fn ($v) => $v === true)->keys();
                                                        @endphp
                                                        @if ($enabledFeatures->isNotEmpty())
                                                            <ul class="plan-features">
                                                                @foreach ($enabledFeatures as $featureKey)
                                                                    <li><i data-feather="check"></i>{{ config('features.features.'.$featureKey.'.label', $featureKey) }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12 text-center text-muted">Aucun forfait disponible pour le moment.</div>
                                            @endforelse
                                        </div>
                                        <button type="button" class="btn btn-primary btn-block mt-1" id="btn-step-1">Continuer</button>
                                    </div>

                                    {{-- Étape 2 : informations du compte --}}
                                    <div class="step-pane" id="step-2">
                                        <div class="form-group">
                                            <label for="company_name" class="text-black form-label">Nom de l'entreprise</label>
                                            <input id="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}" placeholder="Ferme Avicole du Littoral" required autofocus>
                                            @error('company_name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="text-black form-label">Votre nom</label>
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Votre nom complet" required>
                                            @error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="email" class="text-black form-label">E-mail</label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="vous@exemple.com" required autocomplete="email">
                                            @error('email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="password" class="text-black form-label">Mot de passe</label>
                                            <div class="input-group input-group-merge form-password-toggle">
                                                <input id="password" type="password" class="form-control-merge form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="8 caractères minimum">
                                                <div class="input-group-append"><span class="cursor-pointer input-group-text"><i data-feather="eye"></i></span></div>
                                            </div>
                                            @error('password')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="password_confirmation" class="text-black form-label">Confirmer le mot de passe</label>
                                            <div class="input-group input-group-merge form-password-toggle">
                                                <input id="password_confirmation" type="password" class="form-control-merge form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmer">
                                                <div class="input-group-append"><span class="cursor-pointer input-group-text"><i data-feather="eye"></i></span></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6"><button type="button" class="btn btn-outline-secondary btn-block" id="btn-back-2">Retour</button></div>
                                            <div class="col-6"><button type="button" class="btn btn-primary btn-block" id="btn-step-2">Continuer</button></div>
                                        </div>
                                    </div>

                                    {{-- Étape 3 : récapitulatif + essai / paiement --}}
                                    <div class="step-pane" id="step-3">
                                        <div class="card border mb-1">
                                            <div class="card-body py-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <div class="text-muted" style="font-size:.8rem;">Forfait choisi</div>
                                                        <div class="font-weight-bold" id="summary-plan">—</div>
                                                    </div>
                                                    <div class="text-right">
                                                        <div class="text-muted" style="font-size:.8rem;">Prix</div>
                                                        <div class="font-weight-bold" id="summary-price">—</div>
                                                    </div>
                                                </div>
                                                <div id="summary-trial" class="mt-50"></div>
                                            </div>
                                        </div>

                                        {{-- Bloc essai --}}
                                        <div id="trial-block" style="display:none;">
                                            <button type="button" class="btn btn-success btn-block" id="btn-trial">Démarrer l'essai gratuit</button>
                                            <div class="text-center text-muted my-50" style="font-size:.85rem;">ou</div>
                                        </div>

                                        {{-- Bloc paiement --}}
                                        <div id="payment-block">
                                            <div class="form-group">
                                                <label for="phone_number" class="text-black form-label">Numéro Mobile Money</label>
                                                <input id="phone_number" type="tel" inputmode="numeric" class="form-control" placeholder="Ex : 690000000 ou 237690000000">
                                                <small class="text-muted">Vous recevrez un prompt USSD (MTN / Orange Money) pour valider.</small>
                                            </div>
                                            <button type="button" class="btn btn-primary btn-block" id="btn-pay">
                                                <i data-feather="smartphone" class="mr-50"></i> Payer maintenant
                                            </button>
                                        </div>

                                        <div id="payment-status" class="mt-1 text-center" style="display:none;"></div>

                                        <div class="row mt-1">
                                            <div class="col-12"><button type="button" class="btn btn-outline-secondary btn-block" id="btn-back-3">Retour</button></div>
                                        </div>
                                    </div>
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
    <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script>
        var plans = @json($plansData);

        var selectedPlan = null;
        var pollingTimer = null;

        var ETATS = {
            'pending':    { color: 'primary', titre: 'En attente de validation', detail: 'Validez la transaction sur votre téléphone…' },
            'successful': { color: 'success', titre: 'Paiement réussi', detail: 'Votre entreprise est en cours de création.' },
            'failed':     { color: 'danger', titre: 'Paiement échoué', detail: 'La transaction a échoué. Réessayez.' },
            'cancelled':  { color: 'warning', titre: 'Paiement annulé', detail: 'Vous avez annulé la transaction.' },
            'expired':    { color: 'warning', titre: 'Paiement expiré', detail: 'Le délai de validation est dépassé.' }
        };

        function goTo(step) {
            $('.step-pane').removeClass('active');
            $('#step-' + step).addClass('active');
            $('#dot-1, #dot-2, #dot-3').removeClass('active');
            for (var i = 1; i <= step; i++) { $('#dot-' + i).addClass('active'); }
            $('#bar-1, #bar-2').removeClass('done');
            if (step >= 2) { $('#bar-1').addClass('done'); }
            if (step >= 3) { $('#bar-2').addClass('done'); }
        }

        function findPlan(id) {
            for (var i = 0; i < plans.length; i++) { if (plans[i].id == id) { return plans[i]; } }
            return null;
        }

        function canTrial() {
            return selectedPlan && (selectedPlan.price <= 0 || selectedPlan.trial_days > 0);
        }

        function renderSummary() {
            $('#summary-plan').text(selectedPlan.name);
            $('#summary-price').text(Number(selectedPlan.price).toLocaleString('fr-FR') + ' FCFA');
            $('#summary-trial').html(selectedPlan.trial_days > 0
                ? '<span class="badge badge-success">' + selectedPlan.trial_days + ' jours d\'essai gratuit</span>'
                : '');

            $('#trial-block').toggle(selectedPlan.trial_days > 0 && selectedPlan.price > 0);
            if (selectedPlan.price <= 0) {
                $('#btn-trial').text('Créer mon entreprise');
                $('#trial-block').show();
                $('#payment-block').hide();
            } else if (selectedPlan.trial_days > 0) {
                $('#btn-trial').text('Démarrer l\'essai gratuit (' + selectedPlan.trial_days + ' j)');
                $('#trial-block').show();
                $('#payment-block').show();
            } else {
                $('#trial-block').hide();
                $('#payment-block').show();
            }
        }

        function showPaymentStatus(statut, extra) {
            var etat = ETATS[statut] || { color: 'secondary', titre: 'Statut inconnu', detail: statut };
            $('#payment-status').show().html(
                '<div class="text-' + etat.color + '">'
                + '<h4 class="mb-0">' + etat.titre + '</h4>'
                + '<p class="mb-0">' + etat.detail + '</p>'
                + (extra ? '<p class="small text-muted mt-50">' + extra + '</p>' : '')
                + '</div>'
            );
        }

        function startPayment() {
            var phone = $('#phone_number').val().trim();
            if (!phone) {
                toastr.error('Veuillez renseigner le numéro Mobile Money.', 'Champ requis');
                return;
            }

            if (pollingTimer) { clearInterval(pollingTimer); pollingTimer = null; }

            var btn = $('#btn-pay');
            btn.prop('disabled', true);
            $('#payment-status').show().html('<div class="text-center text-primary"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-50 mb-0">Initialisation du paiement…</p></div>');

            $.post('{{ route("register.pay.init") }}', {
                _token: '{{ csrf_token() }}',
                plan_id: selectedPlan.id,
                phone_number: phone,
                client_name: $('#name').val() || null
            }, function (res) {
                btn.prop('disabled', false);
                if (res.ok && res.transaction_id) {
                    var extra = 'Réf. ' + res.external_reference + (res.provider_slug ? ' · ' + res.provider_slug : '');
                    showPaymentStatus('pending', extra);
                    pollPayment(res.transaction_id, res.registration_token);
                } else {
                    showPaymentStatus('failed', res.message || 'Erreur lors de l\'initiation.');
                }
            }).fail(function (xhr) {
                btn.prop('disabled', false);
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Erreur serveur.';
                showPaymentStatus('failed', msg);
            });
        }

        function pollPayment(transactionId, registrationToken) {
            pollingTimer = setInterval(function () {
                $.post('{{ url("/register/pay/status") }}/' + transactionId, {
                    _token: '{{ csrf_token() }}',
                    registration_token: registrationToken
                }, function (res) {
                    if (res.final) {
                        clearInterval(pollingTimer);
                        pollingTimer = null;
                        if (res.payment_status === 'successful') {
                            showPaymentStatus('successful');
                            $('#billing_mode').val('payment');
                            $('#payment_transaction_id').val(transactionId);
                            $('#payment_registration_token').val(registrationToken);
                            setTimeout(function () { $('#register-form').submit(); }, 1200);
                        } else {
                            var extra = 'Réf. ' + (res.external_reference || '');
                            showPaymentStatus(res.payment_status, extra);
                        }
                    } else {
                        showPaymentStatus('pending');
                    }
                }).fail(function () {
                    // On continue le polling tant que le serveur répond mal.
                });
            }, 4000);
        }

        $(function () {
            if (window.feather) { feather.replace({ width: 14, height: 14 }); }

            // Sélection d'un forfait
            $(document).on('click', '.plan-card', function () {
                $('.plan-card').removeClass('selected');
                $(this).addClass('selected');
                var id = $(this).data('plan-id');
                selectedPlan = findPlan(id);
                $('#plan_id').val(id);
            });

            $('#btn-step-1').on('click', function () {
                if (!selectedPlan) {
                    toastr.warning('Veuillez choisir un forfait.', 'Sélection requise');
                    return;
                }
                goTo(2);
            });

            $('#btn-step-2').on('click', function () {
                var ok = true;
                ['company_name', 'name', 'email', 'password', 'password_confirmation'].forEach(function (f) {
                    var el = document.getElementById(f);
                    if (el && !el.value.trim()) { ok = false; el.classList.add('is-invalid'); }
                });
                if (!ok) {
                    toastr.error('Veuillez remplir tous les champs.', 'Champs requis');
                    return;
                }
                if ($('#password').val() !== $('#password_confirmation').val()) {
                    toastr.error('Les mots de passe ne correspondent pas.', 'Erreur');
                    return;
                }
                renderSummary();
                goTo(3);
            });

            $('#btn-back-2').on('click', function () { goTo(1); });
            $('#btn-back-3').on('click', function () { goTo(2); });

            $('#btn-trial').on('click', function () {
                $('#billing_mode').val('trial');
                $('#payment_transaction_id').val('');
                $('#payment_registration_token').val('');
                $('#register-form').submit();
            });

            $('#btn-pay').on('click', function () { startPayment(); });
        });
    </script>
</body>
</html>
