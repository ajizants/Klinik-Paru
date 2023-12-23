<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
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
