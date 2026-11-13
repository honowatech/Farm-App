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
                        <div class="card-header">
                            <h3 class="card-title">{{ __('depenses::translated.Formulaire de création') }}</h3>
                        </div>
                        <div class="card-body">
                            <form class="form" action="{{ route('depenses.store') }}" method="POST">
                                @csrf
                                <div class="row col-lg-8 mx-auto" id='replace'>
                                    <div class="form-group col-12 py-1">
                                        <label>{{ __('Utiliser une caisse ?') }}</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="useCaisseSwitch" checked {{ old('id_caisse') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="useCaisseSwitch">{{ __('Oui') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 py-1 {{ old('id_caisse') ? '' : 'd-none' }}" id="caisseSelection">
                                        <label for="caisse">{{ __('Choisir une caisse') }} <span class="text-danger">*</span></label>
                                        <select id="caisse" name="id_caisse" class="select2 form-control @error('id_caisse') is-invalid @enderror">
                                            <option selected value="">{{ __('Choisir une caisse') }}</option>
                                            @foreach($caisses as $caisse)
                                                <option value="{{ $caisse->id }}" {{ old('id_caisse') == $caisse->id ? 'selected' : '' }}>{{ $caisse->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6 py-1">
                                        <label>{{ __('depenses::translated.Type de dépense') }}<span class="text-danger">*</span></label>
                                        <select required id="type" onchange="" name="id_type_depense" class="select2 form-control @error('id_type_depense') is-invalid @enderror">
                                            <option selected value="">{{ __('depenses::translated.Choisir un type') }}</option>
                                            @if($categories->isEmpty())
                                                <option selected value="">{{ __('depenses::translated.Aucun type trouvé') }}</option>
                                            @else
                                                @foreach($categories as $categorie)
                                                    <option value="{{ $categorie->id }}" {{ old('id_type_depense') == $categorie->id ? 'selected' : '' }}>{{ $categorie->nom }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('id_type_depense')
                                            <div class="text-danger"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6 py-1">
                                        <label for="nature_depense">{{ __('depenses::translated.Intitulé de la dépense') }} <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge @error('nature_depense') is-invalid @enderror">
                                            <input type="text" id="nature_depense" class="form-control @error('nature_depense') is-invalid @enderror" name="nature_depense" placeholder="{{ __('depenses::translated.Intitulé de la dépense') }}" value="{{ old('nature_depense') }}" required/>
                                        </div>
                                        @error('nature_depense')
                                            <div class="text-danger"><strong>{{ $message }}</strong></div>
                                        @enderror
                                        <small class="alert alert-danger d-none" id="erreur_nature_depense">{{ __('depenses::translated.Veuillez spécifier la nature de la dépense') }}</small>
                                    </div>

                                    <div class="col-12 col-md-6 py-1">
                                        <label for="prix_unitaire">{{ __('depenses::translated.Montant dépense') }} <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="text-center form-control" placeholder="1,000" id="prix_unitaire" name="prix_unitaire2" onkeyup="format_montant('prix_unitaire')" value="{{ old('prix_unitaire2') }}" required/>
                                            <div class="input-group-append">
                                                <span class="input-group-text">FCFA</span>
                                            </div>
                                        </div>
                                        <input type="hidden" id="prix_unitaire2" name="prix_unitaire" value="{{ old('prix_unitaire') }}" />
                                        <small class="alert alert-danger d-none" id="erreur_montant">{{ __('depenses::translated.veuillez entrer le montant de la dépense') }}</small>
                                    </div>

                                    <div class="col-md-6 col-12 py-1">
                                        <div class="form-group">
                                            <label for="date">{{ __('depenses::translated.Date') }}</label>
                                            <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                                            <div class="form-group">
                                                <input type="text" id="date" class="form-control flatpickr-basic flatpickr-input @error('date') is-invalid @enderror" name="date" placeholder="{{ __('depenses::translated.Date d\'enregistrement') }}" value="{{ old('date', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                                                <small class="alert alert-danger d-none" id="erreur_date">{{ __('depenses::translated.veuillez entré une date valide') }}</small>
                                            </div>
                                        </div>
                                        @error('date')
                                            <div class="text-danger"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 py-1">
                                        <label for="fournisseur">{{ __('depenses::translated.Fournisseur') }} <span class="text-info">{{ __('depenses::translated.Optionnel') }}</span></label>
                                        <div class="input-group input-group-merge @error('fournisseur') is-invalid @enderror">
                                            <input type="text" id="fournisseur" class="form-control @error('fournisseur') is-invalid @enderror" name="fournisseur" placeholder="{{ __('depenses::translated.Fournisseur') }}" value="{{ old('fournisseur') }}" />
                                        </div>
                                        @error('fournisseur')
                                            <div class="text-danger"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>

                                    <div class="col-6 py-1 mx-auto">
                                        <div class="form-group">
                                            <label for="adresse">{{ __('depenses::translated.Description') }}</label>
                                            <span class="text-info cursor-pointer" title="optionnel">{{ __('depenses::translated.Optionnel') }}</span>
                                            <div class="form-group">
                                                <textarea type="text" id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="3" placeholder="{{ __('depenses::translated.Entrer du texte ici') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row col-12">
                                    <div class="d-none d-md-block text-center col-bg-5 col-lg-8 col-md-12 col-12 mx-auto">
                                        <button type="reset" class="btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-danger mt-1 mt-md-2 mr-md-4">
                                            <i class="mr-1" data-feather='x'></i> {{ __('depenses::translated.Effacer') }}
                                        </button>
                                        <button type="submit" class="btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success mt-1 mt-md-2">
                                            <i class="mr-1" data-feather='download'></i> {{ __('depenses::translated.Enregistrer') }}
                                        </button>
                                    </div>
                                    <div class="d-block d-md-none text-center col-bg-5 col-lg-8 col-md-12 col-12 mx-auto">
                                        <button type="submit" class="btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success mt-1 mt-md-2 mr-md-2">
                                            <i class="mr-1" data-feather='download'></i> {{ __('depenses::translated.Enregistrer') }}
                                        </button>
                                        <button type="reset" class="btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-danger mt-1 mt-md-2">
                                            <i class="mr-1" data-feather='x'></i> {{ __('depenses::translated.Effacer') }}
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

         $('form').on('submit', function(e) {
                e.preventDefault(); // Empêche la soumission du formulaire par défaut

                // Supprime les anciens messages d'erreur
                $('.text-danger').remove();

                // Récupération des valeurs du formulaire
                let nature_depense = $('#nature_depense').val().trim();
                let prix_unitaire = $('#prix_unitaire2').val().trim();
                let date = $('#date').val().trim();
                // Variable pour savoir si on continue avec l'ajout de la ligne
                let valid = true;

                function showError(elementId) {
                    let errorElement = $(`#${elementId}`);
                    errorElement.removeClass('d-none').addClass('d-block');

                    // Masquer le message d'erreur après 4 secondes
                    setTimeout(function() {
                        errorElement.removeClass('d-block').addClass('d-none');
                    }, 4000); // 4000 millisecondes = 4 secondes
                }
                // Vérification des champs obligatoires
                if(!nature_depense){
                   showError('erreur_nature_depense');
                    valid = false;
                }
                if (!prix_unitaire) {
                    showError('erreur_montant');
                    valid = false;
                }
                if (!date) {
                    showError('erreur_date');
                    valid = false;
                }

                if(valid){
                   $('form').off('submit').submit();
                }
            });
         function remplir_montant(id){
                var montant2 = document.querySelector('#'+id+'2')
                    montant = document.querySelector('#'+id).value.replaceAll(',','');
                montant2.value = montant.replaceAll(',','')
            }
            function format_montant(id) {
                number = document.querySelector('#'+id).value.replaceAll(' ','');
                numero = 0
                number= number.replaceAll(',', "")
                number=parseInt(number);
                if (isNaN(number)) {
                    document.getElementById(id).value = '';
                }else{
                number =  number.toLocaleString('fr-FR', {
                        groupSize: 3,
                        useGrouping: true,
                    });
                document.getElementById(id).value =  number.replaceAll(/\s/g, ",")
                }
                remplir_montant(id);
            }
            $('#type').on('change', function() {
            var typeDepenseId = $(this).val(); // Récupère l'ID du type de dépense sélectionné

            if (typeDepenseId) {
                // Requête AJAX pour récupérer le prix unitaire correspondant
                $.ajax({
                    url: '{{ url('/depenses/get-prix-unitaire') }}/' + typeDepenseId, // URL avec l'ID de dépense
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.prix_unitaire) {
                            // Insère le prix unitaire dans le champ correspondant et met à jour le champ caché
                            $('#prix_unitaire').val(data.prix_unitaire);
                            $('#prix_unitaire2').val(data.prix_unitaire);
                        } else {
                            // Si aucun prix trouvé, remettre à zéro
                            $('#prix_unitaire').val('');
                            $('#prix_unitaire2').val('');
                        }
                    },
                    error: function() {
                        // Si la requête échoue, on remet à zéro les champs de prix unitaire
                        $('#prix_unitaire').val('');
                        $('#prix_unitaire2').val('');
                        alert('Erreur lors de la récupération du prix unitaire.');
                    }
                });
            } else {
                // Si aucun type sélectionné, remettre à zéro
                $('#prix_unitaire').val('');
                $('#prix_unitaire2').val('');
            }
        });
        document.getElementById('useCaisseSwitch').addEventListener('change', function() {
            var caisseSelection = document.getElementById('caisseSelection');
            if (this.checked) {
                caisseSelection.classList.remove('d-none');
            } else {
                caisseSelection.classList.add('d-none');
            }
        });

        // Trigger the event on page load based on the initial state
        document.addEventListener('DOMContentLoaded', function() {
            var caisseSwitch = document.getElementById('useCaisseSwitch');
            caisseSwitch.dispatchEvent(new Event('change')); // This will show/hide the caisse selection based on the switch state
            // Set the date input value to the current date
            var dateInput = document.getElementById('date');
            dateInput.value = '{{ old('date', \Carbon\Carbon::now()->format('Y-m-d')) }}';
            // Remove readonly attribute from prix_unitaire input
            $('#date').removeAttr('readonly');
        });
    </script>
@endsection
