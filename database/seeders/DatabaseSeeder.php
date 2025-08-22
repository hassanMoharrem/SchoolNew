<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Teacher;
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
        Admin::query()->create([
            'name' => 'Test Admin',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        Teacher::query()->create([
            'name' => 'Test Teacher',
            'email' => 'test-teacher@example.com',
            'password' => bcrypt('password'),
            'birthday' => '1987-12-10',
        ]);

        for ($i = 1; $i <= 27; $i++) {
            User::query()->create([
                'name' => 'Test User ' . $i,
                'email' => 'test-user' . $i . '@example.com',
                'birthday' => '2001-07-22',
                'password' => bcrypt('password'),
            ]);
        }
    }
}
