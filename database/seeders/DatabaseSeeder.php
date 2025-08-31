<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Stage;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
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

        for ($i = 1; $i <= 28; $i++) {
            User::query()->create([
                'name' => 'Test User ' . $i,
                'email' => 'test-user' . $i . '@example.com',
                'birthday' => '2001-07-22',
                'password' => bcrypt('password'),
            ]);

            Teacher::query()->create([
                'name' => 'Test Teacher ' . $i,
                'email' => 'test-teacher' . $i . '@example.com',
                'birthday' => '1987-12-10',
                'password' => bcrypt('password'),
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            Stage::query()->create([
                'name' => 'Test Stage ' . $i,
                'description' => 'Description Test Stage' . $i,
            ]);

            Subject::query()->create([
                'name' => 'Test Subject ' . $i,
                'stage_id' => 1,
                'description' => 'Description Test Subject' . $i,
                'sub_description' => 'Sub Description Test Subject' . $i,
            ]);
        }
    }
}
