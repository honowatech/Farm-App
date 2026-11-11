@extends('layouts.app')

@section('title')
{{'Nouvelle séance de nutrition'}}
@endsection
@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        	<div class="mb-2 content-header-left col-md-12 col-12">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="float-left mb-0 content-header-title">Nouvelle Séance de Nutrition</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Accueil</a></li>
                                <li class="breadcrumb-item active">Ajouter une séance</li>
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
                                {{-- La route de l'action est déplacée sur le formulaire principal --}}
                                <form class="form" action="{{route('nutrition.store')}}" method="POST" data-offline-queue="1" data-redirect="{{ route('nutrition.index') }}">
                                    @csrf
                                    <div class="mx-auto row col-lg-10">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label>Bande<span class="text-danger">*</span></label>
                                                <select required id="id_bande" name="bande" class="select2 form-control @error('bande') is-invalid @enderror">
                                                    <option selected disabled value="">Choisissez une bande</option>
                                                    @foreach($bandes as $bande)
                                                        @if($bande->statut_bande != 'terminee')
                                                            <option value="{{$bande->id}}" {{ old('bande') == $bande->id ? 'selected' : '' }}>{{$bande->nom}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('bande')
                                                    <div class="mt-1 text-danger"><strong>{{$message}}</strong></div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <label>Statut de nutrition</label>
                                            <div>
                                                <span class="font-weight-bolder" id="nourri-info">Sélectionnez une bande...</span>
                                            </div>
                                        </div>

                                        {{-- Ce conteneur sera remplacé par le formulaire partiel via AJAX --}}
                                        <div id="replace_form" class="mt-2 row col-12">
                                            @if(!old('bande')) {{-- Affiche le message par défaut seulement s'il n'y a pas d'ancienne sélection --}}
                                                <div class="mt-1 mb-1 text-center col-12 text-muted">
                                                    <i data-feather='arrow-up' style="width: 32px; height: 32px;"></i>
                                                    <p><strong>Veuillez sélectionner une bande pour commencer.</strong></p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-3 row col-12">
                                        <div class="mx-auto text-center col-lg-8 col-12">
                                            <button type="reset" class="mr-1 btn btn-danger">
                                                <i class="mr-1" data-feather='x'></i> Annuler
                                            </button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="mr-1" data-feather='check-circle'></i> Enregistrer
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

    // Fonction pour charger le formulaire de nutrition via AJAX
    function getNutritionForm(bandeId) {
        const formContainer = $('#replace_form');
        const statusContainer = $('#nourri-info');

        if (!bandeId) {
            formContainer.html('<div class="text-center col-12 text-muted"><p>Veuillez sélectionner une bande.</p></div>');
            statusContainer.text('Sélectionnez une bande...');
            return;
        }

        // Affiche un indicateur de chargement
        formContainer.html('<div class="text-center col-12"><div class="spinner-border" role="status"></div><p>Chargement...</p></div>');
        statusContainer.text('Vérification du statut...');

        $.ajax({
            url: '{{ route("load_form") }}',
            method: 'GET',
            data: { table_data: [bandeId] },
            success: function(response) {
                formContainer.html(response);
                // Après avoir chargé le formulaire, attacher l'événement pour la vérification de l'aliment
                attachAlimentCheckEvent(bandeId);
            },
            error: function() {
                formContainer.html('<div class="text-center col-12 text-danger"><p>Erreur lors du chargement du formulaire.</p></div>');
                statusContainer.text('Erreur.');
            }
        });
    }

    // Fonction pour attacher l'événement AJAX qui vérifie le statut d'un aliment
    function attachAlimentCheckEvent(bandeId) {
        // On utilise .off() pour éviter d'attacher plusieurs fois le même événement
        $('#replace_form').off('change', '#aliment-select').on('change', '#aliment-select', function() {
            const alimentId = $(this).val();
            const statusContainer = $('#nourri-info');

            if (!alimentId) {
                statusContainer.text('Sélectionnez un aliment...');
                return;
            }

            statusContainer.text('Vérification...');

            $.ajax({
                url: '{{ route("nutrition.check") }}',
                method: 'GET',
                data: { id_bande: bandeId, id_aliment: alimentId },
                success: function(response) {
                    statusContainer.text(response.message);
                },
                error: function() {
                    statusContainer.text('Erreur de vérification.');
                }
            });
        });
    }

    $(document).ready(function() {
        // Écouteur principal sur le changement de la bande
        $('#id_bande').on('change', function() {
            getNutritionForm($(this).val());
        });

        // Si la page a été rechargée après une erreur de validation, on recharge le formulaire
        @if (old('bande') && $errors->any())
            const bandeId = '{{ old('bande') }}';
            getNutritionForm(bandeId);
        @endif
    });
</script>
@endsection
