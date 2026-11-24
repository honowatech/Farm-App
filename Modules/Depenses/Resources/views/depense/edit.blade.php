@extends('layouts.app')

@section('title', 'Modifier une dépense')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <h2 class="content-header-title mb-0">Modifier une dépense</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('depenses.index') }}">Dépenses</a></li>
                        <li class="breadcrumb-item active">Modification</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-body">
            @if($errors->has('error'))
                <div class="alert alert-danger">{{ $errors->first('error') }}</div>
            @endif
            <div class="card">
                <div class="card-header"><h3 class="card-title">Informations de la dépense</h3></div>
                <div class="card-body">
                    <form data-tour-step="fields" action="{{ route('depenses.update', $depense->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="type">Catégorie <span class="text-danger">*</span></label>
                            <select id="type" name="id_type_depense" class="form-control @error('id_type_depense') is-invalid @enderror" required>
                                <option value="">Choisir une catégorie</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->id }}" @selected(old('id_type_depense', $depense->id_categorie) == $categorie->id)>{{ $categorie->nom }}</option>
                                @endforeach
                            </select>
                            @error('id_type_depense')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="nature_depense">Motif <span class="text-danger">*</span></label>
                            <input id="nature_depense" name="nature_depense" class="form-control @error('nature_depense') is-invalid @enderror" maxlength="255" value="{{ old('nature_depense', $depense->motif_depense) }}" required>
                            @error('nature_depense')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="prix_unitaire">Montant <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input id="prix_unitaire" type="text" class="form-control @error('prix_unitaire') is-invalid @enderror" value="{{ old('prix_unitaire2', number_format((float) $depense->montant, 0, '.', ',')) }}" onkeyup="format_montant('prix_unitaire')" required>
                                <div class="input-group-append"><span class="input-group-text">FCFA</span></div>
                            </div>
                            <input id="prix_unitaire2" type="hidden" name="prix_unitaire" value="{{ old('prix_unitaire', $depense->montant) }}">
                            @error('prix_unitaire')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="date">Date <span class="text-danger">*</span></label>
                            <input id="date" type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', \Carbon\Carbon::parse($depense->date_depense)->format('Y-m-d')) }}" required>
                            @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="fournisseur">Fournisseur</label>
                            <input id="fournisseur" name="fournisseur" class="form-control @error('fournisseur') is-invalid @enderror" maxlength="255" value="{{ old('fournisseur', $depense->fournisseur === 'autre' ? '' : $depense->fournisseur) }}">
                            @error('fournisseur')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $depense->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="hidden" name="use_caisse" value="0">
                                <input type="checkbox" class="custom-control-input" id="useCaisseSwitch" name="use_caisse" value="1" @checked(old('use_caisse', $depense->id_compte ? '1' : '0') === '1')>
                                <label class="custom-control-label" for="useCaisseSwitch">Déduire le montant d'une caisse</label>
                            </div>
                        </div>
                        <div class="form-group {{ old('use_caisse', $depense->id_compte ? '1' : '0') === '1' ? '' : 'd-none' }}" id="caisseSelection">
                            <label for="caisse">Caisse <span class="text-danger">*</span></label>
                            <select id="caisse" name="id_caisse" class="form-control @error('id_caisse') is-invalid @enderror">
                                <option value="">Choisir une caisse</option>
                                @foreach($caisses as $caisse)
                                    <option value="{{ $caisse->id }}" @selected(old('id_caisse', $depense->id_compte) == $caisse->id)>{{ $caisse->nom }}</option>
                                @endforeach
                            </select>
                            @error('id_caisse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div data-tour-step="actions" class="d-flex justify-content-end">
                            <a href="{{ route('depenses.index') }}" class="btn btn-outline-secondary mr-1">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
    function remplir_montant(id) {
        var target = document.querySelector('#' + id + '2');
        target.value = document.querySelector('#' + id).value.replaceAll(',', '').replaceAll(' ', '');
    }

    function format_montant(id) {
        var value = document.querySelector('#' + id).value.replaceAll(' ', '').replaceAll(',', '');
        var number = parseInt(value, 10);
        document.querySelector('#' + id).value = Number.isNaN(number) ? '' : number.toLocaleString('fr-FR').replaceAll(/\s/g, ',');
        remplir_montant(id);
    }

    document.getElementById('useCaisseSwitch').addEventListener('change', function () {
        document.getElementById('caisseSelection').classList.toggle('d-none', !this.checked);
        var select = document.getElementById('caisse');
        select.required = this.checked;
        select.disabled = !this.checked;
    });

    document.getElementById('useCaisseSwitch').dispatchEvent(new Event('change'));
</script>
@endsection