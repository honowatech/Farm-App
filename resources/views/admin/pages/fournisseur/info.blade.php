@extends('admin/templates/template')

@section('title')
{{'Détails sur '}} {{name($fournisseur->nom)}}
@endsection
@section('menu')
    @php
        $role = auth()->user()->type_user->libelle;
    @endphp
    @include($role.'.menu.menu')
@endsection
@section('contenu')

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
            <div class="content-wrapper">
    	       <div class="mb-2 content-header-left col-md-12 col-12">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="float-left mb-0 content-header-title">Infos sur {{name($fournisseur->nom)}}</h2>
                            <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('fournisseur.index')}}">
                                    Liste des Fournisseurs
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Infos sur {{name($fournisseur->nom)}}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="app-user-view">
                    <!-- User Card & Plan Starts -->
                    <div class="row">
                        <!-- User Card starts-->
                        <div class="col-md-12">
                            <div class="card user-card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-bg-6 col-lg-6 d-flex flex-column justify-content-between border-container-lg">
                                            <div class="user-avatar-section">
                                                <div class="d-flex justify-content-start">
                                                    <img class="rounded img-fluid" src="{{asset('app-assets/images/avatars/user.png')}}" height="90" width="90" alt="User avatar" />
                                                    <div class="ml-1 d-flex flex-column">
                                                        <div class="mb-1 user-info">
                                                            <h4 class="mb-0 ml-1">{{name($fournisseur->nom)}}</h4>
                                                            <span class="ml-1 card-text" title="{{$fournisseur->adresse}}">{{cutText($fournisseur->adresse,15)}}</span>
                                                        </div>
                                                        <div class="flex-wrap d-flex">
                                                            <a href="{{route('fournisseur.edit',$fournisseur->id)}}" class="ml-1 btn btn-secondary btn-gradient-secondary btn-sm">Modifier</a>
                                                            <button class="btn ml-1 btn-sm @if($fournisseur->statut == 1) btn-gradient-danger btn-danger  @else btn-gradient-success btn-success @endif " onclick="actions('form_active', @if($fournisseur->statut == 1) 'Désactiver' @else 'Activer' @endif ,'{{$fournisseur->nom}}')">
                                                                    @if($fournisseur->statut == 1)
                                                                        Désactiver
                                                                    @else
                                                                        Activer
                                                                    @endif
                                                                <form id="form_active" action="{{route('fournisseur.destroy',$fournisseur->id)}}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-0 mt-2 col-bg-6 col-lg-6 mt-xl-0">
                                            <div class="user-info-wrapper">
                                                <div class="flex-wrap d-flex d-sm-block d-sm-flex d-none">
                                                    <div class="user-info-title">
                                                        <i data-feather="user" class="mr-1"></i>
                                                        <span class="mb-0 card-text user-info-title font-weight-bold">Noms</span>
                                                    </div>
                                                    <p class="mb-0 card-text">{{name($fournisseur->nom)}}</p>
                                                </div>
                                                <div class="flex-wrap d-flex my-50">
                                                    <div class="user-info-title">
                                                        <i data-feather="phone" class="mr-1"></i>
                                                        <span class="mb-0 card-text user-info-title font-weight-bold">Téléphone</span>
                                                    </div>
                                                    <p class="mb-0 card-text">
                                                        <span>
                                                        	{{phone($fournisseur->telephone)}}
                                                        </span></p>
                                                </div>
                                                <div class="flex-wrap d-flex my-50">
                                                    <div class="user-info-title">
                                                         @if($fournisseur->statut == 0)
                                                            <i data-feather="x-circle" class="mr-1"></i>
                                                        @else
                                                            <i data-feather="check-circle" class="mr-1"></i>
                                                        @endif
                                                        <span class="mb-0 card-text user-info-title font-weight-bold">Status</span>
                                                    </div>
                                                    <span class="badge badge-pill badge-glow @if($fournisseur->statut == 0)badge-danger @else badge-success @endif">
	                                            		@if($fournisseur->statut == 0)
			                                             	Désactivé
			                                            @else
			                                            	Actif
			                                            @endif
                                                	</span>

                                                </div>
	                                            @if($fournisseur->telephone_secondaire  != null)
	                                                <div class="flex-wrap d-flex my-50">
	                                                    <div class="user-info-title">
	                                                        <i data-feather="phone" class="mr-1"></i>
	                                                        <span class="mb-0 card-text user-info-title font-weight-bold">Téléphone 2</span>
	                                                    </div>
	                                                    <p class="mb-0 card-text">
	                                                        <span>
	                                                        	{{phone($fournisseur->telephone_secondaire)}}
	                                                        </span></p>
	                                                </div>
	                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- zone Timeline & Permissions Starts -->
                    <div class="row">

                        <!-- zone Permissions Starts -->
                        <div class="col-md-12">
                            <!-- zone Permissions -->
                            <div class="card">
                                <div class="card-header row" style="gap: 2rem;">
                                    <h4 class="card-title">Matieres prémières Disponibles ( {{$fournisseur->infos->count()}} )</h4>
                                    <div class="">
                                        <button type="button" id="add_matiere" class="btn btn-gradient-secondary btn-secondary" data-toggle="modal" data-target="#select2InModal">
                                            Ajouter des Matieres prémières
                                        </button>
                                    </div>
                                </div>
                                <!-- modale de sélection des viles -->
                                <div class="text-left modal fade" id="select2InModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel1">Modale de Sélection</h4>
                                                <button type="button" class="m-0 close" data-dismiss="modal" aria-label="Close">
                                                	<span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            @php
                                               $ids_pris = $fournisseur->infos->pluck('id_matiere_premiere')->toarray();
                                            @endphp
                                        <div class="modal-body">
                                            <p>Sélectionner un ou plusiers Matieres prémières</p>
                                           	<label for="id_details">Choisir des detailss</label>
                                            <div class="mb-0 form-group" id="div_id_details">
                                               <select class=" @error('id_details')  is-invalid @enderror select2 form-control" id="select_details" multiple='multiple' value = "" onchange="remplir_id_detail()" required>
                                                    @foreach($matieres_premieres as $matiere_premiere)
                                                    	@if(!in_array($matiere_premiere->id, $ids_pris))
	                                                        <option value="{{$matiere_premiere->id}}" @if(in_array($matiere_premiere->id,explode(',', old('id_details')))) selected @endif>
	                                                            {{$matiere_premiere->nom_produit}}
	                                                        </option>
                                                    	@endif
                                                    @endforeach
                                                </select>
                                                @error('id_details')
                                                	<small class="alert alert-danger">{{ $message }}</small>
                                                	<script type="text/javascript">
                                                		document.querySelector('#add_matiere').click()
                                                	</script>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="modal-footer d-flex justify-content-around">
    										<button type="button" class="mx-auto col-md-5 col-10 btn btn-gradient-danger btn-danger" data-dismiss="modal">Annuler</button>
                                            <form class="p-0 m-0 mx-auto destroy_form col-md-5 col-10" action="{{route('detail_fournisseur.store')}}" method="POST">
                                                @csrf
                                                <input type="hidden" value = "{{old('id_details')}}" name="id_details" id="id_details" required>
                                                <input type="hidden" value = "{{$fournisseur->id}}" name="id_fournisseur" id="id_fournisseur" required>
                                                <button type="submit" class="col-12 btn btn-gradient-success btn-success"> Valider </button>
                                            </form>
                                       	</div>
                                    </div>
                                </div>
                             </div>
                            <!-- fin modale de sélection des viles -->
                            <div class="table-responsive">
                                <table class="table table-striped table-borderless">
                                    <thead class="thead-light">
                                        <tr class="text-center">
                                            <th>N°</th>
                                            <th>Nom</th>
                                            <th>enlever</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            @php
                                                $row=1
                                            @endphp
                                            @foreach($fournisseur->infos as $details)
                                                <tr class="text-center">
                                                    <td>
                                                        {{$row}}
                                                    </td>
                                                    <td>
                                                        {{$details->matiere_premiere->nom_produit}}
                                                        ({{StockMatierepremiere($details->matiere_premiere)['valeur_en_stock']}})
                                                    </td>
                                                    <td>
                                                        <div class="m-auto cursor-pointer w-25" title="Enlever le details de {{$fournisseur->nom}}" data-toggle="modal" onclick="actions('form_delete_{{$details->id}}', 'Enlever' ,'{{$details->matiere_premiere->nom_produit}}')">

                                                        <i data-feather='x-circle' class="cursor-pointer text-danger" style="width: 24px; height: 24px;"></i>
                                                            <form id="form_delete_{{$details->id}}" action="{{route('detail_fournisseur.destroy',$details->id)}}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>

                                                        </div>
                                                    </td>
                                                </tr>

                                            @php
                                                $row++
                                            @endphp
                                            @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                        <!-- zone Permissions Ends -->
                </div>
                    <!-- zone Timeline & Permissions Ends -->
            </section>
        </div>
    </div>
