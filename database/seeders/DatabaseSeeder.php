<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'age' => 15,
            'password' => bcrypt('password'),
        ]);
            Admin::factory()->create([
            'name' => 'Test Admin',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}
