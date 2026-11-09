<?php

namespace Database\Factories;

use App\Models\Matiere_premiere;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Matiere_premiere>
 */
class Matiere_premiereFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Matiere_premiere::class;

    public function definition(): array
    {
        return [
            'nom_produit' => $this->faker->word,
            'description' => $this->faker->text,
            'prix_unitaire' => $this->faker->randomFloat(2, 10, 100),
            'statut' => $this->faker->boolean,
        ];
    }
}
