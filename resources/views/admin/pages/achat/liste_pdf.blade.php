<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des achats</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; margin: 0; padding: 20px; }
        h1 { font-size: 20px; text-align: center; margin-bottom: 4px; }
        .subtitle { text-align: center; margin-bottom: 16px; color: #666; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background: #333; color: #fff; }
    </style>
</head>
<body>
    <h1>Liste des achats</h1>
    <div class="subtitle">Générée le {{ now()->format('d/m/Y H:i') }}</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Fournisseur</th>
                <th>Matière première</th>
                <th>Quantité</th>
                <th>Prix unitaire (FCFA)</th>
                <th>Prix total (FCFA)</th>
                <th>Mode de paiement</th>
            </tr>
        </thead>
        <tbody>
            @foreach($achats as $achat)
                <tr>
                    <td>{{ $achat->date }}</td>
                    <td>{{ $achat->fournisseur?->nom ?? '' }}</td>
                    <td>{{ $achat->matiere_premiere?->nom_produit ?? '' }}</td>
                    <td>{{ $achat->qte }}</td>
                    <td>{{ number_format($achat->prix_unitaire, 0, ',', ' ') }}</td>
                    <td>{{ number_format($achat->prix_total, 0, ',', ' ') }}</td>
                    <td>{{ $achat->mode_de_paiement }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
