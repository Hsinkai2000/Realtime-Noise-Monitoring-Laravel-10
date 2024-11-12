<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoiseMeterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $now = date("Y-m-d");
        DB::table('noise_meters')->insert([
            [

                'serial_number' => 9900,
                'brand' => 'SINUS TANGO',
                'remarks' => $faker->text(),
                'noise_meter_label' => 'meter 1',
                'last_calibration_date' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [

                'serial_number' => 9901,
                'brand' => 'SINUS TANGO',
                'remarks' => $faker->text(),
                'noise_meter_label' => 'meter 2',
                'last_calibration_date' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [

                'serial_number' => 9902,
                'brand' => 'SINUS TANGO',
                'remarks' => $faker->text(),
                'noise_meter_label' => 'meter 3',
                'last_calibration_date' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [

                'serial_number' => 9903,
                'brand' => 'SINUS TANGO',
                'remarks' => $faker->text(),
                'noise_meter_label' => 'meter 4',
                'last_calibration_date' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [

                'serial_number' => 9904,
                'brand' => 'SINUS TANGO',
                'remarks' => $faker->text(),
                'noise_meter_label' => 'meter 5',
                'last_calibration_date' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ], [

                'serial_number' => 1397,
                'brand' => 'Tango',
                'last_calibration_date' => '2024-06-05',
                'remarks' => 'On site hardware tester',
                'noise_meter_label' => 'meter 6',
                'created_at' => '2024-06-05 00:00:00',
                'updated_at' => '2024-06-05 00:00:00',
            ],
        ]);
    }
}