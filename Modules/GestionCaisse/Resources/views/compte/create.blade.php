@extends('layouts.app')
@section('title')
{{'Nouveau compte'}}
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
                    <h2 class="content-header-title float-left mb-0">{{ __('Nouveau compte') }}</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Acceuil') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Ajouter un nouveau compte') }}</li>
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
                            <form class="form" action="{{ route('compte.store') }}" method="POST">
							    @csrf
							    <div class="row col-lg-8 mx-auto" id='replace'>
							        <!-- Nom -->

                                    <!-- Type de compte -->
                                    <div class="col-12 col-md-6 py-1">
                                        <label for="type_account">{{ __('Type de compte') }} <span class="text-danger">*</span></label>
                                        <select id="type_account" name="type_account" class="form-control @error('type_account') is-invalid @enderror" required>
                                            <option selected value="">{{ __('Choisir un type de compte') }}</option>
                                            <option value="caisse" {{ old('type_account') == 'caisse' ? 'selected' : '' }}>Caisse</option>
                                            <option value="banque" {{ old('type_account') == 'banque' ? 'selected' : '' }}>Banque</option>
                                        </select>
                                        @error('type_account')
                                            <div class="text-danger"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>

							        <div class="col-12 col-md-6 py-1">
							            <label for="nom_caisse">{{ __('intitulé de la caisse') }} <span class="text-danger">*</span></label>
							            <div class="input-group input-group-merge">
							                <input type="text" id="nom_caisse" class="form-control @error('nom_caisse') is-invalid @enderror" name="nom_caisse" placeholder="Nom du compte" value="{{ old('nom_caisse') }}" required autofocus />
							            </div>
							            @error('nom_caisse')
							                <div class="text-danger"><strong>{{ $message }}</strong></div>
							            @enderror
							        </div>
                                    <div class="col-12 col-md-6 py-1">
                                        <label for="num_compte">{{ __('numéro de compte') }} <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" id="num_compte" class="form-control @error('num_compte') is-invalid @enderror" name="num_compte" placeholder="Numéro du compte" value="{{ old('num_compte') }}" required autofocus />
                                        </div>
                                        @error('num_compte')
                                            <div class="text-danger"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6 py-1">
                                        <label for="nom_banque">{{ __('nom de la banque') }} <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" id="nom_banque" class="form-control @error('nom_banque') is-invalid @enderror" name="nom_banque" placeholder="Nom de la banque" value="{{ old('nom_banque') }}" required autofocus />
                                        </div>
                                        @error('nom_banque')
                                            <div class="text-danger"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
							        <div class="col-12 col-md-6 py-1">
                                         <label for="prix_unitaire2">{{ __('Solde initial') }} <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="text-center form-control" placeholder="1,000" id="prix_unitaire" name="initial_balance2" onkeyup="format_montant('prix_unitaire')" value="{{ old('initial_balance2') }}" required/>
                                            <div class="input-group-append">
                                                <span class="input-group-text">FCFA</span>
                                            </div>
                                             @error('initial_balance')
							                <div class="text-danger"><strong>{{ $message }}</strong></div>
							            @enderror
                                        </div>
                                        <input type="hidden" id="prix_unitaire2" name="initial_balance" value="{{ old('initial_balance') }}" />
                                        <small class="alert alert-danger d-none" id="erreur_montant"></small>
                                    </div>
                                    <div class="col-12 col-md-6 py-1">
									    <label for="currency_id">{{ __('devise') }} <span class="text-danger">*</span></label>
									    <select id="currency_id" name="currency_id" class="form-control @error('currency_id') is-invalid @enderror" required>
									        <option selected value="">{{ __('Choisir un type de devise') }}</option>
									        @foreach($devises as $devise)
									            <option value="{{ $devise->id }}" {{ old('devise') == $devise->id ? 'selected' : '' }}>
									                {{ $devise->nom }}
									            </option>
									        @endforeach
									    </select>
									    @error('currency_id')
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
		$('#CompteListe').addClass('active');


   $(document).ready(function () {
        // Set default value to 'caisse' if not already selected
        if (!$('#type_account').val()) {
            $('#type_account').val('caisse');
        }

        // Initial hide/show based on selected type
        toggleFields();

        // Event listener for change in type_account
        $('#type_account').change(function () {
            toggleFields();
        });

        function toggleFields() {
            const typeAccount = $('#type_account').val();

            // Hide all fields initially using d-none
            $('#nom_caisse').closest('.col-md-6').addClass('d-none').find('input').removeAttr('required');
            $('#nom_banque').closest('.col-md-6').addClass('d-none').find('input').removeAttr('required');
            $('#num_compte').closest('.col-md-6').addClass('d-none').find('input').removeAttr('required');
            $('#prix_unitaire').closest('.col-md-6').addClass('d-none').find('input').removeAttr('required');
            $('#devise').closest('.col-md-6').addClass('d-none').find('select').removeAttr('required');

            if (typeAccount === 'caisse') {
                // Show fields for caisse
                $('#nom_caisse').closest('.col-md-6').removeClass('d-none').find('input').attr('required', true);
                $('#devise').closest('.col-md-6').removeClass('d-none').find('select').attr('required', true);
            } else if (typeAccount === 'banque') {
                // Show fields for banque
                $('#nom_banque').closest('.col-md-6').removeClass('d-none').find('input').attr('required', true);
                $('#num_compte').closest('.col-md-6').removeClass('d-none').find('input').attr('required', true);
                $('#devise').closest('.col-md-6').removeClass('d-none').find('select').attr('required', true);
            }
        }
    });


    </script>
@endsection