</div>
    <!-- END: Content-->
@endsection
@if(auth()->user()->type_user->libelle === 'admin')
    @include('admin/menu/menu')
@elseif(auth()->user()->type_user->libelle === 'agent')
    @include('agent/menu/menu')
@endif
@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
	<script type="text/javascript">
        function remplir_id_detail(){
            $(document).ready(function() {
              // Récupération des données sélectionnées
              const select_details= $("#select_details").val();

              // Affichage des données sélectionnées dans la console
              valeur = '';
              virgule = ','
              for (var i = select_details.length - 1; i >= 0; i--) {
                    i == 0 ? virgule = '' : virgule = ',';
                 valeur += select_details[i]+virgule;
              }
                document.querySelector('#id_details').value = valeur;
                // console.log(document.querySelector('#id_details').value)
            });
        }
        function  put_details_id(id){
            var matieres  = document.querySelectorAll('.id_details');
            for (i = 0; i < matieres.length; i++) {
                matieres[i].value = id;
            }
        }
        @error('id_details')
            document.querySelector('#add_matiere').click()
        @enderror
            document.querySelector('#FournisseurListe').classList.add('active');
	</script>
@endsection
@if(auth()->user()->type_user->libelle === 'admin')
    @include('admin/menu/menu')
@elseif(auth()->user()->type_user->libelle === 'agent')
    @include('agent/menu/menu')
@endif
