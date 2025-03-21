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

        $schedule->call(function () {
            $currentTime = Carbon::today();
            $startTime = $currentTime->copy()->setHour(7)->setMinute(0);
            $endTime = $currentTime->copy()->setHour(12)->setMinute(0);

            // Eager load relationships to prevent N+1 queries
            $mps = MeasurementPoint::with(['noiseData' => function ($query) use ($startTime, $endTime) {
                $query->whereBetween('received_at', [$startTime, $endTime])
                    ->latest('received_at');
            }, 'noiseMeter', 'project.contact', 'soundLimit'])->get();

            foreach ($mps as $mp) {
                $last_noise_data = $mp->noiseData->first();
                $alert_status = $mp->check_alert_status($endTime);

                if (!$last_noise_data || !$alert_status) {
                    continue;
                }
                [$leq_12_should_alert, $leq12hlimit, $calculated12hLeq, $num_blanks] =
                    $mp->leq_12_hours_exceed_and_alert($endTime, $last_noise_data);

                $calculated_dose_percentage = $mp->soundLimit->calculate_dose_perc(
                    $calculated12hLeq,
                    $leq12hlimit,
                    $num_blanks,
                    144
                );

                $base_data = [
                    "device_location" => $mp->device_location,
                    "serial_number" => $mp->noiseMeter->serial_number,
                    "dose_perc" => $calculated_dose_percentage,
                    "type" => 'noon_check',
                    "measurement_point_name" => $mp->point_name,
                ];


                [$day, $time_range] = $mp->soundLimit->getTimeRage($endTime);

                if ($day == 'sun_ph') {
                    $base_data['leq5_7am_7pm'] = $mp->soundLimit->sun_ph_7am_7pm_leq5min;
                    $base_data['leq12_7am_7pm'] = $mp->soundLimit->sun_ph_7am_7pm_leq12hr;
                } else {
                    $base_data['leq5_7am_7pm'] = $mp->soundLimit->mon_sat_7am_7pm_leq5min;
                    $base_data['leq12_7am_7pm'] = $mp->soundLimit->mon_sat_7am_7pm_leq12hr;
                }

                if ($calculated_dose_percentage < 100) {
                    if ($calculated_dose_percentage < 100 && $num_blanks != 12 && $num_blanks != 144) {
                        $missingVal = 144;
                        [$leq_5mins_should_alert, $leq5limit] = $mp->leq_5_mins_exceed_and_alert($last_noise_data);

                        $sum = round(convert_to_db((1 - ($calculated_dose_percentage / 100)) * ((linearise_leq($base_data['leq5_7am_7pm']) * $missingVal) / $num_blanks)), 1);

                        $leq_data['leq5max'] = min([$sum, $leq5limit]);
                    } else {
                        $leq_data['leq5max'] = 'N.A.';
                    }
                }

                if ($alert_status["email_alert"]) {
                    foreach ($mp->project->contact as $contact) {
                        $base_data["client_name"] = $contact['contact_person_name'];
                        [$email_messageid, $email_messagedebug] = $mp->send_email($base_data, $contact['email']);

                        DB::table('alert_logs')->insert([
                            'event_timestamp' => $endTime,
                            'email_messageId' => $email_messageid,
                            'email_debug' => $email_messagedebug,
                            'sms_messageId' => null,
                            'sms_status' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                sleep(5);
            }
        })->dailyAt("12:00");
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