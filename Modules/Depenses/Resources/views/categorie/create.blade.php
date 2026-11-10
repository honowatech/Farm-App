@extends('layouts.app')

@section('title')
{{'Nouveau type de dépense'}}
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
                        <h2 class="content-header-title float-left mb-0">{{ __('depenses::translated.Nouveau type de dépense') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('depenses::translated.Acceuil') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('depenses::translated.Ajouter un type de dépense') }}</li>
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
                                <form class="form mt-3" action="{{ route('categorie.store') }}" method="POST">
                                    @csrf
                                    <div class="row col-lg-10 mx-auto">
                                        <div class="col-md-8 col-12 mx-auto">
                                            <div class="form-group">
                                                <label>{{ __('depenses::translated.Type') }} <span class="text-danger">*</span></label>
                                                <select required id="type" name="id_type_depense" class="select2 form-control @error('id_type_depense') is-invalid @enderror">
                                                    <option value="">{{ __('depenses::translated.Choisir un type') }}</option>
                                                    <option value="ponctuel" selected>Ponctuel</option>
                                                    <option value="reguliere">Régulière</option>
                                                </select>
                                                @error('id_type_depense')
                                                    <div class="text-danger"><strong>{{ $message }}</strong></div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-12 m-auto">
                                            <div class="form-group">
                                                <label for="nom">{{ __('depenses::translated.Libellé') }}</label>
                                                <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                                                <div class="form-group">
                                                    <div class="input-group input-group-merge @error('nom') is-invalid @enderror">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"></span>
                                                        </div>
                                                        <input type="text" id="nom" class="form-control @error('nom') is-invalid @enderror" name="nom" placeholder="{{ __('depenses::translated.Nom') }}" value="{{ old('nom') }}" autofocus required>
                                                    </div>
                                                    @error('nom')
                                                    <small class="alert alert-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="prix_unitaire col-md-8 col-12 d-none py-1 mx-auto">
                                            <label for="prix_unitaire">{{ __('depenses::translated.Montant dépense') }} <span class="text-danger">*</span></label>
                                            <div class="input-group input-group-merge @error('animal') is-invalid @enderror">
                                                <input type="text" class="text-center @error('prix_unitaire') is-invalid @enderror form-control" placeholder="1,000" id="prix_unitaire" name="prix_unitaire2" onkeyup="format_montant('prix_unitaire')" value="{{ old('prix_unitaire2') }}" required/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">FCFA</span>
                                                </div>
                                            </div>
                                            <input type="text" id="prix_unitaire2" name="prix_unitaire" value="{{ old('prix_unitaire') }}" hidden />
                                            <small class="alert alert-danger d-none" id="erreur_montant">{{ __('depenses::translated.veuillez entrer le montant de la dépense') }}</small>
                                        </div>
                                        <div class="frequence col-md-8 col-12 mx-auto d-none">
                                            <div class="form-group">
                                                <label>{{ __('depenses::translated.Fréquence') }} <span class="text-danger">*</span></label>
                                                <select required id="frequence" name="frequence" class="select2 form-control @error('frequence') is-invalid @enderror">
                                                    <option selected value="">{{ __('depenses::translated.Choisir une fréquence') }}</option>
                                                    <option value="mensuel">{{ __('depenses::translated.Mensuel') }}</option>
                                                    <option value="hebdomadaire">{{ __('depenses::translated.Hebdomadaire') }}</option>
                                                    <option value="bimensuel">{{ __('depenses::translated.Bimensuel') }}</option>
                                                    <option value="trimestriel">{{ __('depenses::translated.Trimestriel') }}</option>
                                                    <option value="semestriel">{{ __('depenses::translated.Semestriel') }}</option>
                                                    <option value="annuel">{{ __('depenses::translated.Annuel') }}</option>
                                                    <option value="quotidien">{{ __('depenses::translated.Quotidien') }}</option>
                                                </select>
                                                @error('frequence')
                                                    <div class="text-danger"><strong>{{ $message }}</strong></div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-12 mx-auto">
                                            <div class="form-group">
                                                <label for="description">{{ __('depenses::translated.Description') }}</label>
                                                <span class="text-info cursor-pointer" title="Obligatoire">{{ __('depenses::translated.Optionnel') }}</span>
                                                <div class="form-group">
                                                    <textarea type="description" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="{{ __('depenses::translated.Description') }}" name="description" rows="3">{{ old('description') }}</textarea>
                                                    @error('description')
                                                        <small class="alert alert-danger">{{ $message }}</small>
                                                    @enderror
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
        document.querySelector('#CategorieListe').classList.add('active');
         $(document).ready(function() {
        // Fonction qui s'exécute quand le type est sélectionné
        $('#type').on('change', function() {
            var selectedType = $(this).val();

            if (selectedType === 'ponctuel') {
                // Si le type est "Ponctuel", masquer le champ fréquence
                $('.frequence').addClass('d-none');
                $('.prix_unitaire').addClass('d-none');
            } else if (selectedType === 'reguliere') {
                // Si le type est "Régulière", afficher le champ fréquence
                $('.frequence').removeClass('d-none');
                $('.prix_unitaire').removeClass('d-none');
            } else {
                // Si aucun type valide n'est sélectionné, masquer par défaut
                $('.frequence').addClass('d-none');
                 $('.prix_unitaire').addClass('d-none');
            }
        });

        // Masquer la fréquence au chargement initial si "Ponctuel" est sélectionné
        $('#type').trigger('change');
    });
    </script>
@endsection
