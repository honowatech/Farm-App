@extends('layouts.app')

@section('title', 'Configuration MaMoNi')

@section('css')
<style>
    .fab-test-connexion {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #7367f0;
        color: #ffffff;
        border: none;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
        cursor: pointer;
        z-index: 1050;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        transition: transform 0.2s ease;
    }
    .fab-test-connexion:hover {
        transform: scale(1.08);
    }

    .panel-test-connexion {
        position: fixed;
        bottom: 92px;
        right: 24px;
        width: 320px;
        max-width: calc(100vw - 48px);
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.22);
        z-index: 1050;
        overflow: hidden;
        display: none;
        flex-direction: column;
    }

    .panel-test-connexion .panel-header {
        background: #7367f0;
        color: #ffffff;
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        font-size: 14px;
    }

    .panel-test-connexion .panel-close {
        background: transparent;
        border: none;
        color: #ffffff;
        font-size: 18px;
        cursor: pointer;
        line-height: 1;
    }

    .panel-test-connexion .panel-body {
        padding: 16px;
        background: #f5f6f8;
        min-height: 120px;
        max-height: 320px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    .panel-test-connexion .panel-footer {
        padding: 12px 16px;
        border-top: 1px solid #e5e7eb;
        background: #ffffff;
    }

    .msg {
        max-width: 85%;
        padding: 8px 12px;
        border-radius: 12px;
        margin-bottom: 8px;
        font-size: 13px;
        line-height: 1.4;
        word-wrap: break-word;
    }
    .msg.bot {
        background: #e9e9eb;
        color: #333333;
        align-self: flex-start;
        border-bottom-left-radius: 4px;
    }
    .msg.success {
        background: #28c76f;
        color: #ffffff;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
    }
    .msg.error {
        background: #ea5455;
        color: #ffffff;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
    }
</style>
@endsection

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Configuration MaMoNi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('paiement.index') }}">Paiements</a></li>
                                <li class="breadcrumb-item active">Configuration</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section id="multiple-column-form">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Paramètres de paiement MaMoNi</h3>
                            </div>
                            <div class="card-body">
                                <form class="form" action="{{ route('paiement.settings.update') }}" method="POST">
                                    @csrf
                                    <div class="mx-auto row col-lg-10">
                                        <div class="mx-auto col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="base_url">URL de base de l'API</label>
                                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                <div class="form-group">
                                                    <div class="input-group input-group-merge @error('base_url') is-invalid @enderror">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i data-feather="globe"></i></span>
                                                        </div>
                                                        <input type="url" id="base_url" class="form-control @error('base_url') is-invalid @enderror" name="base_url" value="{{ old('base_url', $config['base_url']) }}" placeholder="https://console-v2.mamonipay.me/api" required>
                                                    </div>
                                                    @error('base_url')
                                                    <small class="alert alert-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mx-auto col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="login">Login marchand</label>
                                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                <div class="form-group">
                                                    <div class="input-group input-group-merge @error('login') is-invalid @enderror">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i data-feather="user"></i></span>
                                                        </div>
                                                        <input type="text" id="login" class="form-control @error('login') is-invalid @enderror" name="login" value="{{ old('login', $config['login']) }}" placeholder="Login marchand" required>
                                                    </div>
                                                    @error('login')
                                                    <small class="alert alert-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mx-auto col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="password">Mot de passe</label>
                                                <span class="cursor-pointer text-info" title="Optionnel">Optionnel</span>
                                                <div class="form-group">
                                                    <div class="input-group input-group-merge">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i data-feather="lock"></i></span>
                                                        </div>
                                                        <input type="password" id="password" class="form-control" name="password" placeholder="Laisser vide pour ne pas changer" autocomplete="new-password">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mx-auto col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="channel">Canal</label>
                                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                <div class="form-group">
                                                    <div class="input-group input-group-merge @error('channel') is-invalid @enderror">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i data-feather="git-branch"></i></span>
                                                        </div>
                                                        <input type="text" id="channel" class="form-control @error('channel') is-invalid @enderror" name="channel" value="{{ old('channel', $config['channel']) }}" placeholder="Ex : SELLER_DIRECT" required>
                                                    </div>
                                                    <small class="text-muted">Le canal dépend du fournisseur. Ex. : <code>SELLER_DIRECT</code> pour MTN MoMo ; renseignez le canal Orange fourni par MaMoNi pour Orange Money.</small>
                                                    @error('channel')
                                                    <small class="alert alert-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mx-auto col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="enabled">Activer les paiements MaMoNi</label>
                                                <div class="form-group">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="enabled" name="enabled" {{ $config['enabled'] ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="enabled">Activé</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row col-12">
                                        <div class="mx-auto text-center d-none d-md-block col-lg-8 col-md-12 col-12">
                                            <a href="{{ route('paiement.index') }}" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-danger mt-md-2 mr-md-4">
                                                <i class="mr-1" data-feather="x"></i> Annuler
                                            </a>
                                            <button type="submit" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success mt-md-2">
                                                <i class="mr-1" data-feather="save"></i> Enregistrer
                                            </button>
                                        </div>
                                        <div class="mx-auto text-center d-block d-md-none col-lg-8 col-md-12 col-12">
                                            <button type="submit" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success mt-md-2 mr-md-2">
                                                <i class="mr-1" data-feather="save"></i> Enregistrer
                                            </button>
                                            <a href="{{ route('paiement.index') }}" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-danger mt-md-2">
                                                <i class="mr-1" data-feather="x"></i> Annuler
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

{{-- Bouton flottant + panneau de test de connexion --}}
<button type="button" class="fab-test-connexion" id="btn-test-connexion" title="Tester la connexion MaMoNi">
    <i class="fas fa-plug"></i>
</button>

<div class="panel-test-connexion" id="panel-test-connexion">
    <div class="panel-header">
        <span><i class="fas fa-plug mr-50"></i> Connexion MaMoNi</span>
        <button type="button" class="panel-close" id="btn-close-panel" aria-label="Fermer">&times;</button>
    </div>
    <div class="panel-body" id="panel-body">
        <div class="msg bot">Cliquez sur « Vérifier » pour tester la connexion avec vos identifiants.</div>
    </div>
    <div class="panel-footer">
        <button type="button" id="btn-verifier" class="btn btn-info btn-sm btn-block">
            <i class="fas fa-sync-alt mr-50"></i> Vérifier
        </button>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
    document.querySelector('#PaiementConfig').classList.add('active');

    $('#btn-test-connexion').on('click', function () {
        $('#panel-test-connexion').toggle();
    });

    $('#btn-close-panel').on('click', function () {
        $('#panel-test-connexion').hide();
    });

    function ajouterMessage(type, texte) {
        $('#panel-body').append('<div class="msg ' + type + '">' + texte + '</div>');
        var body = document.getElementById('panel-body');
        body.scrollTop = body.scrollHeight;
    }

    $('#btn-verifier').on('click', function () {
        var baseUrl = $('#base_url').val();
        var login = $('#login').val();
        var password = $('#password').val();

        var btn = $(this);
        btn.prop('disabled', true);

        ajouterMessage('bot', 'Vérification de la connexion…');

        $.post('{{ route("paiement.settings.test") }}', {
            _token: '{{ csrf_token() }}',
            base_url: baseUrl,
            login: login,
            password: password
        }, function (res) {
            btn.prop('disabled', false);
            if (res.ok) {
                ajouterMessage('success', res.message);
            } else {
                ajouterMessage('error', res.message);
            }
        }).fail(function (xhr) {
            btn.prop('disabled', false);
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur.';
            ajouterMessage('error', msg);
        });
    });
</script>
@endsection
