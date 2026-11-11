@extends('layouts.app')

@section('title', 'Configuration MaMoNi')

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
                                                    <select class="select2 form-control" id="channel" name="channel" required>
                                                        <option value="SELLER_DIRECT" {{ old('channel', $config['channel']) === 'SELLER_DIRECT' ? 'selected' : '' }}>SELLER_DIRECT</option>
                                                        <option value="COLLECTION" {{ old('channel', $config['channel']) === 'COLLECTION' ? 'selected' : '' }}>COLLECTION</option>
                                                    </select>
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
@endsection

@section('javascript')
<script type="text/javascript">
    document.querySelector('#PaiementConfig').classList.add('active');
</script>
@endsection
