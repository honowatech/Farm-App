<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des produits</title>
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
    <h1>Liste des produits</h1>
    <div class="subtitle">Générée le {{ now()->format('d/m/Y H:i') }}</div>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prix unitaire (FCFA)</th>
                <th>Description</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produits as $produit)
                <tr>
                    <td>{{ $produit->nom }}</td>
                    <td>{{ number_format($produit->prix_unitaire, 0, ',', ' ') }}</td>
                    <td>{{ $produit->description }}</td>
                    <td>{{ $produit->statut == 1 ? 'Actif' : 'Inactif' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
