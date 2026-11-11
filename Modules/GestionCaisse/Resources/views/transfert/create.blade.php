@extends('layouts.app')
@section('title')
{{'Nouveau transfert'}}
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
                        <h2 class="content-header-title float-left mb-0">{{ __('Nouveau transfert') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Acceuil') }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('transfert.index') }}">{{ __('Liste des transferts') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('Ajouter un transfert') }}</li>
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
                                <form class="form" action="{{ route('transfert.store') }}" method="POST">
                                    @csrf
                                    <div class="row col-lg-8 mx-auto" id="replace">
                                            <!-- Sélection de la caisse source -->

                                            <div class="col-12 col-md-6 py-1">
                                                <label for="caisse_source">Caisse Source<span class="text-danger">*</span></label>
                                                <select id="caisse_source" name="caisse_source" class="form-control @error('caisse_source') is-invalid @enderror" required>
                                                    <option selected value="">{{ __('Choisissez une caisse') }}</option>
                                                    @foreach($caisses as $caisse)
                                                        <option value="{{$caisse->id}}" {{old('caisse_source') == $caisse->id ? 'selected' : ''}}>{{$caisse->nom ?? $caisse->nom_banque}}</option>
                                                    @endforeach
                                                </select>
                                                @error('caisse_source')
                                                    <div class="text-danger"><strong>{{ $message }}</strong></div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-6 py-1">
                                                <label for="destination_caisse">Caisse Destination<span class="text-danger">*</span></label>
                                                <select id="destination_caisse" name="destination_caisse" class="form-control @error('destination_caisse') is-invalid @enderror" required>
                                                    <option selected >Choisissez une caisse</option>
                                                    @foreach($caisses as $caisse)
                                                        <option value="{{$caisse->id}}" {{old('destination_caisse') == $caisse->id ? 'selected' : ''}}>{{$caisse->nom ?? $caisse->nom_banque}}</option>
                                                    @endforeach
                                                </select>
                                                @error('destination_caisse')
                                                    <div class="text-danger"><strong>{{ $message }}</strong></div>
                                                @enderror
                                            </div>

                                            <!-- Montant -->
                                            <div class="col-12 col-md-6 py-1">
                                                <label for="montant" class="form-label">Montant à transférer<span class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" class="text-center form-control" placeholder="1,000" id="montant" name="montant2" onkeyup="format_montant('montant')" value="{{ old('montant2') }}" required/>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">FCFA</span>
                                                    </div>
                                                </div>
                                                    <input type="hidden" id="montant2" name="montant" value="{{ old('montant') }}" />
                                            </div>
                                            <div class="col-12 col-md-6 py-1">
                                                <div class="form-group">
                                                    <label for="date">Date</label>
                                                    <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                                                    <div class="input-group input-group-merge @error('date')  is-invalid @enderror">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="mr-1" data-feather="calendar"></i></span>
                                                        </div>
                                                        <input class="form-control flatpickr-human-friendly @error('date')  is-invalid @enderror "  placeholder="January 01, 2024" tabindex="0" type="text" id="date" readonly="readonly" name="date" required value="{{old('date')}}">
                                                    </div>
                                                    @error('date')
                                                            <small class="alert alert-danger"> {{$message}} </small>
                                                    @enderror
                                                    <div id="error_date" class="d-none">
                                                        <small class="alert alert-danger">veuillez remplir ce champ</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 py-1 mx-auto">
                                                <div class="form-group">
                                                    <label for="adresse">{{ __('Description') }}</label>
                                                    <span class="text-info cursor-pointer" title="optionnel">{{ __('Optionnel') }}</span>
                                                    <div class="form-group">
                                                        <textarea type="text" id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="3" placeholder="{{ __('Entrer du texte ici') }}">{{old('description')}}</textarea>
                                                    </div>
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

