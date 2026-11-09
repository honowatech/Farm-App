<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Veterinaire;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Veterinaire>
 */
class VeterinaireFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
     protected $model = Veterinaire::class;
    public function definition(): array
    {
        return [
            'nom' => $this->faker->name,
            'adresse' => $this->faker->address,
            'telephone' => $this->faker->phoneNumber,
            'telephone_secondaire' => $this->faker->phoneNumber,
            'statut' => $this->faker->boolean,
        ];
    }
}
