<?php

namespace App\Services;

use App\Models\Facture;
use App\Models\Ligne_facture;
use App\Models\Vente;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class VenteService
{
    /**
     * Taux de TVA appliqué aux factures (0 = prix déjà TTC).
     * À déplacer vers un paramètre (settings) lors de la phase dédiée.
     */
    const TAUX_TVA = 0.0;

    public function __construct(private StockService $stock) {}

    /**
     * Enregistre une vente (facture, vente, mouvements de stock et lignes)
     * de façon transactionnelle. Les prix sont recalculés côté serveur.
     *
     * @param  array<int, array<string, mixed>>  $lignes
     */
    public function creerVente(array $lignes, Collection $produits): Facture
    {
        return DB::transaction(function () use ($lignes, $produits) {
            $factureDate = $lignes[0]['date'];
            $idClient = $lignes[0]['id_client'];

            // 1) Vérifier les stocks (quantités cumulées par produit) AVANT toute écriture
            $quantites = [];
            foreach ($lignes as $ligne) {
                $produit = $produits->get($ligne['id_produit']);
                if (! $produit) {
                    throw new \RuntimeException('Produit introuvable.');
                }
                $quantites[$ligne['id_produit']] = ($quantites[$ligne['id_produit']] ?? 0) + $ligne['qte'];
            }

            foreach ($quantites as $idProduit => $qteTotale) {
                $disponible = $this->stock->stockProduit($idProduit);
                if ($disponible < $qteTotale || $disponible <= 0) {
                    $nom = $produits->get($idProduit)->nom;
                    throw new \RuntimeException("Quantité de {$nom} insuffisante pour la transaction. Il n'en reste que {$disponible}.");
                }
            }

            // 2) Calculer les totaux à partir des prix serveur
            $totalHt = 0;
            foreach ($lignes as $ligne) {
                $totalHt += round((float) $produits->get($ligne['id_produit'])->prix_unitaire * $ligne['qte'], 2);
            }

            $tva = round($totalHt * self::TAUX_TVA, 2);
            $totalTtc = $totalHt + $tva;

            // 3) Écrire la facture, la vente puis les mouvements/lignes
            $facture = Facture::create([
                'date_facturation' => $factureDate,
                'total_ht' => $totalHt,
                'tva' => $tva,
                'id_client' => $idClient,
                'total_ttc' => $totalTtc,
                'matricule' => 'FACT-TMP-'.uniqid(),
            ]);

            // Numérotation séquentielle stable, dérivée de l'identifiant auto-incrémenté
            $facture->matricule = sprintf('FACT-%s-%06d', date('Y'), $facture->id);
            $facture->save();

            Vente::create([
                'id_saver' => auth()->user()->id,
                'id_facture' => $facture->id,
                'date_vente' => $factureDate,
            ]);

            foreach ($lignes as $ligne) {
                $mouvement = $this->stock->sortieProduit($ligne['id_produit'], $ligne['qte'], $ligne['date']);

                Ligne_facture::create([
                    'mode_paiement' => $ligne['mode_paiement'],
                    'id_facture' => $facture->id,
                    'id_produit' => $ligne['id_produit'],
                    'type_vente' => $ligne['type'],
                    'id_stock_produit' => $mouvement->id,
                    'id_bande' => null,
                    'qte' => $ligne['qte'],
                    'prix_unitaire' => (float) $produits->get($ligne['id_produit'])->prix_unitaire,
                    'total_ligne' => round((float) $produits->get($ligne['id_produit'])->prix_unitaire * $ligne['qte'], 2),
                ]);
            }

            return $facture;
        });
    }
}
