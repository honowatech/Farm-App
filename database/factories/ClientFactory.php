<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
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
            'id_user' => User::factory(),
            'statut' => $this->faker->boolean,
        ];
    }
}
