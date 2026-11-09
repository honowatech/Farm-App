<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVenteRequest extends FormRequest
{
    /**
     * L'accès est déjà contrôlé par le middleware d'authentification et de rôle.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation.
     */
    public function rules(): array
    {
        return [
            'tableau_donnees' => ['required', 'string'],
        ];
    }

    /**
     * Décode et normalise la liste des lignes de vente.
     *
     * @return array<int, array{id_produit:int, qte:float, type:string|null, mode_paiement:string|null, date:string, id_client:int|null}>
     */
    public function lignes(): array
    {
        $data = json_decode($this->input('tableau_donnees'), true);

        if (! is_array($data)) {
            return [];
        }

        $lignes = [];
        foreach ($data as $item) {
            $lignes[] = [
                'id_produit' => (int) ($item['id_produit'] ?? 0),
                'qte' => (float) ($item['qte'] ?? 0),
                'type' => $item['type'] ?? null,
                'mode_paiement' => $item['mode_paiement'] ?? null,
                'date' => $item['date'] ?? now()->toDateTimeString(),
                'id_client' => ! empty($item['id_client']) ? (int) $item['id_client'] : null,
            ];
        }

        return $lignes;
    }
}
