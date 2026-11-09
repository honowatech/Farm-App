<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Achat;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Achat>
 */
class AchatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Achat::class;
    public function definition(): array
    {
        return [
            'id_stock_matiere_premiere' =>\App\Models\Stock_matiere_premiere::inRandomOrder()->first()->id, 
            'id_matiere_premiere' => \App\Models\Matiere_premiere::factory(),
            'prix_unitaire' => $this->faker->randomFloat(2, 10, 100),
            'qte' => $this->faker->numberBetween(1, 50),
            'prix_total' => $this->faker->randomFloat(2, 100, 5000),
            'mode_de_paiement' => $this->faker->randomElement(['espece', 'mobilemoney','orangemoney', 'bank']), 
            'id_fournisseur' => \App\Models\Fournisseur::inRandomOrder()->first()->id,
            'date' => $this->faker->date,
        ];
    }
}
