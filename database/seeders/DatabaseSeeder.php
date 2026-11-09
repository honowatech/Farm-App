<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Typeusers;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Typeusers::create([
            'libelle' => 'admin',
        ]);
        Typeusers::create([
            'libelle' => 'agent',
        ]);

        Typeusers::create([
            'libelle' => 'client',
        ]);

        User::create([
            'id_type_user' => '1',
            'name' => 'Admin Farm-App',
            'password' => Hash::make('11111111'),
            'email' => 'test@example.com',
            'statut' => 1,
        ]);
    }
}
