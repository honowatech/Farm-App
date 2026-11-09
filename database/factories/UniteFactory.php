<?php

namespace Database\Factories;

use App\Models\Unite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Unite>
 */
class UniteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Unite::class;

    public function definition(): array
    {
        return [
            'libelle' => $this->faker->word,
            'abrege' => $this->faker->word,
        ];
    }
}
