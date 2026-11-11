@extends('layouts.app')

@section('title')
{{'Détails sur '}} {{name($agent->nom)}}
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
                            <h2 class="float-left mb-0 content-header-title">Infos sur {{name($agent->nom)}}</h2>
                            <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('agent.index')}}">
                                    Liste des agents
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Infos sur {{name($agent->nom)}}
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
                                                            <h4 class="mb-0 ml-1">{{name($agent->nom)}}</h4>
                                                            <span class="ml-1 card-text" title="{{$agent->adresse}}">{{cutText($agent->adresse,15)}}</span>
                                                        </div>
                                                        <div class="flex-wrap d-flex">
                                                            <a href="{{route('agent.edit',$agent->id)}}" class="ml-1 btn btn-secondary btn-gradient-secondary btn-sm">Modifier</a>
                                                            <button class="btn ml-1 btn-sm @if($agent->statut == 1) btn-gradient-danger btn-danger  @else btn-gradient-success btn-success @endif " onclick="actions('form_active', @if($agent->statut == 1) 'Désactiver' @else 'Activer' @endif ,'{{$agent->nom}}')">
                                                                    @if($agent->statut == 1)
                                                                        Désactiver
                                                                    @else
                                                                        Activer
                                                                    @endif
                                                                <form id="form_active" action="{{route('agent.destroy',$agent->id)}}" method="POST">
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
                                                    <p class="mb-0 card-text">{{name($agent->nom)}}</p>
                                                </div>
                                                <div class="flex-wrap d-flex my-50">
                                                    <div class="user-info-title">
                                                        <i data-feather="phone" class="mr-1"></i>
                                                        <span class="mb-0 card-text user-info-title font-weight-bold">Téléphone</span>
                                                    </div>
                                                    <p class="mb-0 card-text">
                                                        <span>
                                                        	{{phone($agent->telephone)}}
                                                        </span></p>
                                                </div>
                                                <div class="flex-wrap d-flex my-50">
                                                    <div class="user-info-title">
                                                         @if($agent->statut == 0)
                                                            <i data-feather="x-circle" class="mr-1"></i>
                                                        @else
                                                            <i data-feather="check-circle" class="mr-1"></i>
                                                        @endif
                                                        <span class="mb-0 card-text user-info-title font-weight-bold">Status</span>
                                                    </div>
                                                    <span class="badge badge-pill badge-glow @if($agent->statut == 0)badge-danger @else badge-success @endif">
	                                            		@if($agent->statut == 0)
			                                             	Désactivé
			                                            @else
			                                            	Actif
			                                            @endif
                                                	</span>

                                                </div>
	                                            @if($agent->telephone_secondaire  != null)
	                                                <div class="flex-wrap d-flex my-50">
	                                                    <div class="user-info-title">
	                                                        <i data-feather="phone" class="mr-1"></i>
	                                                        <span class="mb-0 card-text user-info-title font-weight-bold">Téléphone 2</span>
	                                                    </div>
	                                                    <p class="mb-0 card-text">
	                                                        <span>
	                                                        	{{phone($agent->telephone_secondaire)}}
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

                </div>
                    <!-- zone Timeline & Permissions Ends -->
            </section>
        </div>
    </div>
</div>
    <!-- END: Content-->
@endsection
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
	</script>
@endsection
