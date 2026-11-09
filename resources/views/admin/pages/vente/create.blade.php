@extends('admin/templates/template')
@section('title','Nouvel vente')
@section('menu')
    @php
        $role = auth()->user()->type_user->libelle;
    @endphp
    @include($role.'.menu.menu')
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
                        <h2 class="float-left mb-0 content-header-title">Vente</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Ajouter une Vente
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
                                    <h3 class="card-title">Formulaire</h3>
                                </div>
                                <div class="card-body">
                                    <form class="form" >
                                        <div class="mx-auto row col-lg-8" >
                                            <div class="mx-auto text-center  col-bg-5 col-lg-8 col-md-12 col-12">
                                                <button id="CM" onclick="STForm('cabrel','{{ route('formulaire') }}','GET',null,null,null)" class="px-4 btn btn-secondary" type="button" data-toggle="modal" data-target="#ajout">
                                                    <i class="fa fa-user-plus"></i>
                                                </button>
                                            </div>
                                            <div id="client_creer" class="mx-auto col-md-6 col-12"></div>
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="id_produit">Type de vente</label>
                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group">
                                                        <select  class="select2 form-control" id="type" name="type_vente" required>
                                                            <option value="" class="" data-prix="0">
                                                                Choisir un type de produit
                                                            </option>
                                                             @foreach($types_produits as $type_produit)
                                                                <option class="product"
                                                                    value="{{$type_produit->id}}"
                                                                    {{$type_produit->id == old('type_produit') ? 'selected' : ''}}>
                                                                    {{($type_produit->nom)}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div id="error_client" class="d-none">
                                                            <small class="alert alert-danger">veuillez remplir ce champ</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mx-auto col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="id_produit">Nom Produit</label>
                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                    <div class="form-group" id="change_prod">
                                                        <select  class="select2 form-control" id="id_produit" name="produit" onchange="calcul_prix_unitaire('product','prix_unitaire');" required>
                                                            <option value="" class="product" data-prix="0">
                                                                Choisir un produit
                                                            </option>
                                                            @foreach($produits as $produit)
                                                                <option class="product"
                                                                    value="{{$produit->id}}" data-prix="{{$produit->prix_unitaire}}"
                                                                    {{$produit->id == old('produit') ? 'selected' : ''}}>
                                                                    {{ucfirst($produit->nom)}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div id="error_produit" class="d-none">
                                                            <small class="alert alert-danger">veuillez remplir ce champ</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mx-auto col-md-6 col-12">
                                                    <div class="form-group" style="display: inherit; text-align: center;">
                                                        <label for="qte" >Quantité</label>
                                                        <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                        <center>
                                                            <input type="number" class="text-center form-control col-6" min="1" id="qte" value="{{ old('qte') == null ? 1 : old('qte') }}" name="qte" required onkeyup="calculateTotal('qte','prix_unitaire2','total')"/>
                                                        </center>
                                                            <div id="qte" class="d-none" >
                                                                <small class="alert alert-danger">veuillez remplir ce champ</small>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="mx-auto col-md-6 col-12">
                                                    <div class="form-group" id="prix">
                                                            <label for="prix_unitaire">Prix unitaire</label>
                                                            <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                            <div class="input-group col-12 p-0 input-group-merge @error('prix_unitaire') is-invalid @enderror">
                                                                <input type="text" class="text-center @error('prix_unitaire') is-invalid @enderror form-control" placeholder="1,000" id="prix_unitaire" name="prix_unitaire2" onkeyup="format_montant('prix_unitaire');calculateTotal('qte','prix_unitaire2','total')" value="{{old('prix_unitaire2')}}" required/>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">FCFA</span>
                                                                </div>
                                                            </div>
                                                        <input type="text" id="prix_unitaire2" name="prix_unitaire" value="{{old('prix_unitaire')}}" hidden />
                                                        <div id="error_prix_unitaire" class="d-none">
                                                            <small class="alert alert-danger">veuillez remplir ce champ</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mx-auto col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="total">Total</label>
                                                        <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                        <div class="input-group input-group-merge disabled @error('total') is-invalid @enderror">
                                                            <input type="text" class="text-center @error('total')  is-invalid @enderror form-control"  placeholder="1,000" id="total" name="total2" onchange="format_montant('total');" value="0" required  />
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">FCFA</span>
                                                            </div>
                                                        @error('total')
                                                                <small class="alert alert-danger"> {{$message}} </small>
                                                        @enderror
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
                                                        <div id="error_date" class="d-none">
                                                            <small class="alert alert-danger">veuillez remplir ce champ</small>
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
                                                            <div id="error_paiement" class="d-none">
                                                             <small class="alert alert-danger">veuillez remplir ce champ</small>
                                                            </div>
                                                        </div>
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
                <div class="card d-none" id="liste">
                	<div class="card-header">
                		Liste d'enregistrement
                	</div>
                	<div class="card-body">
                        <form  id="form-enregistrement" method="POST" action="{{ route('vente.store') }}">
                            @csrf
                            <input type="hidden" name="tableau_donnees" id="tableau_donnees">
    		                <div class="table-responsive">
    		                    <table class="table">
    		                        <thead>
    		                            <tr>
    		                                <th>n°</th>
    		                                <th>Client</th>
    		                                <th>produit</th>
                                            <th>type de vente</th>
                                            <th>qte</th>
    		                                <th>prix unitaire</th>
    		                                <th>prix total</th>
    		                                <th>mode de paiement</th>
    		                                <th>date</th>
    		                                <th>Actions</th>
    		                            </tr>
    		                        </thead>
    		                        <tbody>

    		                        </tbody>
    		                    </table>
    		                </div>
                            <div class="mt-2 text-center col-12">
                                <button type="submit" class="btn btn-danger" id="viderTableau">effacer</button>
                                <button type="submit" class="btn btn-success"  id="enregistrer">enregistrer</button>
                            </div>
                        </form>
                	</div>

                </div>
	    </div>
	</div>
</div>
<div class="modal fade" id="ajout" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Formulaire d'ajout</h5>
         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <div id="cabrel">

            </div>
        </div>
      </div>
    </div>
</div>

@endsection
@section('javascript')
	<script type="text/javascript">
		document.querySelector('#venteAjouter').classList.add('active');

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
                response_ajax('prix_unitaire',[id_produit],'{{route('ajax_result_prix')}}','GET',true);
        }
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

        $(document).ready(function() {

            function resetFormulaire() {
                $('#id_produit').val('').trigger('change'); // Réinitialiser le select2
                $('#prix_unitaire').val('');
                $('#qte').val(1);
                $('#total').val('0');
                $('input[name="mode_paiement"]').prop('checked', false);
                var date = new Date().toISOString().split('T')[0]; // Récupère la date du jour au format AAAA-MM-JJ
                $('#date').val(date);
                // Ne pas réinitialiser le champ id_client
            }
		    // Lors de la soumission du formulaire
		   $('form').on('submit', function(e) {
                e.preventDefault(); // Empêche la soumission du formulaire par défaut

                // Supprime les anciens messages d'erreur
                $('.text-danger').remove();

                // Récupération des valeurs du formulaire
                let produit = $('#id_produit option:selected').text();
                let type_produit = $('#type option:selected').text();
                let produit_id = $('#id_produit').val();
                let client_id = $('#id_client').val();
                let qte = $('#qte').val();
                let nomClient = $('#id_client option:selected').text();
                let date = $('#date').val();
                let prixUnitaire = $('#prix_unitaire').val();
                let total = $('#total').val();
                let modePaiement = $('input[name="mode_paiement"]:checked').val();
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
                if (!qte) {
                    showError('error_qte');
                    valid = false;
                }
                if(!produit){
                   showError('error_produit');
                    valid = false;
                }
                if (!nomClient || client_id == '') {
                    showError('error_client');
                    valid = false;
                }else{
                    $('#id_client').attr('disabled', 'disabled');
                }
                if (!date) {
                    showError('error_date');
                    valid = false;
                }
                if (!prixUnitaire) {
                    showError('error_prix_unitaire');
                    valid = false;
                }
                if (!total) {
                    showError('error_total');
                    valid = false;
                }
                if (!modePaiement) {
                    showError('error_paiement');
                    valid = false;
                }

                // Si tous les champs sont valides, on ajoute la ligne au tableau
                if (valid) {
                    let rowCount = $('#liste tbody tr').length + 1;
                    let newRow = `<tr>
                                    <td>${rowCount}</td>
                                    <td>${nomClient}</td>
                                    <td>${produit}</td>
                                    <td>${type_produit}</td>
                                    <td>${qte}</td>
                                    <td>${prixUnitaire} FCFA</td>
                                    <td>${total} FCFA</td>
                                    <td>${modePaiement}</td>
                                    <td>${date}</td>
                                    <td class="d-none">${produit_id}</td>
                                    <td class="d-none">${client_id}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm delete-row">Supprimer</button>
                                    </td>
                                </tr>`;

                    // Ajouter la nouvelle ligne au tableau
                    $('#liste tbody').append(newRow);

                    // Afficher le tableau s'il est caché
                    $('#liste').removeClass('d-none').addClass('d-block');

                    // Réinitialiser le formulaire
                    resetFormulaire();
                }
            });

		    // Suppression d'une ligne spécifique
		    $(document).on('click', '.delete-row', function() {
		        $(this).closest('tr').remove();
		        // Ré-indexer les lignes après suppression
		        $('#liste tbody tr').each(function(index) {
		            $(this).find('td:first').text(index + 1);
		        });
		    });

		    // Bouton pour vider le tableau
		    $('#viderTableau').on('click', function() {
		        $('#liste tbody').empty();
		        $('#liste').removeClass('d-block').addClass('d-none');
		    });

		     $('#form-enregistrement').on('submit', function (e) {

                function getTableauDonnees() {
                    var donnees = [];
                    $('#liste tbody tr').each(function () {
                        // Récupérer les données en supprimant les espaces inutiles
                        var client = $(this).find('td:eq(1)').text().trim();
                        var produit = $(this).find('td:eq(2)').text().trim();
                        let type_vente = $(this).find('td:eq(3)').text().trim();
                        var qte = $(this).find('td:eq(4)').text().trim();
                        var prix_unitaire = $(this).find('td:eq(5)').text().trim();
                        var total = $(this).find('td:eq(6)').text().trim();
                        var mode_paiement = $(this).find('td:eq(7)').text().trim();
                        var date = $(this).find('td:eq(8)').text().trim();
                        let id_produit = $(this).find('td:eq(9)').text().trim();
                        let id_client = $(this).find('td:eq(10)').text().trim();
                        // Ajouter des vérifications si nécessaire pour s'assurer que les données ne sont pas vides
                        if (client && produit && prix_unitaire && total && mode_paiement && date) {
                            donnees.push({
                                client: client,
                                type: type_vente,
                                qte: qte,
                                produit: produit,
                                id_produit: id_produit,
                                prix_unitaire: prix_unitaire,
                                total: total,
                                mode_paiement: mode_paiement,
                                date: date,
                                id_client: id_client,
                            });
                        }
                    });
                    return donnees;
                }
                    e.preventDefault(); // Empêche la soumission par défaut du formulaire

                    // Récupérer les données du tableau
                    var tableauDonnees = getTableauDonnees();

                    // Convertir en JSON et assigner à l'input caché
                    $('#tableau_donnees').val(JSON.stringify(tableauDonnees));

                    // Soumettre le formulaire après avoir mis à jour l'input caché
                    this.submit(); // Soumettre le formulaire maintenant que les données ont été ajoutées
                });
            });
            STForm('client_creer','{{ route('client')}}','GET',null,null,null);

            // logique pour gérer le dynamisme du formulaire en fonction du choix du type de produit
            $('#type').on('change',function(){
                let val = $(this).find('option:selected').text();
                let id_type = $(this).val();
                let valNettoye = val.trim().replace(/\s+/g, ' '); // Supprime les espaces inutiles
                let valF = valNettoye.toLowerCase();
                if(valF == 'fiente'){
                    STForm('change_prod','{{ route('get_fiente')}}','GET',null,null,null);
                }else if(valF == 'animal'){
                    STForm('change_prod','{{ route('get_animal')}}','GET',null,null,[id_type]);
                } else if(valF == 'oeuf'){
                     STForm('change_prod','{{ route('get_animal_pondeur')}}','GET',null,null,[id_type]);
                }
            });

	</script>
@endsection
