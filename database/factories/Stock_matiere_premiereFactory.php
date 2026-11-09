<?php

namespace Database\Factories;

use App\Models\Stock_matiere_premiere;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock_matiere_premiere>
 */
class Stock_matiere_premiereFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Stock_matiere_premiere::class;

    public function definition(): array
    {
        return [
            'id_matiere_premiere' => \App\Models\Matiere_premiere::inRandomOrder()->first()->id,
            'type_mouvement' => $this->faker->randomElement(['approvisionnement', 'destockage']),
            'quantite' => $this->faker->randomFloat(2, 10, 1000), // Quantité entre 10 et 1000
            'dernier_stock' => $this->faker->randomFloat(2, 0, 1000), // Dernier stock entre 0 et 1000
            'nouveau_stock' => $this->faker->randomFloat(2, 10, 1000), // Nouveau stock entre 10 et 1000
            'date_entree' => $this->faker->optional()->dateTimeThisYear(),
            'date_sortie' => $this->faker->optional()->dateTimeThisYear(),
            'motif' => $this->faker->sentence,
        ];
    }
}
