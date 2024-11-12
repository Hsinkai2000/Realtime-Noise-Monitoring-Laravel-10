<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoiseDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faker = \Faker\Factory::create();
        $now = date("Y-m-d H:i:s");
        DB::table('noise_data')->insert([
            [
                'measurement_point_id' => 1,
                'leq' => 34.5,
                'received_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'measurement_point_id' => 2,
                'leq' => 34.5,
                'received_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'measurement_point_id' => 3,
                'leq' => 34.5,
                'received_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'measurement_point_id' => 4,
                'leq' => 34.5,
                'received_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'measurement_point_id' => 5,
                'leq' => 34.5,
                'received_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'measurement_point_id' => 6,
                'leq' => 34.5,
                'received_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'measurement_point_id' => 7,
                'leq' => 34.5,
                'received_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],

        ]);
    }
}