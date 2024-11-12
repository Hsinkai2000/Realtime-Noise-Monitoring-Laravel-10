<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //\
        $faker = \Faker\Factory::create();
        DB::table('contacts')->insert([
            [
                'project_id' => 1,
                'contact_person_name' => $faker->name,
                'designation' => 'manager',
                'phone_number' => 81889218,
                'email' => 'hsinkai2000@gmail.com',
            ],
            [
                'project_id' => 6,
                'contact_person_name' => $faker->name,
                'designation' => 'manager',
                'phone_number' => 89472222,
                'email' => $faker->email,
            ],
            [
                'project_id' => 6,
                'contact_person_name' => $faker->name,
                'designation' => 'manager',
                'phone_number' => 98123673,
                'email' => $faker->email,
            ],
        ]);

    }
}