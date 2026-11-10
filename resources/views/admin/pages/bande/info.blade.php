@extends('layouts.app')
@section('title')
{{'Infos sur la bande ' . $bande->nom}}
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
                        <h2 class="float-left mb-0 content-header-title">Détails de la Bande</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Accueil</a></li>
                                <li class="breadcrumb-item"><a href="{{route('bande.index')}}">Liste des bandes</a></li>
                                <li class="breadcrumb-item active">{{ucfirst($bande->nom)}}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	    <div class="content-body">
			<section id="bande-details">
			    <div class="row">
			        <!-- Carte Informations Générales -->
			        <div class="col-lg-8 col-12">
			            <div class="card card-user-timeline">
			                <div class="card-header">
			                    <div class="d-flex align-items-center">
			                        <i data-feather="list" class="user-timeline-title-icon"></i>
			                        <h4 class="card-title">Informations sur {{ucfirst($bande->nom)}}</h4>
			                    </div>
			                </div>
			                <div class="card-body">
			                    <ul class="mb-0 timeline ml-50">
			                        <li class="timeline-item">
			                            <span class="timeline-point timeline-point-info timeline-point-indicator"></span>
			                            <div class="timeline-event">
			                                <h6>Date de lancement</h6>
			                                <p>Le <span class="badge badge-glow badge-secondary">{{ $bande->date_lancement_bande->format('d/m/Y') }}</span> à <span class="badge badge-glow badge-secondary">{{ $bande->date_lancement_bande->format('H:i') }}</span></p>
			                            </div>
			                        </li>
			                        <li class="timeline-item">
			                            <span class="timeline-point timeline-point-info timeline-point-indicator"></span>
			                            <div class="timeline-event">
			                                <h6>Bâtiment / Salle</h6>
			                                <p>{{ $bande->batiment->nom ?? 'Aucun bâtiment associé' }}</p>
			                            </div>
			                        </li>
			                        <li class="timeline-item">
			                            <span class="timeline-point timeline-point-info timeline-point-indicator"></span>
			                            <div class="timeline-event">
			                                <h6>Type d'animal</h6>
			                                <p class="mb-0">{{ $bande->animal->nom ?? 'Aucun type d\'animal associé' }}</p>
			                            </div>
			                        </li>
			                        <li class="timeline-item">
			                            <span class="timeline-point timeline-point-info timeline-point-indicator"></span>
			                            <div class="timeline-event">
			                                <h6>Âge au lancement</h6>
			                                <p class="mb-0">{{ $bande->age_depart }} {{ ucfirst($bande->unite_age_depart) }}(s)</p>
			                            </div>
			                        </li>
			                        <li class="timeline-item">
                                        <span class="timeline-point timeline-point-info timeline-point-indicator"></span>
                                        <div class="timeline-event">
                                            <h6>Âge actuel</h6>
                                            {{-- L'accesseur `age_formate` fait tout le travail pour nous --}}
                                            <p class="mb-0">{{ $bande->age_formate }} ({{ $bande->age_en_jours }} jours)</p>
                                        </div>
                                    </li>
			                        <li class="timeline-item">
			                            <span class="timeline-point timeline-point-info timeline-point-indicator"></span>
			                            <div class="timeline-event">
			                                <h6>Nombre initial de sujets</h6>
			                                <p class="mb-0">{{$bande->nbre_sujet}}</p>
			                            </div>
			                        </li>
			                        <li class="timeline-item">
			                            <span class="timeline-point timeline-point-info timeline-point-indicator"></span>
			                            <div class="timeline-event">
			                                <h6>Nombre actuel de sujets</h6>
			                                {{-- Note : ce champ devrait probablement être mis à jour lors des enregistrements de pertes --}}
			                                <p class="mb-0">{{$bande->nbre_sujet}}</p>
			                            </div>
			                        </li>
			                    </ul>
			                </div>
			            </div>
			        </div>
			        <!--/ Carte Informations Générales -->

			        <!-- Carte Activités et Statut -->
			        <div class="col-lg-4 col-md-6 col-12">
			            <div class="card card-transaction">
			                <div class="card-header">
			                    <h4 class="card-title">Activités Récentes</h4>
			                </div>
			                <div class="card-body">
			                    <div class="transaction-item">
								    <div class="media">
								        <div class="rounded avatar bg-light-primary">
								            <div class="avatar-content">
								                <i data-feather="pocket" class="avatar-icon font-medium-3"></i>
								            </div>
								        </div>
								        <div class="media-body">
                                            <h6 class="transaction-title">Nutrition</h6>
                                            <small class="mb-1 d-block">
                                                Statut d'hier :
                                                @php
                                                    $statutHier = $bande->statut_nutrition_hier; // Utilise le nouvel accesseur fiable
                                                    $badgeClass = [
                                                        'Complet' => 'badge-light-success',
                                                        'Incomplet' => 'badge-light-danger',
                                                        'Aucune' => 'badge-light-secondary',
                                                        'Plan non défini' => 'badge-light-warning',
                                                    ][$statutHier];
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $statutHier }}</span>
                                            </small>

                                            <!-- WIDGET DE NUTRITION D'AUJOURD'HUI -->
                                            @if ($bande->statut_bande == 'terminee')
                                                <span class="badge badge-light-dark">Bande terminée</span>
                                            @elseif (!$bande->has_nutrition_plan_for_today)
                                                <div class="p-1 text-center rounded bg-light-warning">
                                                    <span class="font-weight-bolder text-warning">Aucun plan défini pour cet âge.</span><br>
                                                    <a href="#" data-toggle="modal" data-target="#modal-info-plan">
                                                        <small>Cliquez pour configurer</small>
                                                    </a>
                                                </div>
                                            @else
                                                @php
                                                    $statutAujourdhui = $bande->statut_nutrition_aujourdhui;
                                                    $passagesFaits = \App\Models\Nutrition::where('id_bande', $bande->id)->whereDate('date', today())->count();
                                                    $passagesAttendus = $bande->total_frequency_today;
                                                @endphp
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small>Statut d'aujourd'hui:</small>
                                                    <span class="font-weight-bolder">
                                                        @if($statutAujourdhui == 'Complet')
                                                            <span class="badge badge-light-success">Terminée ({{ $passagesFaits }}/{{ $passagesAttendus }})</span>
                                                        @else
                                                            <span class="text-danger">{{ $passagesFaits }}/{{ $passagesAttendus }}</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
								    </div>
								</div>
			                    <div class="transaction-item">
			                        <div class="media">
			                            <div class="rounded avatar bg-light-success">
			                                <div class="avatar-content">
			                                    <i data-feather="check" class="avatar-icon font-medium-3"></i>
			                                </div>
			                            </div>
                                        <div class="media-body">
                                            <h6 class="transaction-title">Ramassage</h6>
			                                <small>Nombre d'œufs ramassés</small>
			                            </div>
			                        </div>
			                        <div class="font-weight-bolder text-success">...</div>
			                    </div>
			                    
                                <hr>

                                <!-- Bouton Calendrier -->
						        <a href="{{ route('nutrition.calendar', ['bande' => $bande->id]) }}" class="btn btn-primary btn-sm btn-block">
								    <i data-feather="calendar" class="mr-50"></i>
								    <span>Voir le calendrier de nutrition</span>
								</a>
			                </div>
			            </div>
			        </div>
			        <!--/ Carte Activités et Statut -->
			    </div>
			</section>
	    </div>
	</div>
</div>
<!-- MODAL d'information pour plan manquant -->
<div class="modal fade" id="modal-info-plan" tabindex="-1" role="dialog" aria-labelledby="modalInfoPlanTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInfoPlanTitle">Plan de nutrition manquant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Pour pouvoir nourrir cette bande, vous devez d'abord définir un plan de nutrition qui correspond à son âge actuel (<strong>{{ $bande->age_formate }}</strong>).
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <a href="{{ route('foods-setting.index') }}" class="btn btn-primary">
                    Configurer les plannings
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script type="text/javascript">
    // On s'assure que le bon menu est actif
    document.querySelector('#BandeListe').classList.add('active');
</script>
@endsection