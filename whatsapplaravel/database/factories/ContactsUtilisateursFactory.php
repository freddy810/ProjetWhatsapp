<?php

namespace Database\Factories;

use App\Models\Utilisateurs;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactsUtilisateurs>
 */
class ContactsUtilisateursFactory extends Factory
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
            'numPhone' => $this->faker->numerify('##########'),
            // Pour la clé étrangère, on choisit un utilisateur existant aléatoirement
            'utilisateurPossedantContact_id' => Utilisateurs::inRandomOrder()->first()->id ?? Utilisateurs::factory(),
        ];
    }
}
