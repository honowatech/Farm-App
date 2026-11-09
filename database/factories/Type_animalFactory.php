<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Type_animal;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Type_animal>
 */
class Type_animalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Type_animal::class;
    public function definition(): array
    {
        return [
            'nom' => $this->faker->unique()->word,
            'description' => $this->faker->text,
            'type_reproduction' => $this->faker->randomElement([ 'ponte', 'normal']),
        ];
    }
}
