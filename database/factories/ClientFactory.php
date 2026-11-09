<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Client::class;
    public function definition(): array
    {
        return [
            'nom' => $this->faker->lastName,
            'telephone' => $this->faker->phoneNumber,
            'adresse' => $this->faker->address,
            'email' => $this->faker->unique()->safeEmail,
            'id_user' => \App\Models\User::factory(),
            'statut' => $this->faker->boolean,
        ];
    }
}
