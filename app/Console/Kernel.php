<?php

namespace App\Console;

use App\Models\MeasurementPoint;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $mps = MeasurementPoint::all();
            foreach ($mps as $mp) {
                $result = $mp->check_data_status();
                if (!$result) {
                    $data = [
                        "jobsite_location" => $mp->project->jobsite_location,
                        "serial_number" => $mp->noiseMeter->serial_number,
                        "leq_value" => null,
                        "exceeded_limit" => null,
                        "leq_type" => null,
                        "exceeded_time" => Carbon::now(),
                        "type" => 'missing_data',
                        "dose_limit" => null,
                        "calculated_dose" => null,
                        "measurement_point_name" => $mp->point_name,
                    ];
                    $mp->send_alert($data);
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}