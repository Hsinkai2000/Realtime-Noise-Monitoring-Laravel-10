<?php

namespace App\Console;

use App\Models\MeasurementPoint;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

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
                        "device_location" => $mp->device_location,
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

                    [$email_messageid, $email_messagedebug] = $mp->send_email($data, env("NO_DATA_EMAIL"));

                    DB::table('alert_logs')->insert([
                        'event_timestamp' => $data["exceeded_time"],
                        'email_messageId' => $email_messageid,
                        'email_debug' => $email_messagedebug,
                        'sms_messageId' => null,
                        'sms_status' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    \Log::info("FAILED", [$mp]);
                } else {
                    \Log::info("PASSED", [$mp]);
                }
            }
        })->hourly();
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
