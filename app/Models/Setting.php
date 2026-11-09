<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    private array $data = [

        [
            'id' => 1,
            'title' => 'Logo du site',
            'ressource' => 'logo',
            'path' => 'admin/pages/setting/logo/include',
        ],
        [
            'id' => 2,
            'title' => 'Gérer les Unités',
            'ressource' => 'unite',
            'path' => 'admin/pages/setting/unites/include',
        ],
        [
            'id' => 3,
            'title' => 'Paramèttre de nutrition',
            'ressource' => 'foods-setting',
            'path' => 'admin/pages/setting/foods-setting/include',
        ],

        // ... ajouter autres paramètres
    ];

    public function get(): array
    {
        return $this->data;
    }
}
