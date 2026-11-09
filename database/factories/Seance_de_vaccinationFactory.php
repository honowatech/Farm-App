<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Seance_de_vaccination;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seance_de_vaccination>
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
            'id_veterinaire' => \App\Models\Veterinaire::factory(),
            'vaccin_attribuer' => $this->faker->word,
            'verification' => $this->faker->boolean,
            'description' => $this->faker->text,
            'id_bande' => \App\Models\Bande::inRandomOrder()->first()->id,
        ];
    }
}
