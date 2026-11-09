<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Unite;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unite>
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
