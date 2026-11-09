<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Typeusers;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Typeusers>
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
