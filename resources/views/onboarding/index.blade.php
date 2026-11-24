@extends('layouts.app')

@section('title', 'Bienvenue dans '.config('app.name'))

@section('css')
    <style>
        .onboarding-hero {
            background: linear-gradient(120deg, #f3f2ff 0%, #fff 75%);
            border: 1px solid #e8e6ff;
        }
        .onboarding-module {
            height: 100%;
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .onboarding-module:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1.5rem rgba(34, 41, 47, .12);
        }
        .guide-backdrop {
            position: fixed;
            inset: 0;
            z-index: 1040;
            display: none;
            pointer-events: none;
        }
        .guide-backdrop.is-active {
            display: block;
        }
        .guide-spotlight {
            position: fixed;
            z-index: 1041;
            border: 2px solid #7367f0;
            border-radius: .5rem;
            box-shadow: 0 0 0 9999px rgba(24, 25, 38, .68);
            transition: all .2s ease;
        }
        .guide-arrow {
            position: fixed;
            inset: 0;
            z-index: 1042;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
        }
        .guide-panel {
            position: fixed;
            z-index: 1043;
            right: 1rem;
            bottom: 1rem;
            width: min(28rem, calc(100vw - 2rem));
            border-top: 4px solid #7367f0;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .28);
        }
        @media (max-width: 575.98px) {
            .guide-panel {
                right: .5rem;
                bottom: .5rem;
                width: calc(100vw - 1rem);
            }
        }
    </style>
@endsection

@section('contenu')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2">
                    <h2 class="content-header-title mb-0">Bienvenue, {{ auth()->user()->name }}</h2>
                    <p class="text-muted mb-0">{{ $companyName }} · votre espace de travail</p>
                </div>
            </div>

            <div class="content-body">
                <section class="card onboarding-hero" data-tour-step="hero">
                    <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                        <div class="mr-2">
                            <span class="badge badge-light-primary mb-1">DÉMARRAGE GUIDÉ</span>
                            <h3>Découvrez FarmApp à votre rythme</h3>
                            <p class="text-muted mb-0">Visitez les repères essentiels, puis explorez des exemples sans modifier les données de votre entreprise.</p>
                        </div>
                        <button type="button" class="btn btn-primary mt-1" id="startWelcomeTour">
                            <i data-feather="play" class="mr-50"></i> Lancer la visite guidée
                        </button>
                    </div>
                </section>

                <div class="row">
                    <div class="col-lg-8 col-12" data-tour-step="modules">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h4 class="mb-0">Exemples par module</h4>
                            <span class="text-muted small">{{ count($completedGuides) }} parcours terminé(s)</span>
                        </div>
                        <div class="row">
                            @foreach($modules as $slug => $module)
                                <div class="col-md-6 col-12">
                                    <article class="card onboarding-module">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="avatar bg-light-primary mr-1">
                                                    <span class="avatar-content"><i data-feather="{{ $module['icon'] }}"></i></span>
                                                </span>
                                                <h5 class="mb-0">{{ $module['title'] }}</h5>
                                            </div>
                                            <p class="text-muted">{{ $module['description'] }}</p>
                                            <p class="small mb-1"><strong>Exemple :</strong> {{ $module['scenario'] }}</p>
                                            @if(in_array('scenario-'.$slug, $completedGuides, true))
                                                <span class="badge badge-light-success mb-1">Parcours terminé</span>
                                            @endif
                                            <div>
                                                <a href="{{ route('onboarding.example', ['module' => $slug]) }}" class="btn btn-outline-primary btn-sm">
                                                    Voir l’exemple <i data-feather="arrow-right" class="ml-25"></i>
                                                </a>
                                                @if($module['route'] && \Illuminate\Support\Facades\Route::has($module['route']))
                                                    <a href="{{ route($module['route']) }}" class="btn btn-flat-secondary btn-sm">Ouvrir le module</a>
                                                @endif
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-4 col-12">
                        <section class="card" data-tour-step="sandbox">
                            <div class="card-header">
                                <h4 class="card-title">Un bac à sable sans risque</h4>
                            </div>
                            <div class="card-body">
                                <p>Les exemples sont fictifs et affichés uniquement dans les pages du guide.</p>
                                <ul class="pl-1">
                                    <li>Aucune donnée n’est ajoutée à votre entreprise.</li>
                                    <li>Aucun stock, solde ou indicateur n’est modifié.</li>
                                    <li>Vous pouvez refaire les parcours à tout moment.</li>
                                </ul>
                                <div class="alert alert-light-primary mb-0">
                                    <i data-feather="shield" class="mr-50"></i>
                                    Vos opérations réelles restent dans votre espace habituel.
                                </div>
                            </div>
                        </section>

                        <section class="card" data-tour-step="progress">
                            <div class="card-body">
                                <h5>Votre progression</h5>
                                <p class="text-muted mb-1">Les parcours terminés sont suivis pour votre compte dans cette entreprise.</p>
                                <strong>{{ count($completedGuides) }} / {{ count($modules) + 1 }}</strong> parcours terminés
                            </div>
                        </section>
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-2">
                    <a href="{{ route('home') }}" class="btn btn-primary">Continuer vers mon tableau de bord</a>
                </div>
            </div>
        </div>
    </div>

    <div class="guide-backdrop" id="guideBackdrop" aria-hidden="true"></div>
    <div class="guide-spotlight" id="guideSpotlight" aria-hidden="true" style="display:none"></div>
    <svg class="guide-arrow" id="guideArrow" aria-hidden="true" style="display:none">
        <defs>
            <marker id="guideArrowHead" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
                <path d="M0,0 L0,6 L7,3 z" fill="#ff9f43"></path>
            </marker>
        </defs>
        <line id="guideArrowLine" x1="0" y1="0" x2="0" y2="0" stroke="#ff9f43" stroke-width="3" marker-end="url(#guideArrowHead)"></line>
    </svg>
    <div class="card guide-panel" id="guidePanel" role="dialog" aria-modal="true" aria-labelledby="guideTitle" aria-describedby="guideDescription" hidden>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <span class="badge badge-light-primary" id="guideCounter"></span>
                <button type="button" class="close" id="closeWelcomeTour" aria-label="Fermer">&times;</button>
            </div>
            <h4 class="mt-1" id="guideTitle"></h4>
            <p class="text-muted mb-1" id="guideDescription" aria-live="polite"></p>
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="previousGuideStep">Précédent</button>
                <button type="button" class="btn btn-primary btn-sm" id="nextGuideStep">Suivant</button>
            </div>
        </div>
    </div>
    <form id="completeWelcomeForm" action="{{ route('onboarding.complete', ['guide' => 'welcome']) }}" method="POST" class="d-none">
        @csrf
    </form>
@endsection

@section('javascript')
    <script>
        $(function () {
            var tourSteps = [
                {target: '[data-tour-step="hero"]', title: 'Votre espace entreprise', text: 'Ce guide vous aide à découvrir FarmApp. Vous pouvez le relancer depuis le menu de votre profil.'},
                {target: '[data-tour-step="modules"]', title: 'Parcours par module', text: 'Choisissez un exemple guidé pour comprendre les principales étapes d’un module.'},
                {target: '[data-tour-step="sandbox"]', title: 'Données fictives isolées', text: 'Les chiffres des exemples ne sont jamais enregistrés dans votre entreprise.'},
                {target: '[data-tour-step="progress"]', title: 'Votre progression', text: 'Les parcours terminés sont enregistrés pour votre compte dans cette entreprise.'}
            ];
            var currentStep = 0;
            var $panel = $('#guidePanel');
            var $spotlight = $('#guideSpotlight');
            var $arrow = $('#guideArrow');
            var $line = $('#guideArrowLine');
            var $backdrop = $('#guideBackdrop');

            function positionTour() {
                var target = document.querySelector(tourSteps[currentStep].target);
                if (!target) {
                    return;
                }

                var rect = target.getBoundingClientRect();
                var padding = 8;
                $spotlight.css({
                    display: 'block',
                    left: Math.max(4, rect.left - padding) + 'px',
                    top: Math.max(4, rect.top - padding) + 'px',
                    width: Math.min(window.innerWidth - 8, rect.width + padding * 2) + 'px',
                    height: Math.min(window.innerHeight - 8, rect.height + padding * 2) + 'px'
                });

                var panelRect = $panel[0].getBoundingClientRect();
                var targetX = rect.left + rect.width / 2;
                var targetY = Math.min(window.innerHeight - 16, rect.bottom + padding);
                var panelX = panelRect.left + panelRect.width / 2;
                var panelY = panelRect.top;
                $line.attr({x1: panelX, y1: panelY, x2: targetX, y2: targetY});
            }

            function renderStep() {
                var step = tourSteps[currentStep];
                var target = document.querySelector(step.target);
                if (!target) {
                    finishTour();
                    return;
                }

                target.scrollIntoView({behavior: 'smooth', block: 'center'});
                $('#guideTitle').text(step.title);
                $('#guideDescription').text(step.text);
                $('#guideCounter').text('Étape ' + (currentStep + 1) + ' / ' + tourSteps.length);
                $('#previousGuideStep').prop('disabled', currentStep === 0);
                $('#nextGuideStep').text(currentStep === tourSteps.length - 1 ? 'Terminer' : 'Suivant');
                window.setTimeout(positionTour, 220);
            }

            function startTour() {
                currentStep = 0;
                $backdrop.addClass('is-active');
                $panel.prop('hidden', false);
                $arrow.show();
                renderStep();
                $('#nextGuideStep').trigger('focus');
            }

            function closeTour() {
                $backdrop.removeClass('is-active');
                $spotlight.hide();
                $arrow.hide();
                $panel.prop('hidden', true);
                $('#startWelcomeTour').trigger('focus');
            }

            function finishTour() {
                $('#nextGuideStep').prop('disabled', true).text('Enregistrement…');
                fetch($('#completeWelcomeForm').attr('action'), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('#completeWelcomeForm [name="_token"]').val(),
                        'Accept': 'application/json'
                    }
                }).then(function (response) {
                    if (!response.ok) {
                        throw new Error('Impossible d’enregistrer la progression.');
                    }

                    closeTour();
                    window.location.reload();
                }).catch(function () {
                    $('#guideDescription').text('La visite est terminée, mais sa progression n’a pas pu être enregistrée. Vérifiez votre connexion puis réessayez.');
                    $('#nextGuideStep').prop('disabled', false).text('Réessayer');
                });
            }

            $('#startWelcomeTour').on('click', startTour);
            $('#closeWelcomeTour').on('click', closeTour);
            $('#previousGuideStep').on('click', function () {
                if (currentStep > 0) {
                    currentStep--;
                    renderStep();
                }
            });
            $('#nextGuideStep').on('click', function () {
                if (currentStep === tourSteps.length - 1) {
                    finishTour();
                } else {
                    currentStep++;
                    renderStep();
                }
            });
            $(window).on('resize scroll', function () {
                if (!$panel.prop('hidden')) {
                    positionTour();
                }
            });
            $(document).on('keydown', function (event) {
                if ($panel.prop('hidden')) {
                    return;
                }

                if (event.key === 'Escape') {
                    closeTour();
                } else if (event.key === 'ArrowRight' && currentStep < tourSteps.length - 1) {
                    currentStep++;
                    renderStep();
                } else if (event.key === 'ArrowLeft' && currentStep > 0) {
                    currentStep--;
                    renderStep();
                }
            });
        });
    </script>
@endsection
