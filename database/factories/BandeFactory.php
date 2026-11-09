<?php

namespace Database\Factories;

use App\Models\Bande;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bande>
 */
class BandeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Bande::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->unique()->word,
            'date_fin_bande' => $this->faker->date,
            'date_lancement_bande' => $this->faker->date,
            'nbre_sujet' => $this->faker->numberBetween(1, 100),
            'age_depart' => $this->faker->numberBetween(1, 10),
            'statut_bande' => $this->faker->randomElement(['encours', 'terminee']),
            'id_type_animal' => \App\Models\Type_animal::inRandomOrder()->first()->id,
            'id_batiment' => \App\Models\Batiment::factory(),
            'prix_acquisition' => $this->faker->randomFloat(2, 1000, 5000),
        ];
    }
}
