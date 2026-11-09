<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Salaries;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Salaries>
 */
class SalariesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Salaries::class;
    public function definition(): array
    {
         return [
            'nom' => $this->faker->name,
            'adresse' => $this->faker->address,
            'telephone' => $this->faker->phoneNumber,
            'cni' => $this->faker->unique()->numerify('########'),
            'telephone_secondaire' => $this->faker->phoneNumber,
            'id_saver' => \App\Models\User::factory(),
            'accumulateur' => $this->faker->randomFloat(2, 0, 1000),
             'type_periode' => $this->faker->randomElement(['heure', 'jour', 'semaine', 'mois', 'annee']),
            'salaire' => $this->faker->randomFloat(2, 2000, 5000),
            'date_debut_contrat' => $this->faker->date,
            'date_dernier_paiement' => $this->faker->date,
            'statut' => $this->faker->boolean,
            'date_modif' => $this->faker->date,
        ];
    }
}
