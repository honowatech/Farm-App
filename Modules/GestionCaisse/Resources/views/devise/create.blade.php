@extends('layouts.app')
@section('title')
{{'Nouvelle dépense'}}
@endsection
@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{ __('depenses::translated.Nouvelle dépense') }}</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('depenses::translated.Acceuil') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('depenses::translated.Ajouter une dépense') }}</li>
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
                        <div class="card-body">
                            <form class="form" action="{{ route('devise.store') }}" method="POST">
                                @csrf
                                <div class="row col-lg-8 mx-auto" id="replace">
                                    <!-- Code -->
                                    <div class="col-12 col-md-6 py-1">
                                        <label for="code">{{ __('Code de la devise') }} <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" id="code" class="form-control @error('code') is-invalid @enderror" name="code" placeholder="Ex: USD" value="{{ old('code') }}" required />
                                        </div>
                                        @error('code')
                                            <div class="text-danger"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>

                                    <!-- Symbole -->
                                    <div class="col-12 col-md-6 py-1">
                                        <label for="symbole">{{ __('Symbole de la devise') }} <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" id="symbole" class="form-control @error('symbole') is-invalid @enderror" name="symbole" placeholder="Ex: $" value="{{ old('symbole') }}" required />
                                        </div>
                                        @error('symbole')
                                            <div class="text-danger"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>

                                    <!-- Nom -->
                                    <div class="col-12 col-md-6 py-1">
                                        <label for="nom">{{ __('Nom de la devise') }} <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" id="nom" class="form-control @error('nom') is-invalid @enderror" name="nom" placeholder="Ex: Dollar américain" value="{{ old('nom') }}" required />
                                        </div>
                                        @error('nom')
                                            <div class="text-danger"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Boutons -->
                                <div class="row col-12">
                                    <div class="text-center col-lg-8 mx-auto">
                                        <button type="reset" class="btn btn-danger mt-2 mr-2">
                                            <i class="mr-1" data-feather='x'></i> {{ __('Effacer') }}
                                        </button>
                                        <button type="submit" class="btn btn-success mt-2">
                                            <i class="mr-1" data-feather='download'></i> {{ __('Enregistrer') }}
                                        </button>
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
		$('#DeviseListe').addClass('active');
    </script>
@endsection
