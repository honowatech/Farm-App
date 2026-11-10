<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des ventes</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            font-size: 20px;
            text-align: center;
            margin-bottom: 4px;
        }
        .subtitle {
            text-align: center;
            margin-bottom: 16px;
            color: #666666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000000;
            padding: 6px;
            text-align: center;
        }
        th {
            background: #333333;
            color: #ffffff;
        }
        td.left {
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Liste des ventes</h1>
    <div class="subtitle">Générée le {{ now()->format('d/m/Y H:i') }}</div>
    <table>
        <thead>
            <tr>
                <th>Matricule</th>
                <th>Client</th>
                <th>Date</th>
                <th>Produits</th>
                <th>Total HT (FCFA)</th>
                <th>Total TTC (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventes as $vente)
                @php
                    $facture = $vente->facture;
                    $client = $facture ? $facture->client_facture : null;
                    $produits = $facture
                        ? $facture->lignes_facture->map(fn ($ligne) => $ligne->produit?->nom)->filter()->unique()->implode(', ')
                        : '';
                @endphp
                <tr>
                    <td>{{ $facture ? $facture->matricule : '' }}</td>
                    <td>{{ $client ? $client->nom : '' }}</td>
                    <td>{{ $vente->date_vente }}</td>
                    <td class="left">{{ $produits }}</td>
                    <td>{{ number_format($facture ? $facture->total_ht : 0, 0, ',', ' ') }}</td>
                    <td>{{ number_format($facture ? $facture->total_ttc : 0, 0, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
