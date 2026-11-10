@extends('layouts.app')

@section('title')
{{'Détails sur '}} {{$user->name}}
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
                            <h2 class="float-left mb-0 content-header-title">Infos sur {{$user->name}}</h2>
                            <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route(session()->get("info")[0].'.index')}}">
                            @if(session()->get('info')[0]  == 'client')
                                    Liste des Clients
                            @else
                                    Liste des Utilisateurs
                            @endif
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Infos sur {{$user->name}}
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
                                                            <h4 class="mb-0 ml-1">{{$user->name}}</h4>
                                                            <span class="ml-1 card-text">{{$user->email}}</span>
                                                        </div>
                                                        <div class="flex-wrap d-flex">
                                                            <a href="{{route('user.edit',$user->id)}}" class="ml-1 btn btn-secondary btn-gradient-secondary btn-sm">Modifier</a>
                                                        @if(isset($client))
                                                            <button class="btn ml-1 btn-sm @if($user->statut == 1) btn-gradient-danger btn-danger  @else btn-gradient-success btn-success @endif " onclick="actions('form_active', @if($user->statut == 1) 'Désactiver' @else 'Activer' @endif ,'{{name($user->name)}}')">
                                                                    @if($user->statut == 1)
                                                                        Désactiver
                                                                    @else
                                                                        Activer
                                                                    @endif
                                                                <form id="form_active" action="{{route('client.destroy',$client->id)}}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </button>
                                                        @else
                                                            <button class="btn ml-1 btn-sm @if($user->statut == 1) btn-gradient-danger btn-danger  @else btn-gradient-success btn-success @endif " onclick="actions('form_active', @if($user->statut == 1) 'Désactiver' @else 'Activer' @endif ,'{{name($user->name)}}')">
                                                                    @if($user->statut == 1)
                                                                        Désactiver
                                                                    @else
                                                                        Activer
                                                                    @endif
                                                                <form id="form_active" action="{{route('user.destroy',$user->id)}}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </button>
                                                        @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-0 mt-2 col-bg-6 col-lg-6 mt-xl-0">
                                            <div class="user-info-wrapper">
                                            @if(session()->get('info')[0]  == 'client')
                                                <div class="flex-wrap d-flex my-50">
                                                    <div class="user-info-title">
                                                        <i data-feather="phone" class="mr-1"></i>
                                                        <span class="mb-0 card-text user-info-title font-weight-bold">Téléphone</span>
                                                    </div>
                                                    <p class="mb-0 card-text">
                                                        <span class="d">+237</span>
                                                        {{session()->get('info')[1]->telephone}}</p>
                                                </div>
                                            @endif
                                                <div class="flex-wrap d-flex d-sm-block d-sm-flex d-none">
                                                    <div class="user-info-title">
                                                        <i data-feather="user" class="mr-1"></i>
                                                        <span class="mb-0 card-text user-info-title font-weight-bold">Noms</span>
                                                    </div>
                                                    <p class="mb-0 card-text">{{$user->name}}</p>
                                                </div>
                                                <div class="flex-wrap d-flex my-50">
                                                    <div class="user-info-title">
                                                         @if($user->statut == 0)
                                                            <i data-feather="x-circle" class="mr-1"></i>
                                                        @else
                                                            <i data-feather="check-circle" class="mr-1"></i>
                                                        @endif
                                                        <span class="mb-0 card-text user-info-title font-weight-bold">Status</span>
                                                    </div>
                                                    <span class="badge badge-pill badge-glow @if($user->statut == 0)badge-danger @else badge-success @endif">
	                                            		@if($user->statut == 0)
			                                             	Désactivé
			                                            @else
			                                            	Actif
			                                            @endif
                                                	</span>

                                                </div>
                                                @php
                                                    $table = ['secondary','danger','warning','info','light-success','primary','dark'];
                                                @endphp
                                                <div class="flex-wrap d-flex my-50">
                                                    <div class="user-info-title">
                                                        <i data-feather="award" class="mr-1"></i>
                                                        <span class="mb-0 card-text user-info-title font-weight-bold">Rôle</span>
                                                    </div>
                                                    <span class="badge badge-pill badge-glow badge-{{ $table[($user->type_user->id + 4) % count($table)] }}">
                                                        {{ucfirst($user->type_user->libelle)}}
                                                    </span>
                                                </div>
                                                <div class="flex-wrap d-flex my-50">
                                                    <div class="user-info-title">
                                                        <i data-feather="flag" class="mr-1"></i>
                                                        <span class="mb-0 card-text user-info-title font-weight-bold">Pays</span>
                                                    </div>
                                                    <p class="mb-0 card-text">{{'Cameroun'}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /User Card Ends-->
                        <!-- /Plan CardEnds -->
                    </div>
                    <!-- User Card & Plan Ends -->
                @if(!isset($client))
                    <!-- User Timeline & Permissions Starts -->
                    <div class="row">
                        <!-- User Permissions Starts -->
                        <div class="mx-auto col-md-7">
                            <!-- User Permissions -->
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Permissions</h4>
                                </div>
                                <p class="ml-2 card-text">Permission accordées Au rôle " {{ucfirst($user->type_user->libelle)}} " </p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-borderless">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Module</th>
                                                <th>Lecture</th>
                                                <th>Ecriture</th>
                                                <th>Création</th>
                                                <th>Suppression</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td> {{$user->type_user->libelle}} </td>
                                                <td>
                                                    <div class="custom-control custom-checkbox custom-control-dark">
                                                        <input type="checkbox" class="custom-control-input" id="admin-read"
                                                        @if( $user->type_user->id == 1 || $user->type_user->id == 2 || $user->type_user->id == 3 || $user->type_user->id == 4)
                                                        	checked
                                                        @endif
                                                        disabled />
                                                        <label class="custom-control-label" for="admin-read"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-checkbox custom-control-dark">
                                                        <input type="checkbox" class="custom-control-input" id="admin-write" disabled
                                                        @if( $user->type_user->id == 1 || $user->type_user->id == 2 || $user->type_user->id == 3 || $user->type_user->id == 4)
                                                        	checked
                                                        @endif />
                                                        <label class="custom-control-label" for="admin-write"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-checkbox custom-control-dark">
                                                        <input type="checkbox" class="custom-control-input" id="admin-create" disabled
                                                        @if( $user->type_user->id == 1 || $user->type_user->id == 2 || $user->type_user->id == 4)
                                                        	checked
                                                        @endif />
                                                        <label class="custom-control-label" for="admin-create"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-checkbox custom-control-dark">
                                                        <input type="checkbox" class="custom-control-input" id="admin-delete" disabled
                                                        @if( $user->type_user->id == 1 || $user->type_user->id == 2)
                                                        	checked
                                                        @endif/>
                                                        <label class="custom-control-label" for="admin-delete"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /User Permissions -->
                        </div>
                        <!-- User Permissions Ends -->
                    </div>
                    <!-- User Timeline & Permissions Ends -->
                @else
                    @include('admin.pages.client.info')
                @endif
                </section>

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
@section('javascript')
	<script type="text/javascript">
        @if(session()->get('info')[0] =='client')
            document.querySelector('#ClientListe').classList.add('active');
        @else
            document.querySelector('#UserListe').classList.add('active');
        @endif
	</script>
@endsection
