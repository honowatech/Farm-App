<?php

namespace Database\Factories;

use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit>
 */
class ProduitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Produit::class;

    public function definition(): array
    {
        return [
            'id_type_animal' => \App\Models\Type_animal::inRandomOrder()->first()->id,
            'prix_unitaire' => $this->faker->randomFloat(2, 10, 100),
            'nom' => $this->faker->unique()->word,
            'description' => $this->faker->text,
            'statut' => $this->faker->boolean,
        ];
    }
}
