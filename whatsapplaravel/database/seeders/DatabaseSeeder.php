<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Appels;
use App\Models\ContactsUtilisateurs;
use App\Models\Messages;
use App\Models\Utilisateurs;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Utilisateurs::factory(5)->create();

        ContactsUtilisateurs::factory(5)->create();

        Messages::factory(5)->create();

        Appels::factory(5)->create();
    }
}
