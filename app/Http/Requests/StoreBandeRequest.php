<?php

namespace App\Http\Requests;

use App\Support\TenantValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreBandeRequest extends FormRequest
{
    /**
     * L'accès est déjà contrôlé par le middleware d'authentification et de rôle.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation d'une bande.
     */
    public function rules(): array
    {
        return [
            'nom' => 'bail|required|unique:bandes|max:255|min:4',
            'type_animal' => ['bail', 'required', 'integer', TenantValidation::exists('type_animals')],
            'age_depart' => 'bail|required|numeric|min:1',
            'batiment' => ['bail', 'required', 'integer', TenantValidation::exists('batiments')],
            'unite_age_depart' => 'bail|required|string|in:jour,semaine,mois,annee',
            'nbre_sujet' => 'bail|required|numeric|min:1',
            'date' => 'bail|required|date|before_or_equal:today',
            'prix_acquisition' => 'bail|required|numeric|min:0',
        ];
    }
}
