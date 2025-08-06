<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Utilisateurs>
 */
class UtilisateursFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'type' => $this->faker->randomElement(['simple_utilisateur', 'administrateur']),
            'dateNaissance' => $this->faker->date('Y-m-d', '2003-01-01'), // date avant 2003
            'telephone' => $this->faker->numerify('##########'), // 10 chiffres
            'photoProfil' => null, // tu peux gérer une image si besoin mais compliqué en faker
            'status' => $this->faker->randomElement(['en_ligne', 'hors_ligne']),
            'motDePasse' => $this->faker->word(),
        ];
    }
}
