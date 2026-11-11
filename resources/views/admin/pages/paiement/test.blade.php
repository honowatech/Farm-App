@extends('layouts.app')

@section('title', 'Test de paiement MaMoNi')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Test de paiement MaMoNi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('paiement.index') }}">Paiements</a></li>
                                <li class="breadcrumb-item active">Test</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-12 col-md-6 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Initier un paiement de test</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="phone_number">Numéro de téléphone (Mobile Money)</label>
                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather="phone"></i></span>
                                    </div>
                                    <input type="tel" inputmode="tel" id="phone_number" class="form-control" placeholder="Ex : 237690000000 ou 690000000" required>
                                </div>
                                <small class="text-muted">MTN ou Orange Money (le numéro doit commencer par 6 ou 69).</small>
                            </div>
                            <div class="form-group">
                                <label for="amount">Montant (FCFA)</label>
                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather="dollar-sign"></i></span>
                                    </div>
                                    <input type="number" inputmode="numeric" id="amount" class="form-control" placeholder="Ex : 15000" min="1" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="client_name">Nom du client</label>
                                <span class="cursor-pointer text-info" title="Optionnel">Optionnel</span>
                                <input type="text" id="client_name" class="form-control" placeholder="Nom du client">
                            </div>

                            <button type="button" id="btn-initier" class="btn btn-success btn-lg btn-block">
                                <i data-feather="play"></i> Initier le paiement
                            </button>

                            <div id="resultat" class="mt-1" style="display: none;">
                                <div id="statut" class="text-center"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
    document.querySelector('#PaiementTest').classList.add('active');

    var pollingTimer = null;

    var ETATS = {
        'pending':   { icon: 'fa-spinner fa-spin', color: 'primary', titre: 'En attente de validation', detail: 'Le client doit valider sur son téléphone…' },
        'successful': { icon: 'fa-check-circle', color: 'success', titre: 'Paiement réussi', detail: 'Le client a validé et le paiement est confirmé.' },
        'failed':    { icon: 'fa-times-circle', color: 'danger', titre: 'Paiement échoué', detail: 'Le client a validé mais l\'opération a échoué.' },
        'cancelled': { icon: 'fa-ban', color: 'warning', titre: 'Paiement annulé', detail: 'Le client a annulé l\'opération.' },
        'expired':   { icon: 'fa-clock', color: 'warning', titre: 'Paiement expiré', detail: 'Le délai de validation est dépassé.' },
    };

    function afficherEtat(statut, extra) {
        var etat = ETATS[statut] || { icon: 'fa-question-circle', color: 'secondary', titre: 'Statut inconnu', detail: statut };
        var html = '<div class="text-' + etat.color + '">'
            + '<i class="fas ' + etat.icon + ' fa-3x"></i>'
            + '<h4 class="mt-1 mb-0">' + etat.titre + '</h4>'
            + '<p class="mb-0">' + etat.detail + '</p>'
            + (extra ? '<p class="small text-muted mt-50">' + extra + '</p>' : '')
            + '</div>';
        $('#statut').html(html);
    }

    function demarrerPolling(transactionId) {
        pollingTimer = setInterval(function () {
            $.post('{{ url("/paiement/statut") }}/' + transactionId, { _token: '{{ csrf_token() }}' }, function (res) {
                if (res.final) {
                    clearInterval(pollingTimer);
                    var extra = 'Réf. ' + (res.external_reference || '') + (res.provider_slug ? ' · ' + res.provider_slug : '');
                    if (res.payment_status === 'successful' && res.receipt_url) {
                        extra += ' · <a href="' + res.receipt_url + '" target="_blank">Voir le reçu</a>';
                    }
                    afficherEtat(res.payment_status, extra);
                } else {
                    afficherEtat('pending');
                }
            });
        }, 4000);
    }

    $(document).ready(function () {
        $('#btn-initier').on('click', function () {
            var phone = $('#phone_number').val();
            var amount = $('#amount').val();
            var client = $('#client_name').val();

            if (!phone || !amount) {
                toastr.error('Veuillez renseigner le numéro et le montant.', 'Champs requis');
                return;
            }

            if (pollingTimer) {
                clearInterval(pollingTimer);
                pollingTimer = null;
            }

            var btn = $(this);
            btn.prop('disabled', true);

            $('#resultat').show();
            $('#statut').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-1">Initialisation du paiement…</p></div>');

            $.post('{{ route("paiement.initiate") }}', {
                _token: '{{ csrf_token() }}',
                phone_number: phone,
                amount: amount,
                client_name: client
            }, function (res) {
                btn.prop('disabled', false);

                if (res.ok && res.transaction_id) {
                    var extra = 'Réf. ' + res.external_reference + (res.provider_slug ? ' · ' + res.provider_slug : '');
                    $('#statut').html('<div class="text-center text-primary"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-1 mb-0">Prompt USSD envoyé, validez sur le téléphone…</p><p class="small text-muted">' + extra + '</p></div>');
                    demarrerPolling(res.transaction_id);
                } else {
                    afficherEtat('failed', res.message || 'Erreur lors de l\'initiation.');
                }
            }).fail(function (xhr) {
                btn.prop('disabled', false);
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur serveur.';
                $('#statut').html('<div class="text-center text-danger"><i class="fas fa-exclamation-triangle fa-2x"></i><p class="mt-1">' + msg + '</p></div>');
            });
        });
    });
</script>
@endsection
