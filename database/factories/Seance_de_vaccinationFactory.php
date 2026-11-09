<?php

namespace Database\Factories;

use App\Models\Bande;
use App\Models\Seance_de_vaccination;
use App\Models\Veterinaire;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Seance_de_vaccination>
 */
class Seance_de_vaccinationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Seance_de_vaccination::class;

    public function definition(): array
    {
        return [
            'id_veterinaire' => Veterinaire::factory(),
            'vaccin_attribuer' => $this->faker->word,
            'verification' => $this->faker->boolean,
            'description' => $this->faker->text,
            'id_bande' => Bande::inRandomOrder()->first()->id,
        ];
    }
}
