@extends('layouts.app')
@section('title')
Calendrier de Nutrition
@endsection
@section('contenu')
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="mb-2 content-header-left col-md-12 col-12">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="float-left mb-0 content-header-title">Calendrier de Nutrition</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Accueil</a></li>
                                <li class="breadcrumb-item"><a href="{{route('bande.index')}}">Liste des bandes</a></li>
                                <li class="breadcrumb-item"><a href="{{route('bande.show',$id_bande)}}">Détails sur la Bande</a></li>
                                <li class="breadcrumb-item active">Calendrier de nutrition</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section>
                <div class="overflow-hidden border app-calendar">
                    <div class="row no-gutters">
                        <!-- Sidebar -->
                        <div class="overflow-hidden flex-grow-0 col app-calendar-sidebar d-flex flex-column" id="app-calendar-sidebar">
                            <div class="sidebar-wrapper">
                                <div class="pb-0 card-body">
                                    <h5 class="mb-1 section-label"><span class="align-middle">Filtres</span></h5>
                                    <div class="mb-1 custom-control custom-checkbox">
                                        <input type="checkbox" class="select-all custom-control-input" id="select-all" checked />
                                        <label class="custom-control-label" for="select-all">Tout voir</label>
                                    </div>
                                    <div class="calendar-events-filter">
                                        <div class="mb-1 custom-control custom-control-danger custom-checkbox">
                                            <input type="checkbox" class="custom-control-input input-filter" id="filter-missed" data-color="#EA5455" checked />
                                            <label class="custom-control-label" for="filter-missed">Nutrition manquée</label>
                                        </div>
                                        <div class="mb-1 custom-control custom-control-warning custom-checkbox">
                                            <input type="checkbox" class="custom-control-input input-filter" id="filter-incomplete" data-color="#ff9f43" checked />
                                            <label class="custom-control-label" for="filter-incomplete">Nutrition incomplète</label>
                                        </div>
                                        <div class="mb-1 custom-control custom-control-success custom-checkbox">
                                            <input type="checkbox" class="custom-control-input input-filter" id="filter-complete" data-color="#28a745" checked />
                                            <label class="custom-control-label" for="filter-complete">Nutrition complète</label>
                                        </div>
                                        <div class="mb-1 custom-control custom-control-secondary custom-checkbox">
                                            <input type="checkbox" class="custom-control-input input-filter" id="filter-no-plan" data-color="#6c757d" checked />
                                            <label class="custom-control-label" for="filter-no-plan">Plan manquant</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <img src="{{asset('app-assets/images/pages/calendar-illustration.png')}}" alt="Calendar illustration" class="img-fluid" />
                            </div>
                        </div>
                        <!-- /Sidebar -->

                        <!-- Calendar -->
                        <div class="col position-relative">
                            <div class="mb-0 border-0 shadow-none card rounded-0">
                                <div class="pb-0 card-body">
                                    <div id="calendar"></div>
                                </div>
                            </div>
                        </div>
                        <!-- /Calendar -->
                        <div class="body-content-overlay"></div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Modal de description des événements -->
