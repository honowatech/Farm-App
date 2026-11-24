@extends('layouts.app')

@section('title', $guide['scenario'])

@section('contenu')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2">
                    <a href="{{ route('onboarding.index') }}" class="text-muted"><i data-feather="arrow-left"></i> Retour au guide</a>
                    <h2 class="content-header-title mt-1 mb-0">{{ $guide['scenario'] }}</h2>
                    <p class="text-muted">{{ $guide['title'] }} · exemple pédagogique interactif</p>
                </div>
            </div>

            <div class="content-body">
                <div class="alert alert-warning" role="note">
                    <strong>Environnement pédagogique :</strong> toutes les données ci-dessous sont fictives. Cette page ne crée ni ne modifie aucune donnée de votre entreprise.
                </div>

                <div class="row">
                    <div class="col-lg-8 col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Exemple illustré</h4>
                                <span class="badge badge-light-warning">Données fictives</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Étape / opération</th>
                                            <th>Détail illustratif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($guide['sample'] as $row)
                                            <tr>
                                                <td>{{ $row['date'] }}</td>
                                                <td>{{ $row['operation'] }}</td>
                                                <td>{{ $row['detail'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span class="badge badge-light-primary" id="scenarioCounter">Étape 1 / {{ count($guide['steps']) }}</span>
                                    @if($guide['completed'])
                                        <span class="badge badge-light-success" id="scenarioCompleted">Parcours terminé</span>
                                    @else
                                        <span class="badge badge-light-secondary" id="scenarioCompleted" hidden>Parcours terminé</span>
                                    @endif
                                </div>
                                <h4 class="mt-1" id="scenarioHeading">À retenir</h4>
                                <p class="text-muted" id="scenarioStep" aria-live="polite">{{ $guide['steps'][0] }}</p>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary" id="scenarioPrevious" disabled>Précédent</button>
                                    <button type="button" class="btn btn-primary" id="scenarioNext">
                                        {{ count($guide['steps']) === 1 ? 'Terminer' : 'Suivant' }}
                                    </button>
                                </div>
                                @if($guide['completed'])
                                    <p class="text-success mt-1 mb-0">Ce parcours est déjà enregistré comme terminé. Vous pouvez le refaire.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>{{ $guide['title'] }}</h4>
                                <p>{{ $guide['description'] }}</p>
                                @if($guide['moduleUrl'])
                                    <a href="{{ $guide['moduleUrl'] }}" class="btn btn-outline-primary btn-block">Ouvrir le module réel</a>
                                @endif
                                <a href="{{ route('onboarding.index') }}" class="btn btn-flat-secondary btn-block">Autres exemples</a>
                                <a href="{{ route('home') }}" class="btn btn-primary btn-block">Continuer vers le tableau de bord</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="completeScenarioForm" action="{{ route('onboarding.complete', ['guide' => $guide['key']]) }}" method="POST" class="d-none">
        @csrf
    </form>
@endsection

@section('javascript')
    <script>
        $(function () {
            var steps = @json($guide['steps']);
            var current = 0;

            function renderStep() {
                $('#scenarioStep').text(steps[current]);
                $('#scenarioCounter').text('Étape ' + (current + 1) + ' / ' + steps.length);
                $('#scenarioPrevious').prop('disabled', current === 0);
                $('#scenarioNext').text(current === steps.length - 1 ? 'Terminer' : 'Suivant');
            }

            $('#scenarioPrevious').on('click', function () {
                if (current > 0) {
                    current--;
                    renderStep();
                }
            });

            $('#scenarioNext').on('click', function () {
                if (current < steps.length - 1) {
                    current++;
                    renderStep();
                    return;
                }

                var $button = $(this).prop('disabled', true).text('Enregistrement…');
                fetch($('#completeScenarioForm').attr('action'), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('#completeScenarioForm [name="_token"]').val(),
                        'Accept': 'application/json'
                    }
                }).then(function (response) {
                    if (!response.ok) {
                        throw new Error('Impossible d’enregistrer la progression.');
                    }

                    $('#scenarioCompleted').prop('hidden', false);
                    $('#scenarioStep').text('Bravo, cet exemple est terminé. Vous pouvez le refaire ou revenir au guide.');
                    $button.text('Terminé');
                }).catch(function () {
                    $button.prop('disabled', false).text('Réessayer l’enregistrement');
                    window.alert('La progression n’a pas pu être enregistrée. Vérifiez votre connexion puis réessayez.');
                });
            });
        });
    </script>
@endsection
