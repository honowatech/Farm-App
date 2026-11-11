@extends('layouts.app')
@section('css')
<style type="text/css">
.reset-flex {
    -webkit-flex: initial !important;
    flex: initial !important;
}
.touchspin-disabled {
    pointer-events: none;
}
.btn-delete-row {
    z-index: 1000 !important;
    position: absolute !important;
    right: 10px;
    top: 10px;
}
.formulaire {
    position: relative;
}
/* New CSS to hide elements initially */
.hide-until-loaded {
    display: none;
}
</style>
@endsection
@section('title')
 Nutrition
@endsection
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
                                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('setting.index')}}">Paramètre</a></li>
                                    <li class="breadcrumb-item active">{{ isset($nutritions) ? 'Modifier' : 'Nouveau' }} paramètre</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section class="form-control-repeater">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{ isset($nutritions) ? 'Modifier' : 'Définir' }} la quantité d'aliment par animal par jour
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <form method="POST"
                                        action="{{ isset($nutritions) && $nutritions->isNotEmpty() ? route('foods-setting.update', ['id_type_animal' => $nutritions->first()->foodSetting->id_type_animal, 'id_aliment' => $nutritions->first()->foodSetting->aliment]) : route('foods-setting.store') }}"
                                        class="invoice-repeater">
                                        @csrf
                                        @if(isset($nutritions))
                                            @method('PUT')
                                        @endif

                                        <div class="py-3 mx-auto row">
                                            <div class="offset-md-3 offset-0 col-12 col-md-3">
                                                <label>Type d'animal<span class="text-danger">*</span></label>
                                                <select required name="type"
                                                        class="select2 form-control form-control-lg @error('type') is-invalid @enderror"
                                                        {{ isset($nutritions) ? 'disabled' : '' }}>
                                                    <option value="">Choisir le type</option>
                                                    @foreach($types as $type)
                                                        <option value="{{$type->id}}"
                                                                {{ (old('type', isset($nutritions) ? $nutritions->first()->id_type_animal : '') == $type->id) ? 'selected' : '' }}>
                                                            {{$type->nom}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if(isset($nutritions))
                                                    <input type="hidden" name="type" value="{{ $nutritions->first()->id_type_animal }}">
                                                @endif
                                                @error('type')
                                                    <div class="invalid-feedback d-block"><strong>{{$message}}</strong></div>
                                                @enderror
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="itemquantity">Aliment<span class="text-danger">*</span></label>
                                                    <select required name="aliment"
                                                            class="select2 form-control form-control-lg @error('aliment') is-invalid @enderror"
                                                            {{ isset($nutritions) ? 'disabled' : '' }}>
                                                        <option value="">Choisir l'aliment</option>
                                                        @foreach($aliments as $aliment)
                                                            @if($aliment->statut == 1)
                                                                <option value="{{ $aliment->id }}"
                                                                        {{ (old('aliment', isset($nutritions) ? $nutritions->first()->aliment : '') == $aliment->id) ? 'selected' : '' }}>
                                                                    {{ $aliment->nom_produit }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    @if(isset($nutritions))
                                                        <input type="hidden" name="aliment" value="{{ $nutritions->first()->aliment }}">
                                                    @endif
                                                </div>
                                                @error('aliment')
                                                    <div class="invalid-feedback d-block"><strong>{{$message}}</strong></div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div data-repeater-list="invoice">
                                            @if(isset($nutritions) && $nutritions->count() > 0)
                                                @foreach($nutritions as $index => $nutrition)
                                                    <div class="formulaire" data-repeater-item data-index="{{ $index }}">
                                                        <input type="hidden" name="id" value="{{ $nutrition->id }}">
                                                        <div class="mx-auto row align-items-center">
                                                            <div class="mx-auto col-md-4 col-12">
                                                                <div class="form-group">
                                                                    <label class="mx-auto text-center col-12">Tranche d'âge en jour(s)<span class="text-danger">*</span></label>
                                                                    <div class="ml-0 demo-inline-spacing" style="justify-content: space-evenly; flex-wrap: nowrap;">
                                                                        <div class="min-div input-group col-6 reset-flex" style="margin-right: initial !important;">
                                                                            <div class="col-10"><label>min</label></div>
                                                                            <input required name="age_min" type="number"
                                                                                   class="touchspine min hide-until-loaded @error('invoice.'.$index.'.age_min') is-invalid @enderror"
                                                                                   value="{{ old('invoice.'.$index.'.age_min', $nutrition->age_min) }}" />
                                                                        </div>
                                                                        <div class="input-group col-6 reset-flex">
                                                                            <div class="col-10"><label>max</label></div>
                                                                            <input required name="age_max" type="number"
                                                                                   class="touchspine max hide-until-loaded @error('invoice.'.$index.'.age_max') is-invalid @enderror"
                                                                                   value="{{ old('invoice.'.$index.'.age_max', $nutrition->age_max) }}" />
                                                                        </div>
                                                                    </div>
                                                                    @error('invoice.'.$index.'.age_min')
                                                                        <div class="text-center text-danger"><small>{{ $message }}</small></div>
                                                                    @enderror
                                                                    @error('invoice.'.$index.'.age_max')
                                                                        <div class="text-center text-danger"><small>{{ $message }}</small></div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="mx-auto my-auto col-md-2 col-12">
                                                                <label>Sélectionner l'unité<span class="text-danger">*</span></label>
                                                                <select required class="select5 form-control unit @error('invoice.'.$index.'.unite') is-invalid @enderror"
                                                                        name="unite">
                                                                    <option value="">Sélection de l'unité</option>
                                                                    @foreach($unites as $unite)
                                                                        @if(!empty($unite) && ($unite->abrege === 'Kg' || $unite->abrege === 'mg'))
                                                                            <option value="{{ $unite->id }}"
                                                                                    {{ old('invoice.'.$index.'.unite', $nutrition->id_unite) == $unite->id ? 'selected' : '' }}>
                                                                                {{ $unite->abrege }}
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                                @error('invoice.'.$index.'.unite')
                                                                    <div class="invalid-feedback d-block"><small>{{ $message }}</small></div>
                                                                @enderror
                                                            </div>
                                                            <div class="mx-auto my-auto col-md-2 col-12">
                                                                <label for="itemcost">Qte d'aliment<span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <input required type="number" step="0.01"
                                                                           class="form-control qte @error('invoice.'.$index.'.qte') is-invalid @enderror"
                                                                           placeholder="Entrer un nombre" name="qte"
                                                                           value="{{ old('invoice.'.$index.'.qte', $nutrition->quantite_aliment) }}" />
                                                                </div>
                                                                @error('invoice.'.$index.'.qte')
                                                                    <div class="invalid-feedback d-block"><small>{{ $message }}</small></div>
                                                                @enderror
                                                            </div>
                                                            <div class="mx-auto my-auto col-md-2 col-12">
                                                                <label>Nombre de fois<span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <input required type="number" min="1"
                                                                           class="form-control @error('invoice.'.$index.'.frequency') is-invalid @enderror"
                                                                           name="frequency" placeholder="Nombre de fois"
                                                                           value="{{ old('invoice.'.$index.'.frequency', $nutrition->frequency) }}" />
                                                                </div>
                                                                @error('invoice.'.$index.'.frequency')
                                                                    <div class="invalid-feedback d-block"><small>{{ $message }}</small></div>
                                                                @enderror
                                                            </div>
                                                            <div class="mx-auto my-auto col-md-2 col-12">
                                                                <label>Période<span class="text-danger">*</span></label>
                                                                <select required class="form-control period @error('invoice.'.$index.'.period') is-invalid @enderror"
                                                                        name="period">
                                                                    <option value="">Sélectionner</option>
                                                                    <option value="day" {{ old('invoice.'.$index.'.period', $nutrition->period) == 'day' ? 'selected' : '' }}>
                                                                        Par jour
                                                                    </option>
                                                                </select>
                                                                @error('invoice.'.$index.'.period')
                                                                    <div class="invalid-feedback d-block"><small>{{ $message }}</small></div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <hr />
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="d-none formulaire" data-repeater-item data-index="0">
                                                    <div class="mx-auto row align-items-center">
                                                        <div class="mx-auto col-md-4 col-12">
                                                            <div class="form-group">
                                                                <label class="mx-auto text-center col-12">Tranche d'âge en jour(s)<span class="text-danger">*</span></label>
                                                                <div class="ml-0 demo-inline-spacing" style="justify-content: space-evenly; flex-wrap: nowrap;">
                                                                    <div class="min-div input-group col-6 reset-flex" style="margin-right: initial !important;">
                                                                        <div class="col-10"><label>min</label></div>
                                                                        <input required name="age_min" type="number" class="touchspine min hide-until-loaded" value="0" />
                                                                    </div>
                                                                    <div class="input-group col-6 reset-flex">
                                                                        <div class="col-10"><label>max</label></div>
                                                                        <input required name="age_max" type="number" class="touchspine max hide-until-loaded" value="1" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mx-auto my-auto col-md-2 col-12">
                                                            <label>Sélectionner l'unité<span class="text-danger">*</span></label>
                                                            <select required class="select5 form-control unit" name="unite">
                                                                <option value="">Sélection de l'unité</option>
                                                                @foreach($unites as $unite)
                                                                    @if(!empty($unite) && ($unite->abrege === 'Kg' || $unite->abrege === 'mg'))
                                                                        <option value="{{ $unite->id }}">{{ $unite->abrege }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mx-auto my-auto col-md-2 col-12">
                                                            <label>Qte d'aliment<span class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <input required type="number" step="0.01" class="form-control qte" placeholder="Entrer un nombre" name="qte" />
                                                            </div>
                                                        </div>
                                                        <div class="mx-auto my-auto col-md-2 col-12">
                                                            <label>Nombre de fois<span class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <input required type="number" min="1" class="form-control" name="frequency" placeholder="Nombre de fois" />
                                                            </div>
                                                        </div>
                                                        <div class="mx-auto my-auto col-md-2 col-12">
                                                            <label>Période<span class="text-danger">*</span></label>
                                                            <select required class="form-control period" name="period">
                                                                <option value="">Sélectionner</option>
                                                                <option value="day" selected>Par jour</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mx-auto row justify-content-center col-md-8 col-sm-10 col-12 col-lg-7">
                                            <div class="demo-inline-spacing">
                                                <button class="btn btn-icon btn-danger" data-repeater-delete type="button">
                                                    <i data-feather="x" class="mr-25"></i>
                                                    <span>Supprimer dernier</span>
                                                </button>
                                                <button class="btn btn-icon btn-success" type="button" data-repeater-create>
                                                    <i data-feather="plus" class="mr-25"></i>
                                                    <span>Ajouter</span>
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
                                                    <i class="mr-1" data-feather='{{ isset($nutritions) ? "edit" : "download" }}'></i>
                                                    {{ isset($nutritions) ? 'Mettre à jour' : 'Enregistrer' }}
                                                </button>
                                            </div>
                                            <div class="text-center d-block d-md-none col-bg-5 col-lg-7 col-md-12 col-12">
                                                <button type="submit" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success mt-md-2 mr-md-2">
                                                    <i class="mr-1" data-feather='{{ isset($nutritions) ? "edit" : "download" }}'></i>
                                                    {{ isset($nutritions) ? 'Mettre à jour' : 'Enregistrer' }}
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
document.addEventListener('DOMContentLoaded', function () {

    const isEditMode = {{ isset($nutritions) ? 'true' : 'false' }};

    function initTouchSpin(element, options = {}) {
        element.TouchSpin(Object.assign({
            buttondown_class: 'btn btn-primary',
            buttonup_class: 'btn btn-primary',
            buttondown_txt: feather.icons['minus'].toSvg(),
            buttonup_txt: feather.icons['plus'].toSvg(),
            min: 0,
            max: 1000000
        }, options));
        element.removeClass('hide-until-loaded').show();
    }

    function showAndInitRow(row, previousMax = 0) {
        row.removeClass('d-none').addClass('d-block');
        const minInput = row.find('.min');
        const maxInput = row.find('.max');
        const selectUnit = row.find('.select5');
        const qteInput = row.find('.qte');
        const periodSelect = row.find('.period');
        const index = row.data('index');

        const existingMin = parseFloat(minInput.val());
        const existingMax = parseFloat(maxInput.val());
        let minVal, maxVal;

        if (isNaN(existingMin) || isNaN(existingMax)) {
            // 🆕 Nouvelle ligne : première ligne démarre à [0, 1]
            if (index === 0) {
                minVal = 0;
                maxVal = 1;
            } else {
                minVal = previousMax + 1;
                maxVal = minVal + 1;
            }
            minInput.val(minVal);
            maxInput.val(maxVal);

            // ✅ Première ligne : min VERROUILLÉ à 0, autres : min AUTOMATIQUE
            if (index === 0) {
                minInput.prop('readonly', true);
                minInput.css('background-color', '#f5f5f5');
                minInput.attr('title', 'La première ligne commence toujours à 0');
            } else {
                minInput.prop('readonly', true);
                minInput.css('background-color', '#f5f5f5');
                minInput.attr('title', 'Valeur calculée automatiquement');
            }
        } else {
            minVal = existingMin;
            maxVal = existingMax;

            // Mode édition : permettre les modifications
            minInput.prop('readonly', false);
            minInput.css('background-color', '');
            minInput.attr('title', '');
        }

        initTouchSpin(minInput, { min: minVal, max: 1000000 });
        initTouchSpin(maxInput, { min: minVal + 1, max: 1000000 });

        selectUnit.select2();

        // ✅ Gestionnaire pour le changement du min
        minInput.off('change.nutrition').on('change.nutrition', function() {
            const newMin = parseFloat($(this).val()) || 0;
            const currentRow = $(this).closest('[data-repeater-item]');
            const rowIndex = currentRow.data('index');
            const prevRow = currentRow.prev('[data-repeater-item]');
            const prevMax = prevRow.length ? parseFloat(prevRow.find('.max').val()) || 0 : -1;

            // ✅ Première ligne : min doit rester 0
            if (rowIndex === 0) {
                if (newMin !== 0) {
                    $(this).val(0);
                    toastr.warning('La première ligne doit commencer à 0', 'Valeur bloquée');
                    return;
                }
            } else {
                // ✅ Autres lignes : min doit être exactement prevMax + 1
                if (newMin !== prevMax + 1) {
                    $(this).val(prevMax + 1);
                    toastr.warning('Le minimum doit être exactement ' + (prevMax + 1) + ' (suite de la ligne précédente)', 'Valeur corrigée');
                    return;
                }
            }

            let currentMax = parseFloat(maxInput.val()) || newMin + 1;
            if(currentMax <= newMin) {
                currentMax = newMin + 1;
                maxInput.val(currentMax);
            }

            // ✅ Mise à jour immédiate des lignes suivantes
            updateNextRows(currentRow, currentMax);
        });

        // ✅ Gestionnaire pour le changement du max
        maxInput.off('change.nutrition').on('change.nutrition', function() {
            const newMax = parseFloat($(this).val()) || 0;
            const currentRow = $(this).closest('[data-repeater-item]');
            const currentMin = parseFloat(minInput.val()) || 0;

            if(newMax <= currentMin) {
                const correctedMax = currentMin + 1;
                $(this).val(correctedMax);
                toastr.warning('Le maximum doit être supérieur au minimum (' + currentMin + ')', 'Valeur corrigée');
                updateNextRows(currentRow, correctedMax);
            } else {
                // ✅ Mise à jour immédiate des lignes suivantes
                updateNextRows(currentRow, newMax);
            }
        });

        addDeleteButton(row);
    }

    function addDeleteButton(row) {
        row.find('.btn-delete-row').remove();
        const allRows = $('[data-repeater-item]');

        // Le bouton supprimer n'apparaît que si y'a plus d'une ligne
        if(allRows.length > 1) {
            const deleteBtn = $('<button type="button" class="btn btn-sm btn-icon btn-delete-row" style="position: absolute; right: 10px; top: 10px; z-index: 1001;"><i data-feather="trash-2" class="text-danger"></i></button>');
            row.css('position', 'relative');
            row.append(deleteBtn);

            feather.replace({ width: 14, height: 14 });

            deleteBtn.off('click').on('click', function(e) {
                e.preventDefault();
                deleteSpecificRow(row);
            });
        }
    }

    function deleteSpecificRow(rowToDelete) {
        const allRows = $('[data-repeater-item]');
        if(allRows.length <= 1) {
            toastr.error('Vous devez conserver au moins une ligne', 'Suppression impossible');
            return;
        }

        rowToDelete.slideUp(200, function() {
            $(this).remove();
            toastr.info('Ligne supprimée avec succès', 'Suppression');

            // ✅ Réindexer et valider cohérence
            $('[data-repeater-item]').each(function(i) {
                $(this).attr('data-index', i);
                $(this).find('[name^="invoice"]').each(function() {
                    const name = $(this).attr('name');
                    $(this).attr('name', name.replace(/invoice\[\d+\]/, `invoice[${i}]`));
                });
            });

            // ✅ Recalculer les plages d'âge pour cohérence
            let currentMax = -1;
            $('[data-repeater-item]').each(function(i) {
                const row = $(this);
                const minInput = row.find('.min');
                const maxInput = row.find('.max');

                if (i === 0) {
                    minInput.val(0);
                    let maxVal = parseFloat(maxInput.val());
                    if (isNaN(maxVal) || maxVal <= 0) {
                        maxVal = 1;
                    }
                    maxInput.val(maxVal);
                    currentMax = maxVal;
                } else {
                    const newMin = currentMax + 1;
                    let newMax = parseFloat(maxInput.val());

                    if (isNaN(newMax) || newMax <= newMin) {
                        newMax = newMin + 1;
                    }

                    minInput.val(newMin);
                    maxInput.val(newMax);
                    currentMax = newMax;
                }

                addDeleteButton(row);
            });
        });
    }

    let updating = false;
    function updateNextRows(currentRow, currentMax) {
        if(updating) return;
        updating = true;

        let prevMax = currentMax;
        const nextRows = currentRow.nextAll('[data-repeater-item]');

        nextRows.each(function(){
            const r = $(this);
            const nextMin = prevMax + 1;
            const currentMaxVal = parseFloat(r.find('.max').val()) || nextMin + 1;
            let nextMax = currentMaxVal;

            if(nextMax <= nextMin) {
                nextMax = nextMin + 1;
            }

            r.find('.min').val(nextMin);
            r.find('.max').val(nextMax);

            prevMax = nextMax;
        });

        updating = false;
    }

    $(window).on('load', function() {
        $('[data-repeater-item]').each(function(i){
            const row = $(this);
            row.attr('data-index', i);
            const prevRow = row.prev('[data-repeater-item]');
            const prevMax = prevRow.length ? parseFloat(prevRow.find('.max').val()) || 0 : -1;
            showAndInitRow(row, prevMax);
        });

        $('[data-repeater-create]').on('click', function(){
            setTimeout(function(){
                const allRows = $('[data-repeater-item]');
                allRows.each(function(i) {
                    $(this).attr('data-index', i);
                });

                const newRow = allRows.last();
                const prevRow = newRow.prev('[data-repeater-item]');
                const prevMax = prevRow.length ? parseFloat(prevRow.find('.max').val()) || 0 : -1;
                showAndInitRow(newRow, prevMax);

                // ✅ Réappliquer addDeleteButton sur toutes les lignes
                allRows.each(function() {
                    addDeleteButton($(this));
                });
            }, 50);
        });

        $('[data-repeater-delete]').on('click', function(e){
            e.preventDefault();
            const allRows = $('[data-repeater-item]');
            if(allRows.length <= 1) {
                toastr.error('Vous devez conserver au moins une ligne', 'Suppression impossible');
                return;
            }
            deleteSpecificRow(allRows.last());
        });
    });
});
</script>
@endsection

