@php
    $tour = $tour ?? null;
    $steps = data_get($tour, 'steps', []);
    $tourTitle = data_get($tour, 'title', 'Visite guidée');
    $tourKey = data_get($tour, 'key');
    $autoStart = (bool) ($autoStart ?? false);
@endphp

@if(!empty($steps) && $tourKey)
<style>
    .farm-tour-backdrop { position: fixed; inset: 0; z-index: 1040; display: none; pointer-events: none; }
    .farm-tour-backdrop.is-active { display: block; }
    .farm-tour-spotlight { position: fixed; z-index: 1041; border: 2px solid #7367f0; border-radius: .5rem; box-shadow: 0 0 0 9999px rgba(24, 25, 38, .68); transition: all .2s ease; }
    .farm-tour-arrow { position: fixed; inset: 0; z-index: 1042; width: 100vw; height: 100vh; pointer-events: none; }
    .farm-tour-panel { position: fixed; z-index: 1043; right: 1rem; bottom: 1rem; width: min(28rem, calc(100vw - 2rem)); border-top: 4px solid #7367f0; box-shadow: 0 1rem 3rem rgba(0, 0, 0, .28); }
    @media (max-width: 575.98px) { .farm-tour-panel { right: .5rem; bottom: .5rem; width: calc(100vw - 1rem); } }
</style>

<div class="farm-tour-backdrop" id="farmTourBackdrop" aria-hidden="true"></div>
<div class="farm-tour-spotlight" id="farmTourSpotlight" aria-hidden="true" style="display:none"></div>
<svg class="farm-tour-arrow" id="farmTourArrow" aria-hidden="true" style="display:none">
    <defs>
        <marker id="farmTourArrowHead" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
            <path d="M0,0 L0,6 L7,3 z" fill="#ff9f43"></path>
        </marker>
    </defs>
    <line id="farmTourArrowLine" x1="0" y1="0" x2="0" y2="0" stroke="#ff9f43" stroke-width="3" marker-end="url(#farmTourArrowHead)"></line>
</svg>
<div class="card farm-tour-panel" id="farmTourPanel" role="dialog" aria-modal="true" aria-labelledby="farmTourTitle" aria-describedby="farmTourDescription" hidden>
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <span class="badge badge-light-primary" id="farmTourCounter"></span>
            <button type="button" class="close" id="farmTourClose" aria-label="Fermer">&times;</button>
        </div>
        <h4 class="mt-1" id="farmTourTitle"></h4>
        <p class="text-muted mb-1" id="farmTourDescription" aria-live="polite"></p>
        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="farmTourPrevious">Précédent</button>
            <button type="button" class="btn btn-primary btn-sm" id="farmTourNext">Suivant</button>
        </div>
    </div>
</div>
<form id="farmTourCompleteForm" action="{{ route('tour.complete', ['route' => $tourKey]) }}" method="POST" class="d-none">
    @csrf
</form>

<script>
    $(function () {
        var tourSteps = @json($steps);
        var autoStart = @json($autoStart);

        // Ne garde que les étapes dont la cible existe réellement dans la page.
        tourSteps = tourSteps.filter(function (step) {
            return !!document.querySelector(step.target);
        });

        if (tourSteps.length === 0) {
            $('#farmTourTrigger').hide();
            return;
        }

        var currentStep = 0;
        var $panel = $('#farmTourPanel');
        var $spotlight = $('#farmTourSpotlight');
        var $arrow = $('#farmTourArrow');
        var $line = $('#farmTourArrowLine');
        var $backdrop = $('#farmTourBackdrop');

        function positionTour() {
            var target = document.querySelector(tourSteps[currentStep].target);
            if (!target) { return; }

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
            $line.attr({ x1: panelX, y1: panelY, x2: targetX, y2: targetY });
        }

        function renderStep() {
            var step = tourSteps[currentStep];
            var target = document.querySelector(step.target);
            if (!target) { finishTour(); return; }

            target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            $('#farmTourTitle').text(step.title);
            $('#farmTourDescription').text(step.text);
            $('#farmTourCounter').text('Étape ' + (currentStep + 1) + ' / ' + tourSteps.length);
            $('#farmTourPrevious').prop('disabled', currentStep === 0);
            $('#farmTourNext').text(currentStep === tourSteps.length - 1 ? 'Terminer' : 'Suivant');
            window.setTimeout(positionTour, 220);
        }

        function startTour() {
            currentStep = 0;
            $backdrop.addClass('is-active');
            $panel.prop('hidden', false);
            $arrow.show();
            renderStep();
            $('#farmTourNext').trigger('focus');
        }

        function closeTour() {
            $backdrop.removeClass('is-active');
            $spotlight.hide();
            $arrow.hide();
            $panel.prop('hidden', true);
        }

        function finishTour() {
            $('#farmTourNext').prop('disabled', true).text('Enregistrement…');
            fetch($('#farmTourCompleteForm').attr('action'), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('#farmTourCompleteForm [name="_token"]').val(),
                    'Accept': 'application/json'
                }
            }).catch(function () {}).then(function () {
                closeTour();
            });
        }

        $('#farmTourClose').on('click', closeTour);
        $('#farmTourPrevious').on('click', function () {
            if (currentStep > 0) { currentStep--; renderStep(); }
        });
        $('#farmTourNext').on('click', function () {
            if (currentStep === tourSteps.length - 1) { finishTour(); }
            else { currentStep++; renderStep(); }
        });
        $(window).on('resize scroll', function () {
            if (!$panel.prop('hidden')) { positionTour(); }
        });
        $(document).on('keydown', function (event) {
            if ($panel.prop('hidden')) { return; }
            if (event.key === 'Escape') { closeTour(); }
            else if (event.key === 'ArrowRight' && currentStep < tourSteps.length - 1) { currentStep++; renderStep(); }
            else if (event.key === 'ArrowLeft' && currentStep > 0) { currentStep--; renderStep(); }
        });

        // Permet au bouton « Guide » de la page de relancer la visite.
        window.FarmAppTour = window.FarmAppTour || {};
        window.FarmAppTour.start = startTour;
        window.FarmAppTour.title = @json($tourTitle);

        if (autoStart) {
            window.setTimeout(startTour, 500);
        }
    });
</script>
@endif
