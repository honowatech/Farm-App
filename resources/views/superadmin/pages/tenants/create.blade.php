@extends('layouts.app')

@section('title', 'Nouvelle entreprise')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Nouvelle entreprise</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informations de l'entreprise</h4>
                </div>
                <div class="card-body">
                    <form class="form" action="{{ route('admin.tenants.store') }}" method="POST">
                        @csrf
                        <div class="row mx-auto col-lg-10">
                            <div class="col-12 col-md-6 py-1">
                                <label for="name">Nom de l'entreprise <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather="grid"></i></span>
                                    </div>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ex : Ferme Avicole du Littoral" required>
                                </div>
                                @error('name')
                                    <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 py-1">
                                <label for="slug">Identifiant (slug) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather="hash"></i></span>
                                    </div>
                                    <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="ex : ferme-littoral" required>
                                </div>
                                <small class="text-muted">Minuscules, chiffres et tirets uniquement.</small>
                                @error('slug')
                                    <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 py-1">
                                <label for="status">Statut <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather="activity"></i></span>
                                    </div>
                                    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspendue</option>
                                    </select>
                                </div>
                                @error('status')
                                    <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 py-1">
                                <label for="demo_expires_at">Expiration de la démo</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather="clock"></i></span>
                                    </div>
                                    <input type="datetime-local" id="demo_expires_at" name="demo_expires_at" class="form-control @error('demo_expires_at') is-invalid @enderror" value="{{ old('demo_expires_at') }}">
                                </div>
                                @error('demo_expires_at')
                                    <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>

                            <div class="col-12 py-1">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_demo" name="is_demo" value="1" {{ old('is_demo') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_demo">Espace de démonstration</label>
                                </div>
                            </div>

                            <div class="col-12 py-2 text-center">
                                <a href="{{ route('admin.tenants.index') }}" class="btn btn-danger mt-2 mr-2">
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
