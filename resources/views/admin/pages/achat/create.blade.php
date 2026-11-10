@extends('layouts.app')
@section('title')
    {{'Nouvel Achat'}}
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
                        <h2 class="float-left mb-0 content-header-title">Achat</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Ajouter un Achat
                            </li>
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
                                </div>
                                <div class="card-body">
                                    <form class="form" action="{{route('achat.store')}}" method="POST">
                                    	@csrf
                                        <div class="mx-auto row col-lg-10">
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="id_produit">Aliment</label>
                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <select  class="select2 form-control" id="id_produit" name="matiere_premiere" onchange="call_ajax2();calcul_prix_unitaire('product','prix_unitaire');" required>
                                                            <option value="" class="product" data-prix="0">
                                                                Choisir un Aliment
                                                            </option>
                                                            @foreach($matieres_premieres as $matiere_premiere)
                                                                <option class="product"
                                                                    value="{{$matiere_premiere->id}}" data-prix="{{$matiere_premiere->prix_unitaire}}"
                                                                    {{$matiere_premiere->id == old('matiere_premiere') ? 'selected' : ''}}>
                                                                    {{ucfirst($matiere_premiere->nom_produit)}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('matiere_premiere')
                                                            <small class="alert alert-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="text-center form-group">
                                                    <label for="qte">Quantité</label>
                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>

                                                    <div class="input-group justify-content-center">
                                                        <input type="number"
                                                               class="text-center form-control col-md-6"
                                                               min="1"
                                                               id="qte"
                                                               value="{{ old('qte') ?? 1 }}"
                                                               name="qte"
                                                               required
                                                               onkeyup="calculateTotal('qte','prix_unitaire2','total')"
                                                        />
                                                        <!-- Sélecteur d'unité -->
                                                        <select id="unite" name="unite" class="form-control col-3" onchange="toggleSackWeight()">
                                                            <option value="kg" {{ old('unite') == 'kg' ? 'selected' : '' }}>KG</option>
                                                            <option value="g" {{ old('unite') == 'g' ? 'selected' : '' }}>Gramme</option>
                                                            <option value="sac" {{ old('unite') == 'sac' ? 'selected' : '' }}>Sac</option>
                                                        </select>
                                                    </div>
                                                    @error('qte')
                                                        <small class="alert alert-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="fournisseur">Fournisseur</label>
                                                    <span class="cursor-pointer text-info" title="Optionnel">Optionnel</span>
                                                    <select class="select2 form-control" id="id_fournisseur" name="fournisseur">
                                                        <option value="">
                                                            Choisir Le fournisseur
                                                        </option>
                                                    </select>
                                                    @error('fournisseur')
                                                        <small class="alert alert-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>


                                            <!-- Champ conditionnel pour le poids du sac -->
                                            <div class="mx-auto form-group col-md-6 col-12" id="sack-weight-field" style="display: none;">
                                                <label for="sack_weight">Poids par sac (KG)</label>
                                                <input type="number"
                                                       class="text-center form-control"
                                                       min="1"
                                                       id="sack_weight"
                                                       name="sack_weight"
                                                       value="{{ old('sack_weight') ?? '' }}"
                                                       placeholder="Entrez le poids en KG" />
                                            </div>
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                    <center>
                                                        <label for="prix_unitaire">Prix unitaire</label>
                                                        <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                        <div class="input-group text-center col-md-9 p-0 input-group-merge @error('prix_unitaire') is-invalid @enderror">
                                                            <input type="text" class="text-center @error('prix_unitaire') is-invalid @enderror form-control" placeholder="1,000" id="prix_unitaire" name="prix_unitaire2" onkeyup="format_montant('prix_unitaire');calculateTotal('qte','prix_unitaire2','total')" value="{{old('prix_unitaire2')}}" required/>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">FCFA</span>
                                                            </div>
                                                        </div>
                                                    </center>
                                                    <input type="text" id="prix_unitaire2" name="prix_unitaire" value="{{old('prix_unitaire')}}" hidden />
                                                    @error('prix_unitaire')
                                                            <small class="alert alert-danger"> {{$message}} </small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="total">Total</label>
                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="input-group input-group-merge disabled @error('total') is-invalid @enderror">
                                                        <input type="text" class="@error('total')  is-invalid @enderror form-control"  placeholder="1,000" id="total" name="total2" onchange="format_montant('total');" value="0" required readonly />
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">FCFA</span>
                                                        </div>
                                                    @error('total')
                                                            <small class="alert alert-danger"> {{$message}} </small>
                                                    @enderror
                                                    </div>
                                                </div>
                                            </div>
                                                @php
                                                    $row = 0;
                                                    $img = [
                                                        'espece' => 'app-assets/images/logo/espece.webp',
                                                        'mobilemoney' => 'app-assets/images/logo/mtn.svg',
                                                        'orangemoney' => 'app-assets/images/logo/orange.png',
                                                        'bank' => 'app-assets/images/logo/bank.png',
                                                    ];
                                                    $title = [
                                                        'mobilemoney' => 'Mtn MOMO',
                                                        'orangemoney' => 'Orange Money',
                                                        'espece' => 'Espèce',
                                                        'bank' => 'Paiement Bancaire'
                                                        ];
                                                @endphp
                                                <div class="mx-auto col-md-6 col-12">
                                                    <div class="form-group">
                                                        <center>
                                                            <label for="id_mode_paiement">Mode de paiement</label>
                                                            <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                        </center>
                                                        <div class="col-12">
                                                            <div class="mt-1 form-group d-flex">
                                                                @foreach($img as $index => $src)
                                                                <div class="mx-auto col-3" title="{{$title[$index]}}">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" id="{{$index}}" name="mode_paiement" class="m-auto mode_paiement custom-control-input"@if(old('mode_paiement')!= null && $index == old('mode_paiement')) checked @endif @if($index =='espece' && old('mode_paiement') == null) checked @endif value="{{$index}}">
                                                                            <label class="custom-control-label" for="{{$index}}">
                                                                                <img src="{{asset($src)}}" alt="avatar" style="position: absolute; bottom: -50%; top: -50%; height: 40px; width: 50px;">
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mx-auto col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="date">Date</label>
                                                        <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                        <div class="input-group input-group-merge @error('date')  is-invalid @enderror">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="mr-1" data-feather="calendar"></i></span>
                                                            </div>
                                                            <input class="form-control flatpickr-human-friendly @error('date')  is-invalid @enderror "  placeholder="January 01, 2024" tabindex="0" type="text" id="date" readonly="readonly" name="date" required value="{{old('date')}}">
                                                        </div>
                                                        @error('date')
                                                                <small class="alert alert-danger"> {{$message}} </small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row col-12">
                                            <div class="mx-auto text-center d-none d-md-block col-bg-5 col-lg-8 col-md-12 col-12">
                                                 <button type="reset" onclick="reset_form('id_produit')" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-danger mt-md-2 mr-md-4">
                                                    <i class="mr-1"></i> Effacer
                                               </button>
                                                <button type="submit" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success mt-md-2">
                                                    <i class="mr-1"></i> Enregistrer
                                                </button>
                                            </div>
                                            <div class="mx-auto text-center d-block d-md-none col-bg-5 col-lg-8 col-md-12 col-12">
                                                <button type="submit" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success mt-md-2 mr-md-2">
                                                    <i class="mr-1"></i> Enregistrer
                                                </button>
                                                <button type="reset" onclick="reset_form('id_produit')" class="mt-1 btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-danger mt-md-2">
                                                    <i class="mr-1"></i> Effacer
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
		document.querySelector('#AchatAjouter').classList.add('active');

        function calculateTotal(input_qte,input_prix_unitaire,input_total) {
            var input_qte = document.querySelector('#'+input_qte).value,
                input_prix_unitaire = document.querySelector('#'+input_prix_unitaire).value,
                input_total = document.querySelector('#'+input_total);
            result = input_qte * input_prix_unitaire ;
            result = result.toLocaleString('fr-FR', {
                    groupSize: 3,
                    useGrouping: true,
                });
            result =  result.replaceAll(/\s/g, ",")
            input_total.value = result
        }
        function call_ajax2(){
            var valeur = document.querySelector('#id_produit').value,
                id_produit = valeur == '' ? 0 : valeur;
                response_ajax('id_fournisseur',[id_produit],'{{route('ajax_result_fournisseur')}}','GET',true);
        }
        call_ajax2()
        function calcul_prix_unitaire(classes,prix){
            var options = document.querySelectorAll('.'+classes),
                result_input = document.querySelector('#'+prix);
            options.forEach((option) => {
                if(option.selected){
                    result_input.value = option.dataset.prix ;
                }
            });
            format_montant('prix_unitaire');
            calculateTotal('qte','prix_unitaire2','total');
        }
        function reset_form(id){
            var select = document.querySelector('#'+id),
                evenement = new Event('change');
                setTimeout(function(){select.dispatchEvent(evenement)},100)
        }
        calcul_prix_unitaire('product','prix_unitaire');
          // Fonction pour gérer l'affichage du champ de poids du sac
    function toggleSackWeight() {
        const unite = document.getElementById('unite').value;
        const sackWeightField = document.getElementById('sack-weight-field');
        const selectedUnit = document.getElementById('selected-unit');

        // Affiche ou masque le champ de poids du sac
        if (unite === 'sac') {
            sackWeightField.style.display = 'block';
        } else {
            sackWeightField.style.display = 'none';
        }

        // Met à jour l'unité sélectionnée dans le texte
        selectedUnit.innerText = unite === 'sac' ? 'SAC' : unite.toUpperCase();
    }

    // Initialisation au chargement de la page
    document.addEventListener('DOMContentLoaded', () => {
        toggleSackWeight();
    });
	</script>
@endsection