<div class="modal fade" id="event-details-modal" tabindex="-1" aria-labelledby="eventDetailsTitle" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> {{-- Agrandie en 'modal-lg' pour plus d'espace --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailsTitle">Rapport de Nutrition</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 id="modal-date" class="mb-2 text-center"></h4>
                <div id="modal-details-content">
                    <!-- Le contenu sera injecté ici par JavaScript -->
                    <div class="text-center">Chargement...</div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button data-dismiss="modal" class="btn btn-secondary">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->
@endsection

@section('javascript')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // --- Variables et Constantes ---
    const calendarEl = document.getElementById('calendar');
    const calendarEventsUrl = `/api/nutrition/{{$id_bande}}/calendrier`;
    const eventModal = $('#event-details-modal');
    let allServerEvents = []; // Stocke les événements bruts du serveur

    // --- Fonctions de rendu de la Modale ---

    /**
     * Crée et retourne le HTML pour une seule ligne de détail d'aliment.
     * @param {object} detail - L'objet de détail pour un aliment.
     * @returns {string} Le HTML de la ligne.
     */
    function createDetailRowHtml(detail) {
        const badgeClass = detail.est_complet ? 'badge-light-success' : 'badge-light-danger';
        const iconClass = detail.est_complet ? 'fa-check-circle text-success' : 'fa-times-circle text-danger';

        const quantiteDonneeHtml = detail.quantite_donnee > 0
            ? `<span>| Quantité donnée : <strong>${detail.quantite_donnee} ${detail.unite}</strong></span>`
            : '';

        const quantiteAttendueHtml = detail.quantite_attendue > 0
            ? `<span>Prévu : <strong>${detail.quantite_attendue.toFixed(2)} ${detail.unite}</strong></span>`
            : '';

        return `
            <li class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="fas ${iconClass} mr-1"></i><strong>${detail.aliment}</strong></span>
                    <span class="badge ${badgeClass} badge-pill">${detail.score}</span>
                </div>
                <div class="mt-1" style="padding-left: 1.5rem;">
                    <small class="text-info">${quantiteAttendueHtml} ${quantiteDonneeHtml}</small>
                </div>
            </li>
        `;
    }
    
    /**
     * Crée et retourne le HTML pour le cas où un plan de nutrition est manquant.
     * @returns {string} Le HTML de l'alerte.
     */
    function createMissingPlanHtml() {
        // Note : On ne peut plus passer d'URL dynamique ici, car le JS est générique.
        // L'utilisateur devra aller sur la page de configuration manuellement.
        return `
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Plan de nutrition non défini</h4>
                <div class="alert-body">
                    Aucun planning de nutrition n'était configuré pour l'âge de la bande à cette date.
                    <br><br>
                    Veuillez vous rendre dans les <strong>paramètres de nutrition</strong> pour configurer un plan adéquat.
                </div>
            </div>
        `;
    }

    // --- Initialisation de FullCalendar ---
    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'fr',
        initialView: 'dayGridMonth',
        headerToolbar: {
            start: 'prev,next today',
            center: 'title',
            end: 'dayGridMonth,timeGridWeek,listYear'
        },
        editable: false,
        events: function(fetchInfo, successCallback, failureCallback) {
            fetch(calendarEventsUrl)
                .then(response => response.ok ? response.json() : Promise.reject('Erreur réseau'))
                .then(data => {
                    allServerEvents = data.map(event => ({ ...event, textColor: 'white' }));
                    successCallback(filterEvents(allServerEvents));
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des événements:', error);
                    failureCallback(error);
                });
        },
        eventClick: function(info) {
            const { event } = info;
            const { details } = event.extendedProps;
            
            const eventDate = event.start.toLocaleDateString('fr-FR', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });
            eventModal.find('#modal-date').text(eventDate);
            const contentEl = eventModal.find('#modal-details-content');
            
            if (Array.isArray(details) && details.length > 0) {
                const detailsHtml = details.map(createDetailRowHtml).join('');
                contentEl.html(`<ul class="list-group">${detailsHtml}</ul>`);
            } else if (details && details.type === 'plan_manquant') {
                contentEl.html(createMissingPlanHtml());
            } else {
                contentEl.html('<p class="text-center">Aucune information détaillée disponible.</p>');
            }

            eventModal.modal('show');
        }
    });

    calendar.render();

    // --- Gestion des Filtres (Logique optimisée) ---
    const selectAllCheckbox = document.getElementById('select-all');
    const filterCheckboxes = document.querySelectorAll('.input-filter');

    function filterEvents(events) {
        const activeColors = new Set(
            [...filterCheckboxes].filter(cb => cb.checked).map(cb => cb.dataset.color)
        );
        return selectAllCheckbox.checked ? events : events.filter(event => activeColors.has(event.color));
    }

    function refreshCalendar() {
        calendar.removeAllEvents();
        calendar.addEventSource(filterEvents(allServerEvents));
    }

    selectAllCheckbox.addEventListener('change', () => {
        filterCheckboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
        refreshCalendar();
    });

    filterCheckboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            selectAllCheckbox.checked = [...filterCheckboxes].every(c => c.checked);
            refreshCalendar();
        });
    });

    // --- Activation du menu ---
});
</script>
@endsection