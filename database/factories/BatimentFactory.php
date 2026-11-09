<?php

namespace Database\Factories;

use App\Models\Batiment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Batiment>
 */
class BatimentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Batiment::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->word,
            'description' => $this->faker->text,
        ];
    }
}
