@extends('layouts.app')

@section('title', 'Nouveau forfait')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Nouveau forfait</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informations du forfait</h4>
                </div>
                <div class="card-body">
                    <form class="form" action="{{ route('admin.plans.store') }}" method="POST">
                        @csrf
                        <div class="row mx-auto col-lg-10">
                            <div class="col-12 col-md-6 py-1">
                                <label for="name">Nom <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather="layers"></i></span>
                                    </div>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ex : Starter" required>
                                </div>
                                @error('name')
                                    <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 py-1">
                                <label for="code">Code <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather="hash"></i></span>
                                    </div>
                                    <input type="text" id="code" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="ex : starter" required>
                                </div>
                                @error('code')
                                    <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 py-1">
                                <label for="price">Prix <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather="dollar-sign"></i></span>
                                    </div>
                                    <input type="number" step="0.01" min="0" id="price" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', 0) }}" required>
                                </div>
                                @error('price')
                                    <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 py-1">
                                <label for="currency">Devise <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather="globe"></i></span>
                                    </div>
                                    <input type="text" id="currency" name="currency" class="form-control @error('currency') is-invalid @enderror" value="{{ old('currency', 'XAF') }}" maxlength="3" required>
                                </div>
                                @error('currency')
                                    <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 py-1">
                                <label for="billing_interval">Période de facturation <span class="text-danger">*</span></label>
                                <select id="billing_interval" name="billing_interval" class="form-control @error('billing_interval') is-invalid @enderror">
                                    <option value="monthly" {{ old('billing_interval') === 'monthly' ? 'selected' : '' }}>Mensuel</option>
                                    <option value="annually" {{ old('billing_interval') === 'annually' ? 'selected' : '' }}>Annuel</option>
                                </select>
                                @error('billing_interval')
                                    <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 py-1">
                                <label for="trial_days">Jours d'essai</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather="clock"></i></span>
                                    </div>
                                    <input type="number" min="0" max="365" id="trial_days" name="trial_days" class="form-control @error('trial_days') is-invalid @enderror" value="{{ old('trial_days', 0) }}">
                                </div>
                                @error('trial_days')
                                    <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>

                            <div class="col-12 py-1">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="2" placeholder="Description du forfait">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>

                            <div class="col-12 py-1">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Forfait actif</label>
                                </div>
                            </div>

                            <div class="col-12 py-2 text-center">
                                <a href="{{ route('admin.plans.index') }}" class="btn btn-danger mt-2 mr-2">
                                    <i data-feather="x" class="mr-50"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-success mt-2">
                                    <i data-feather="download" class="mr-50"></i> Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
