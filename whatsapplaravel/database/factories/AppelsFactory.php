<?php

namespace Database\Factories;

use App\Models\Utilisateurs;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appels>
 */
class AppelsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $envoyeurId = Utilisateurs::inRandomOrder()->first()->id ?? Utilisateurs::factory();
        do {
            $receveurId = Utilisateurs::inRandomOrder()->first()->id ?? Utilisateurs::factory();
        } while ($receveurId == $envoyeurId);

        return [
            'type' => $this->faker->randomElement(['audio', 'video']),
            'status' => $this->faker->randomElement(['appel_manquer', 'appel_reussit', 'appel_decliné']),
            'dateAppel' => $this->faker->date('Y-m-d'),
            'utilisateurEnvoyeur_id' => $envoyeurId,
            'utilisateurReceveur_id' => $receveurId,
        ];
    }
}
