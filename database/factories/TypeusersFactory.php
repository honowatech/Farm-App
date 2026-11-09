<?php

namespace Database\Factories;

use App\Models\Typeusers;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Typeusers>
 */
class TypeusersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Typeusers::class;

    public function definition(): array
    {
        return [
            'libelle' => $this->faker->word,
        ];
    }
}
