<?php

namespace Database\Factories;

use App\Models\Utilisateurs;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Messages>
 */
class MessagesFactory extends Factory
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
            'contenues' => $this->faker->sentence(),
            'typeMessage' => $this->faker->randomElement(['texte', 'audio', 'video', 'fichier']),
            'statusMessage' => $this->faker->randomElement(['lus', 'non_lus']),
            'dateMessage' => $this->faker->date('Y-m-d'),
            'utilisateurEnvoyeurMessage_id' => $envoyeurId,
            'utilisateurReceveurMessage_id' => $receveurId,
        ];
    }
}
