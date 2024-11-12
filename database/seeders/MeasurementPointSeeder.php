<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeasurementPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $now = date("Y-m-d H:i:s");

        DB::table('measurement_points')->insert([
            [
                "project_id" => 1,
                'noise_meter_id' => 1,
                'concentrator_id' => 1,
                'point_name' => 'zz81889218',
                'remarks' => $faker->text(),
                'device_location' => 'zzKakiBukit',
            ],
            [
                "project_id" => 7,
                'noise_meter_id' => 2,
                'concentrator_id' => 2,
                'point_name' => 'Basement point',
                'remarks' => $faker->text(),
                'device_location' => 'kaki bukit',
            ],
            [
                "project_id" => 6,
                'noise_meter_id' => 3,
                'concentrator_id' => 3,
                'point_name' => 'L1 window',
                'remarks' => $faker->text(),
                'device_location' => 'kaki bukit',
            ],
            [
                "project_id" => 6,
                'noise_meter_id' => 4,
                'concentrator_id' => 4,
                'point_name' => 'L2 window',
                'remarks' => $faker->text(),
                'device_location' => 'kaki bukit',
            ],
            [
                "project_id" => 6,
                'noise_meter_id' => 5,
                'concentrator_id' => 5,
                'point_name' => 'Roof',
                'remarks' => $faker->text(),
                'device_location' => 'kaki bukit',
            ], [
                'project_id' => 1,
                'noise_meter_id' => 2,
                'concentrator_id' => 2,
                'point_name' => 'Onsite testing',
                'remarks' => 'testing',
                'device_location' => 'kaki bukit',
            ],
        ]);

    }
}