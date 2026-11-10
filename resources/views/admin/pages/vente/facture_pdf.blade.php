<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $facture->matricule }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-height: 70px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }
        .meta {
            margin-top: 10px;
        }
        .meta span {
            display: inline-block;
            border: 1px solid #cccccc;
            border-radius: 20px;
            padding: 6px 14px;
            margin: 4px;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000000;
            padding: 8px;
            text-align: center;
        }
        th {
            background: #333333;
            color: #ffffff;
        }
        td.description {
            text-align: left;
        }
        .total {
            font-weight: bold;
        }
        .info {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .info td {
            border: none;
            text-align: left;
            padding: 4px;
            vertical-align: top;
        }
        .info td.right {
            text-align: right;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($logoPath)
            <img src="{{ $logoPath }}" alt="Logo">
        @endif
        <div class="title">FACTURE</div>
        <div class="meta">
            <span>n° {{ $facture->matricule }}</span>
            <span>{{ $date }}</span>
        </div>
    </div>

    <table class="info">
        <tr>
            <td>
                <strong>{{ $vendeur->name }}</strong><br>
                {{ $vendeur->email }}
            </td>
            <td class="right">
                <strong>À L'ATTENTION DE</strong><br>
                <strong>{{ $facture->client_facture?->nom }}</strong><br>
                {{ $facture->client_facture?->telephone }}<br>
                {{ $facture->client_facture?->adresse }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Prix unitaire</th>
                <th>Quantité</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lignes as $ligne)
                <tr>
                    <td class="description">{{ $ligne->produit?->nom ?? '' }}</td>
                    <td>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                    <td>{{ $ligne->qte }}</td>
                    <td>{{ number_format($ligne->total_ligne, 0, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
            <tr class="total">
                <td colspan="3">Sous-total :</td>
                <td>{{ number_format($facture->total_ht, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr class="total">
                <td colspan="3"><strong>TOTAL :</strong></td>
                <td><strong>{{ number_format($facture->total_ttc, 0, ',', ' ') }} FCFA</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">MERCI DE VOTRE CONFIANCE</div>
</body>
</html>
