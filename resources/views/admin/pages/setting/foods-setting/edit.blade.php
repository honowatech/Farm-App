@extends('layouts.app')
@section('title')
 Nutrition
@endsection
<style type="text/css">
    .touchspin-disabled {
    pointer-events: none;
}
</style>
@section('contenu')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-9 col-12">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="float-left mb-0 content-header-title">Nutrition</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{route('setting.index')}}">Paramètre</a>
                                    </li>
                                    <li class="breadcrumb-item active">Modification du planning
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section class="form-control-repeater">
                    <div class="row">
                        <!-- Invoice repeater -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Formulaire</h4>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{route('update_foods_setting')}}" class="invoice-repeater">
                                        @csrf
                                        @method('PATCH')
                                        <div class="py-3 mx-auto row">
                                            <div class="offset-3 col-12 col-md-3">

                                                    <label>Type d'annimal<span class="text-danger">*</span> </label>
                                                    <select required name="type" class="select2 form-control form-control-lg @error('type') is-invalid @enderror">
                                                        @php
                                                            // Utilisation de la méthode unique pour filtrer les éléments basés sur 'planning->nom'
                                                            $uniqueNutritions = $nutritions->unique(function ($item) {
                                                                return $item->planning->nom;
                                                            });
                                                        @endphp
                                                        @foreach($uniqueNutritions as $nutrition)
                                                        <option selected value="{{$nutrition->id_type_animal}}">{{$nutrition->planning->nom}}</option>
                                                            @foreach($types as $type)
                                                                @if($type->id != $nutrition->id_type_animal )
                                                                <option value="{{$type->id}}" {{old('type') == $type->id? 'selected': ''}}>{{$type->nom}}</option>
                                                                @endif
                                                            @endforeach

                                                    </select>
                                                @error('type')
                                                    <div class="text-danger"><strong>{{$message}}</strong></div>
                                                @enderror
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="itemquantity">Aliment<span class="text-danger">*</span></label>
                                                    <select required name="aliment" class="select2 form-control form-control-lg @error('aliment') is-invalid @enderror">

                                                        <option selected value="{{$nutrition->aliment}}">{{$nutrition->alimentName->nom_produit}}</option>
                                                            @foreach($aliments as $aliment)
                                                                @if($aliment->statut == 1 && $aliment->id != $nutrition->aliment )
                                                                    <option value="{{ $aliment->id }}" {{ old('aliment') == $aliment->id ? 'selected':''}}>{{ $aliment->nom_produit }}</option>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('aliment')
                                                    <span class="invalid-feedback"><strong>{{$message}}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div data-repeater-list="invoice">
                                            <div class="d-none formulaire" data-repeater-item data-index>
                                                <div class="mx-auto row align-items-center">
                                                    <div class="mx-auto col-md-4 col-12">
                                                        <div class="form-group">
                                                            <label class="mx-auto text-center col-12" for="itemname ">Tranche d'âge<span class="text-danger">*</span></label>
                                                            <div class="ml-3 demo-inline-spacing">
                                                                <div class="offset-2 min-div input-group col-6 col-lg-4">
                                                                    <div class="col-10"><label>min</label></div>
                                                                    <input required  name="age_min" type="number" class="touchspine min fs-2 @error('age_min') is-invalid @enderror" value="{{old('age_min',0)}}" />
                                                                </div>
                                                                @error('invoice.*.age_min')
                                                                    <span class="invalid-feedback"><strong>{{$message}}</strong></span>
                                                                @enderror
                                                                <div class="input-group max-div col-6 col-lg-4">
                                                                    <div class="col-10"><label>max</label></div>
                                                                    <input name="age_max" type="number" class="touchspine max @error('age_max') is-invalid @enderror" value="{{old('age_max', 0 )}}" />
                                                                </div>
                                                                @error('invoice.*.age_max')
                                                                    <span class="invalid-feedback"><strong>{{$message}}</strong></span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                   <div class="mx-auto my-auto col-md-2 col-12">
                                                        <label for="itemcost">Quantité d'aliment<span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <input required type="number" class="form-control qte @error('qte') is-invalid @enderror" id="itemcost" aria-describedby="itemcost" placeholder="Entrer un nombre" name="qte" value="{{ old('qte') }}" />
                                                        </div>
                                                        @error('invoice.*.qte')
                                                            <span class="invalid-feedback"><strong>{{$message}}</strong></span>
                                                        @enderror
                                                    </div>
                                                    <div class="mx-auto my-auto col-md-2 col-12">
                                                        <label>Unité</label>
                                                        <select required class="select5 unite form-control @error('unite') is-invalid @enderror" id="unit" name="unite">
                                                            <option selected value="">Sélectionner l'unité</option>
                                                            @foreach($unites as $unite)
                                                                <option value="{{ $unite->id }}" {{ old('unite') == $unite->id ? 'selected' : '' }}>{{ $unite->abrege }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('invoice.*.unite')
                                                            <span class="invalid-feedback"><strong>{{$message}}</strong></span>
                                                        @enderror
                                                        <input class="unit" type="hidden" name="id" value="{{$nutrition->id}}">
                                                    </div>
                                                    <div class="mx-auto my-auto col-md-2 col-12">
                                                        <label for="frequency">Fréquence (fois)<span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <input required type="number" min="1" class="form-control frequency" id="frequency" name="frequency" placeholder="Nombre de fois par période" value="{{ old('frequency', $nutrition->frequency) }}" />
                                                        </div>
                                                    </div>

                                                    <div class="mx-auto my-auto col-md-2 col-12">
                                                        <label>Période<span class="text-danger">*</span></label>
                                                        <select required class="form-control period" id="period" name="period">
                                                            <option selected value="">Sélectionner une période</option>
                                                            <option value="day" {{ old('period', $nutrition->period) == 'day' ? 'selected' : '' }}>Par jour</option>
                                                            <option value="week" {{ old('period', $nutrition->period) == 'week' ? 'selected' : '' }}>Par semaine</option>
                                                            <option value="month" {{ old('period', $nutrition->period) == 'month' ? 'selected' : '' }}>Par mois</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <hr />
                                            </div>
                                        </div>
                                        <div class="mx-auto row justify-content-center col-md-8 col-sm-10 col-12 col-lg-7">
                                            <div class="demo-inline-spacing">
                                                <button class="btn btn-icon btn-danger" data-repeater-delete type="button">
                                                    <i data-feather="x" class="mr-25"></i>
                                                </button>
                                                <button class="btn btn-icon btn-success" type="button" data-repeater-create>
                                                    <i data-feather="plus" class="mr-25"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="ml-3 row col-12">
                                                <div class="text-center d-none d-md-block col-bg-5 offset-2 col-lg-7 col-md-12 col-12">
                                                    <button type="reset" class="btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-danger mr-md-4">
                                                        <i class="mr-1" data-feather='x'></i> Effacer
                                                   </button>
                                                    <button type="submit" class="btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success">
                                                        <i class="mr-1" data-feather='download'></i> Enregistrer
                                                    </button>
                                                </div>
                                                <div class="text-center d-block d-md-none col-bg-5 col-lg-7 col-md-12 col-12">
                                                    <button type="submit" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success mt-md-2 mr-md-2">
                                                        <i class="mr-1" data-feather='download'></i> Enregistrer
                                                    </button>
                                                    <button type="reset" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-danger mt-md-2">
                                                        <i class="mr-1" data-feather='x'></i> Effacer
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
//activation du menu setting
$(document).ready(function() {
    let updating = false; // Drapeau pour éviter la récursion infinie
        setTimeout(function() {
                for (let i = 0; i < {{$lignes}}; i++) {
                    $('[data-repeater-create]').trigger('click');
                    let newElement = $('[data-index]').last();

                    @foreach($nutritions as $index => $nutrition)
                        if (i === {{ $index }}) {
                            newElement.each(function() {
                                $(this).find('.min').val('{{ $nutrition->age_min }}');
                                $(this).find('.max').val('{{ $nutrition->age_max }}');
                                $(this).find('.qte').val('{{ $nutrition->quantite_aliment }}');
                                $(this).find('.unite').val('{{ $nutrition->id_unite }}');
                                $(this).find('.period').val('{{ $nutrition->period}}');
                                $(this).find('.frequency').val('{{ $nutrition->frequency }}');
                            });
                        }
                    @endforeach
                }
        }, 100);
    // Suppression des lignes existantes
    setTimeout(function() {
            $('[data-repeater-item]').last().remove();
            // Ajout d'une nouvelle ligne après un court délai
            }, 10);

    // Activation des fonctions nécessaires au fonctionnement de l'input number
    setTimeout(function() {
        $('.touchspine').TouchSpin({
            buttondown_class: 'btn btn-primary',
            buttonup_class: 'btn btn-primary',
            buttondown_txt: feather.icons['minus'].toSvg(),
            buttonup_txt: feather.icons['plus'].toSvg(),
            min: 0,
            max: 100000000,
        });
    }, 100);
$('.formulaire').removeClass('d-none').addClass('d-block')// cache le formulaire le temps que a page charge complètemement puis l'affiche
    // Gestion des répétitions
  function initializeMin(element, minValue) {
        element.TouchSpin({
            buttondown_class: 'btn btn-primary',
            buttonup_class: 'btn btn-primary',
            buttondown_txt: feather.icons['minus'].toSvg(),
            buttonup_txt: feather.icons['plus'].toSvg(),
            min: minValue+1,
            max: 1000000
        }).val(minValue + 1);
    }

    function initializeMax(element, minValue) {
        element.TouchSpin({
            buttondown_class: 'btn btn-primary',
            buttonup_class: 'btn btn-primary',
            buttondown_txt: feather.icons['minus'].toSvg(),
            buttonup_txt: feather.icons['plus'].toSvg(),
            min: minValue + 1,
            max: 1000000
        }).val(minValue + 1);
    }


    function updateNextMin(newElement, updatedMaxValue) {
        if (updating) return; // Sortir si nous sommes déjà en train de mettre à jour

        updating = true; // Activer le drapeau

        newElement.find('.max').each(function() {
            $(this).trigger("touchspin.updatesettings", { min: parseFloat(updatedMaxValue) + 2 });
            $(this).val(parseFloat(updatedMaxValue) + 2);
        });

        newElement.find('.min').each(function() {
            $(this).val(parseFloat(updatedMaxValue) + 1);
            $(this).trigger("touchspin.updatesettings", { min: parseFloat(updatedMaxValue) + 1 });
        });

        updating = false; // Désactiver le drapeau
    }

    function minValNextMax(newElement, updatedMinValue) {
        if (updating) return; // Sortir si nous sommes déjà en train de mettre à jour

        updating = true; // Activer le drapeau

        newElement.find('.max').each(function() {
            $(this).trigger("touchspin.updatesettings", { min: parseFloat(updatedMinValue) + 1 });
            $(this).val(parseFloat(updatedMinValue));
        });

        newElement.find('.max').each(function() {
            $(this).trigger("touchspin.updatesettings", { min: parseFloat(updatedMinValue) + 1 });
            $(this).val(parseFloat(updatedMinValue) + 1);
        });

        updating = false; // Désactiver le drapeau
    }
    function griseMin(newElement,indexVal){
        newElement.find('.min-div').each(function(){
                if(indexVal > 0){
                $(this).find('.bootstrap-touchspin-up').addClass('touchspin-disabled');
                $(this).find('.bootstrap-touchspin-down').addClass('touchspin-disabled');
                }
            });
    }

    $('[data-repeater-create]').on('click', function() {

        $('.formulaire').removeClass('d-none').addClass('d-block');

        setTimeout(function() {
            let newElement = $('[data-index]').last();
            let previousElement = newElement.prev('[data-index]');
            let indexVal = newElement.data('index');
            let previousMaxValue = previousElement.find('.max').val();
            if (!previousMaxValue) {
                previousMaxValue = 0; // Valeur par défaut si aucun élément précédent trouvé
            }
            newElement.find('.select5').select2();


            initializeMin(newElement.find('.min'), parseFloat(previousMaxValue));
            initializeMax(newElement.find('.max'), parseFloat(newElement.find('.min').val()));

            $('[data-index]').each(function() {
                let input_min = $(this).find('.min');
                let input_max = $(this).find('.max');
                let data_index = $(this);
                let all_next_data_index = data_index.nextAll('[data-index]');
                let index = $(this).data('index');
                if(index <= 0){
                        input_min.on('change', function() {
                        if (updating) return; // Sortir si nous sommes déjà en train de mettre à jour
                        let update_val = parseFloat($(this).val());
                        minValNextMax(data_index, update_val);

                        all_next_data_index.each(function(){
                            previous_index = $(this).prev('[data-index]');
                            previous_max = parseFloat(previous_index.find('.max').val());
                            updateNextMin($(this) , previous_max);
                            minValNextMax($(this), previous_max+1);
                        });

                    });
                        input_max.on('change', function() {
                        if (updating) return; // Sortir si nous sommes déjà en train de mettre à jour
                        let update_val = $(this).val();
                        //gestion des input qui se suive directement
                        all_next_data_index.each(function(){
                            previous_index = $(this).prev('[data-index]');
                            previous_max = parseFloat(previous_index.find('.max').val());
                            updateNextMin($(this) , previous_max);
                            minValNextMax($(this), previous_max+1);
                        });
                    });
                } else {
                    input_max.on('change', function() {
                        if (updating) return; // Sortir si nous sommes déjà en train de mettre à jour
                        let update_val = $(this).val();
                        //gestion des input qui se suive directement
                        all_next_data_index.each(function(){
                            previous_index = $(this).prev('[data-index]');
                            previous_max = parseFloat(previous_index.find('.max').val());
                            updateNextMin($(this) , previous_max);
                            minValNextMax($(this), previous_max+1);
                        });
                    });
                        griseMin(data_index, index);
                }
            });

            $('[data-repeater-delete]').on('click', function() {
                let index = $('[data-index]').last();
                setTimeout(function() {
                    index.slideUp(function() {
                        $(this).remove();
                    });
                }, 100);
            });
        }, 100);
    });
    // Suppression de la ligne qui sert à dupliquer après le chargement de la page
    $('[data-repeater-item-template]').remove();
    });
    </script>
    <script src="{{asset('app-assets/js/scripts/forms/form-select2.js')}}"></script>
@endsection
