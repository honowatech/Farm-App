@extends('layouts.app')

@section('title', 'Souscrire à un forfait')

@section('contenu')
    <div class="content app-content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2">
                    <h2 class="content-header-title mb-0">Souscrire à un forfait</h2>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Vos données seront conservées</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Votre période d’essai s’est terminée le {{ $expiredTrial->trial_ends_at->format('d/m/Y') }}.
                        Choisissez un forfait pour réactiver votre espace.
                    </p>

                    @if($pendingPayment)
                        <div class="alert alert-info" role="status">
                            Un paiement est en attente. Confirmez-le sur votre téléphone pour réactiver l’espace.
                            <div class="mt-1">
                                Référence MaMoNi :
                                <code>{{ $pendingPayment->gateway_reference ?: 'non disponible' }}</code>
                                <br>
                                Référence FarmApp :
                                <code>{{ $pendingPayment->external_reference }}</code>
                            </div>
                        </div>
                    @endif

                    <div id="subscriptionMessage" class="alert d-none" role="alert"></div>

                    <form id="subscriptionForm" action="{{ route('tenant.subscription.initiate') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="plan_id">Forfait</label>
                            <select id="plan_id" name="plan_id" class="form-control" required>
                                <option value="">Choisir un forfait</option>
                                @foreach($plans as $plan)
                                    <option
                                        value="{{ $plan->id }}"
                                        data-price="{{ (int) round((float) $plan->price) }}"
                                        data-interval="{{ $plan->billing_interval === 'annually' ? 'an' : 'mois' }}"
                                    >
                                        {{ $plan->name }} — {{ number_format((float) $plan->price, 0, ',', ' ') }} {{ $plan->currency }} / {{ $plan->billing_interval === 'annually' ? 'an' : 'mois' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" id="phoneField">
                            <label for="phone_number">Numéro Mobile Money</label>
                            <input id="phone_number" name="phone_number" type="tel" class="form-control" inputmode="tel" autocomplete="tel" placeholder="6XXXXXXXX">
                            <small class="form-text text-muted">Numéro camerounais au format 6XXXXXXXX ou 2376XXXXXXXX.</small>
                        </div>

                        <button id="subscribeButton" type="submit" class="btn btn-primary" @disabled($pendingPayment)>
                            Souscrire en gardant mes données
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary ml-1">Retour au tableau de bord</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(function () {
            var $form = $('#subscriptionForm');
            var $plan = $('#plan_id');
            var $phone = $('#phone_number');
            var $phoneField = $('#phoneField');
            var $button = $('#subscribeButton');
            var $message = $('#subscriptionMessage');
            var statusUrlTemplate = @json(route('tenant.subscription.status', ['transaction' => '__ID__']));
            var homeUrl = @json(route('home'));

            function showMessage(message, type) {
                $message.removeClass('d-none alert-danger alert-success alert-info')
                    .addClass('alert-' + type)
                    .text(message);
            }

            function updatePhoneRequirement() {
                var price = Number($plan.find(':selected').data('price') || 0);
                $phoneField.toggleClass('d-none', price <= 0);
                $phone.prop('required', price > 0);
            }

            function pollPayment(transactionId) {
                $.ajax({
                    url: statusUrlTemplate.replace('__ID__', transactionId),
                    method: 'POST',
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                    success: function (response) {
                        if (!response.final) {
                            window.setTimeout(function () { pollPayment(transactionId); }, 5000);
                            return;
                        }

                        if (response.payment_status === 'successful') {
                            showMessage('Paiement confirmé. Votre espace est réactivé.', 'success');
                            window.setTimeout(function () { window.location.href = homeUrl; }, 1200);
                            return;
                        }

                        showMessage('Le paiement n’a pas abouti. Vous pouvez réessayer.', 'danger');
                        $button.prop('disabled', false);
                    },
                    error: function () {
                        showMessage('Impossible de vérifier le paiement. Rechargez cette page pour reprendre le suivi.', 'danger');
                    }
                });
            }

            $plan.on('change', updatePhoneRequirement);
            updatePhoneRequirement();

            $form.on('submit', function (event) {
                event.preventDefault();
                $button.prop('disabled', true);
                $message.addClass('d-none');

                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    success: function (response) {
                        if (response.activated) {
                            showMessage('Votre forfait est activé.', 'success');
                            window.location.href = homeUrl;
                            return;
                        }

                        showMessage(response.message || 'Validez le paiement sur votre téléphone.', 'info');
                        pollPayment(response.transaction_id);
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON && xhr.responseJSON.errors;
                        var message = errors ? Object.values(errors)[0][0] : (xhr.responseJSON && xhr.responseJSON.message);
                        showMessage(message || 'Impossible d’initier le paiement.', 'danger');
                        $button.prop('disabled', false);
                    }
                });
            });

            @if($pendingPayment)
                $button.prop('disabled', true);
                pollPayment(@json($pendingPayment->id));
            @endif
        });
    </script>
@endsection
