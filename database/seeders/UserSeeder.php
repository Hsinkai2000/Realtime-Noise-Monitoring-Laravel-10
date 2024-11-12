<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Guesser\Name;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $now = date("Y-m-d H:i:s");
        User::create([
            'user_type' => 'admin',
            'project_id' => 1,
            'username' => $faker->name(),
            'password' => Hash::make('abc123456'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        User::create([
            'user_type' => 'admin',
            'project_id' => 6,
            'username' => 'kai',
            'password' => Hash::make('kai123!'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}