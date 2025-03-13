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
                $last_missing_data_on_same_day = $mp->check_last_missing_data();
                if (!$result && !$last_missing_data_on_same_day) {
                    $data = [
                        "device_location" => $mp->device_location,
                        "serial_number" => $mp->noiseMeter->serial_number,
                        "exceeded_time" => Carbon::now(),
                        "type" => 'missing_data',
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
                    $mp->missing_data_last_alert_at = now();
                    $mp->save();
                    sleep(5);
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
