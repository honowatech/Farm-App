<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $facture->matricule }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; font-size: 13px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #42a045; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #42a045; font-size: 22px; }
        .section { margin-bottom: 18px; }
        .section h3 { margin: 0 0 6px; color: #714126; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f8f7fb; }
        .right { text-align: right; }
        .total { font-size: 15px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>FarmApp</h1>
            <div>Facture {{ $facture->matricule }}</div>
        </div>
        <div class="right">
            <div>Date : {{ \Illuminate\Support\Carbon::parse($facture->date_facturation)->format('d/m/Y') }}</div>
        </div>
    </div>

    <div class="section">
        <h3>Client</h3>
        <div>{{ $facture->client_facture->nom ?? '—' }}</div>
        <div>{{ $facture->client_facture->telephone ?? '' }}</div>
        <div>{{ $facture->client_facture->adresse ?? '' }}</div>
    </div>

    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facture->lignes_facture as $ligne)
                    <tr>
                        <td>{{ $ligne->produit->nom ?? 'Produit #'.$ligne->id_produit }}</td>
                        <td>{{ $ligne->qte }}</td>
                        <td>{{ number_format((float) $ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                        <td class="right">{{ number_format((float) $ligne->total_ligne, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr><td colspan="3" class="right">Total HT</td><td class="right">{{ number_format((float) $facture->total_ht, 0, ',', ' ') }} FCFA</td></tr>
                <tr><td colspan="3" class="right">TVA</td><td class="right">{{ number_format((float) $facture->tva, 0, ',', ' ') }} FCFA</td></tr>
                <tr><td colspan="3" class="right total">Total TTC</td><td class="right total">{{ number_format((float) $facture->total_ttc, 0, ',', ' ') }} FCFA</td></tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
