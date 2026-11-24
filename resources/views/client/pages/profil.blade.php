@extends('layouts.app')

@section('title', 'Mon profil')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Mon profil</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            @if($demoProfile)
                <div class="alert alert-warning" role="alert">
                    <i data-feather="info" class="mr-50"></i>
                    Vous consultez la démo en mode client : la modification du profil est désactivée.
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('client.profil.update') }}" method="POST">
                        @csrf
                        <fieldset @if($demoProfile) disabled="disabled" @endif>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="name">Nom complet</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                                    @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="email">Adresse e-mail</label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                                    @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="password">Nouveau mot de passe (laisser vide pour conserver)</label>
                                    <input type="password" name="password" id="password" class="form-control">
                                    @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmer le mot de passe</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('client.dashboard') }}" class="btn btn-outline-secondary">Annuler</a>
                            @if(!$demoProfile)
                            <button type="submit" class="btn btn-success">
                                <i data-feather="save" class="mr-50"></i> Enregistrer
                            </button>
                            @endif
                        </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
