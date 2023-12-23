<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'admin',
            'role' => 'admin',
            'email' => 'admin@tindakan.com',
            'password' => bcrypt('enter'),



            'name' => 'tindakan',
            'role' => 'user',
            'email' => 'user@tindakan.com',
            'password' => bcrypt('enter'),
        ]);
    }
}
