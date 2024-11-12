<?php

namespace Database\Seeders;

use App\Models\SoundLimit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SoundLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = \Faker\Factory::create();

        for ($i = 1; $i < 7; $i++) {
            SoundLimit::create(['measurement_point_id' => $i]);
            DB::table('sound_limits')->insert([
                'category' => 'Residential',
                'measurement_point_id' => 2,
                'mon_sat_7am_7pm_leq5min' => 90,
                'mon_sat_7pm_10pm_leq5min' => 70,
                'mon_sat_10pm_12am_leq5min' => 55,
                'mon_sat_12am_7am_leq5min' => 55,
                'sun_ph_7am_7pm_leq5min' => 75,
                'sun_ph_7pm_10pm_leq5min' => 55,
                'sun_ph_10pm_12am_leq5min' => 55,
                'sun_ph_12am_7am_leq5min' => 55,
                'mon_sat_7am_7pm_leq12hr' => 75,
                'mon_sat_7pm_10pm_leq12hr' => 65,
                'mon_sat_10pm_12am_leq12hr' => 55,
                'mon_sat_12am_7am_leq12hr' => 55,
                'sun_ph_7am_7pm_leq12hr' => 75,
                'sun_ph_7pm_10pm_leq12hr' => 140,
                'sun_ph_10pm_12am_leq12hr' => 140,
                'sun_ph_12am_7am_leq12hr' => 140,
                'created_at' => '2024-06-05 08:11:48',
                'updated_at' => '2024-06-05 08:11:48',
            ]);
        }

    }
}