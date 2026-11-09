<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAchatRequest extends FormRequest
{
    /**
     * L'accès est déjà contrôlé par le middleware d'authentification et de rôle.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation d'un achat.
     */
    public function rules(): array
    {
        return [
            'matiere_premiere' => ['bail', 'required', 'integer', 'min:1', 'exists:matiere_premieres,id'],
            'qte' => ['bail', 'required', 'numeric', 'gt:0'],
            'mode_paiement' => ['bail', 'required', 'string'],
            'prix_unitaire' => ['bail', 'required', 'numeric', 'min:0'],
            'unite' => ['required', 'string', 'in:kg,g,sac'],
            'date' => ['bail', 'required', 'date', 'before_or_equal:today'],
            'fournisseur' => ['nullable', 'integer', 'min:1'],
            'sack_weight' => ['nullable', 'required_if:unite,sac', 'numeric', 'min:1'],
        ];
    }
}
