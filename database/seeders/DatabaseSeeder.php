<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
        \App\Models\Typeusers::create([
            'libelle' => 'admin',
        ]);
        \App\Models\Typeusers::create([
            'libelle' => 'agent',
        ]);

        \App\Models\Typeusers::create([
            'libelle' => 'client',
        ]);

        \App\Models\User::create([
            'id_type_user' => '1',
            'name' => 'Admin Farm-App',
            'password' => Hash::make('11111111'),
            'email' => 'test@example.com',
        ]);
    }
}
