<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .invoice {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .invoice-header {
            text-align: center;
        }

        .invoice-title {
            font-size: 36px;
            font-weight: bold;
        }

        .invoice-number, .invoice-date {
            display: inline-block;
            border: 1px solid #ddd;
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 10px;
        }

        .invoice-info {
            margin-top: 40px;
        }

        .invoice-info h4 {
            margin-bottom: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .table {
            margin-top: 20px;
        }

        .table th, .table td {
            text-align: center;
            border: 1px solid #000;
        }

        .table .description {
            text-align: left;
        }

        .table .total-row {
            font-weight: bold;
        }

        .payment-info, .payment-conditions {
            margin-top: 20px;
            font-size: 16px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-weight: bold;
            font-size: 18px;
        }

        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
    	<div class="row text-center mx-auto">
    		<div class="col-4 mx-auto">
    			<button class="btn btn-primary my-3 print-btn" onclick="window.print()">Imprimer la Facture</button>
    		</div>
    		<div class="col-4 mx-auto">
    			<a class="btn btn-primary my-3" href="{{route('vente.index')}}">retour</a>
    		</div>
    	</div>

        <div class="invoice">
            <!-- Facture Header -->
            <div class="invoice-header">
                <div class="invoice-title">FACTURE</div>
                <div class="invoice-number">n° {{ $facture->matricule}}</div>
                <div class="invoice-date">{{$date}}</div>
            </div>

            <!-- Facture Information -->
            <div class="row invoice-info">
                <div class="col-md-6">
                    <h4>{{$vendeur->name}}</h4>
                    <p class="address">123-456-7890<br>{{$vendeur->email}}<br>farmapp.ctn.com</p>
                </div>
                <div class="col-md-6 text-end">
                    <h4>À L’ATTENTION DE</h4>
                    <p class="address"><strong>{{$facture->client_facture->nom}}</strong><br>{{$facture->client_facture->telephone}}<br>{{$facture->client_facture->adresse}}</p>
                </div>
            </div>

            <!-- Facture Table -->
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Nom produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                	@foreach($lignes as $ligne)
                    <tr>
                        <td class="description">{{$ligne->produit->nom}}</td>
                        <td>{{$ligne->prix_unitaire}} FCFA</td>
                        <td>{{$ligne->qte}}</td>
                        <td>{{$ligne->total_ligne}} FCFA</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3">Sous total :</td>
                        <td>{{$facture->total_ht}} FCFA</td>
                    </tr>
                    <!--
	                    <tr class="total-row">
	                        <td colspan="3">TVA (20%) :</td>
	                        <td>930 €</td>
	                    </tr>
                	-->
                    <tr class="total-row">
                        <td colspan="3"><strong>TOTAL :</strong></td>
                        <td><strong>{{$facture->total_ttc}} FCFA</strong></td>
                    </tr>
                </tbody>
            </table>

            <!-- Payment Information -->
            <div class="row">
                <div class="col-md-6 payment-info">
                    <strong>Paiement à l’ordre de {{$facture->client_facture->nom}}</strong><br>
                    N° de compte 0123 4567 8901 2345
                </div>
                <div class="col-md-6 text-end payment-conditions">
                    <strong>Conditions de paiement</strong><br>
                    Paiement sous 30 jours
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">MERCI DE VOTRE CONFIANCE</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>