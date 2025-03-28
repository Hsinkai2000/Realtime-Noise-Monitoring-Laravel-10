<?php

namespace App\Models;

use App\Mail\EmailAlert;
use App\Services\TwilioService;
use DateTime;
use Exception;
use function PHPUnit\Framework\isNull;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MeasurementPoint extends Model
{
    use HasFactory;

    const LEQ_SMS_TEMPLATE = 'sms.sms_leq_limit_exceeded';
    const DOSE_SMS_TEMPLATE = 'sms.sms_dose_limit_exceeded';
    const MISSING_DATA_TEMPLATE = 'sms.sms_missing_data_45_mins';

    protected $table = 'measurement_points';

    protected $fillable = [
        'project_id',
        'noise_meter_id',
        'concentrator_id',
        'point_name',
        'remarks',
        'inst_leq',
        'leq_temp',
        'dose_flag',
        'device_location',
        'leq_5_mins_last_alert_at',
        'leq_1_hour_last_alert_at',
        'leq_12_hours_last_alert_at',
        'dose_70_last_alert_at',
        'dose_100_last_alert_at',
        'missing_data_last_alert_at',
        'alert_start_time',
        'alert_end_time',
        'alert_days',
        'alert_mode',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'project_id' => 'integer',
        'noise_meter_id' => 'integer',
        'concentrator_id' => 'integer',
        'inst_leq' => 'float',
        'leq_temp' => 'integer',
        'dose_flag' => 'decimal:11',
        'leq_5_mins_last_alert_at' => 'datetime',
        'leq_1_hour_last_alert_at' => 'datetime',
        'leq_12_hours_last_alert_at' => 'datetime',
        'dose_70_last_alert_at' => 'datetime',
        'dose_100_last_alert_at' => 'datetime',
        'missing_data_last_alert_at' => 'datetime',
        'alert_start_time' => 'string',
        'alert_end_time' => 'string',
        'alert_days' => 'string',
        'alert_mode' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    private static $timeSlots = [
        '7am_7pm' => ['start' => '07:00', 'end' => '18:59'],
        '7pm_10pm' => ['start' => '19:00', 'end' => '06:59'],
        '10pm_12am' => ['start' => '19:00', 'end' => '06:59'],
        '12am_7am' => ['start' => '19:00', 'end' => '06:59'],
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function concentrator(): HasOne
    {
        return $this->hasOne(Concentrator::class, 'id', 'concentrator_id');
    }

    public function noiseMeter(): HasOne
    {
        return $this->hasOne(NoiseMeter::class, 'id', 'noise_meter_id');
    }

    public function noiseData(): HasMany
    {
        return $this->hasMany(NoiseData::class, 'measurement_point_id', 'id');
    }

    public function soundLimit(): HasOne
    {
        return $this->hasOne(SoundLimit::class, 'measurement_point_id', 'id');
    }

    public function hasProject()
    {
        return $this->project !== null;
    }

    public function has_running_project()
    {
        return $this->project->isRunning();
    }

    public function dose_flag_reset()
    {
        $lastLeq = $this->getLastLeqData();
        $soundLimit = $this->soundLimit;

        if ($lastLeq) {
            $last_date_time = $lastLeq->received_at;
            if ($soundLimit->check_12_1_hour_limit_type($last_date_time) == '12') {
                $resetHours = range(7, 19);
            } else {
                $resetHours = range(0, 23);
            }
            $hour = $last_date_time->format('H');
            $minute = $last_date_time->format('i');

            return in_array($hour, $resetHours) && ($minute < 6);
        }

        return false;
    }

    public function check_last_data_for_alert($last_noise_data = null, $sendAlert = false)
    {

        $num_blanks = 0;
        $leq_12_should_alert = false;
        $leq_1_should_alert = false;
        $dose_100_should_alert = false;
        $dose_70_should_alert = false;
        $calculated_dose_percentage = null;

        $soundLimit = $this->soundLimit;

        [$leq_5mins_should_alert, $leq5limit] = $this->leq_5_mins_exceed_and_alert($last_noise_data);

        [$decision, $last_data_datetime] = $soundLimit->check_12_1_hour_limit_type($last_noise_data->received_at);

        if ($decision == '12h') {

            [$leq_12_should_alert, $leq12hlimit, $calculated12hLeq, $num_blanks] = $this->leq_12_hours_exceed_and_alert($last_data_datetime, $last_noise_data);

            $calculated_dose_percentage = $soundLimit->calculate_dose_perc($calculated12hLeq, $leq12hlimit, $num_blanks, 144);
        } else {
            [$leq_1_should_alert, $leq1hlimit, $calculated1hLeq, $num_blanks] = $this->leq_1_hour_exceed_and_alert($last_data_datetime, $last_noise_data);
            $calculated_dose_percentage = $soundLimit->calculate_dose_perc($calculated1hLeq, $leq1hlimit, $num_blanks, 12);
        }

        $dose_70_should_alert = $this->last_alert_allowed($this->dose_70_last_alert_at, $last_noise_data->received_at) && $calculated_dose_percentage >= 70 && $calculated_dose_percentage < 100;
        $dose_100_should_alert = $this->last_alert_allowed($this->dose_100_last_alert_at, $last_noise_data->received_at) && $calculated_dose_percentage >= 100;

        if (!$sendAlert) {
            return $decision == '12h' ? [$calculated_dose_percentage, $num_blanks, $leq12hlimit, $decision] : [$calculated_dose_percentage, $num_blanks, $leq1hlimit, $decision];
        } else {
            $alert_status = $this->check_alert_status($last_noise_data->received_at);
            if ($alert_status) {
                $data = [
                    "device_location" => $this->device_location,
                    "serial_number" => $this->noiseMeter->serial_number,
                    "leq_value" => null,
                    "exceeded_limit" => null,
                    "leq_type" => null,
                    "exceeded_time" => $last_noise_data->received_at,
                    "type" => 'dose',
                    "dose_limit" => null,
                    "calculated_dose" => $calculated_dose_percentage,
                    "measurement_point_name" => $this->point_name,
                    "email_alert" => $alert_status["email_alert"],
                    "sms_alert" => $alert_status["sms_alert"]
                ];

                if ($dose_100_should_alert) {
                    $data["dose_limit"] = '100';
                    if ($this->dose_100_last_alert_at < $last_noise_data->received_at)
                        $this->dose_100_last_alert_at = $last_noise_data->received_at;
                    $this->send_alert($data);
                } else if ($dose_70_should_alert) {
                    $data["dose_limit"] = '70';
                    if ($this->dose_70_last_alert_at < $last_noise_data->received_at)
                        $this->dose_70_last_alert_at = $last_noise_data->received_at;
                    $this->send_alert($data);
                }

                $data["type"] = 'leq';
                if ($leq_5mins_should_alert) {
                    if ($this->leq_5_mins_last_alert_at < $last_noise_data->received_at)
                        $this->leq_5_mins_last_alert_at = $last_noise_data->received_at;
                    $data["leq_type"] = "5min";
                    $data["exceeded_limit"] = $leq5limit;
                    $data["leq_value"] = $last_noise_data->leq;
                    $this->send_alert($data);
                }
                if ($leq_12_should_alert) {
                    if ($this->leq_12_hours_last_alert_at < $last_noise_data->received_at)
                        $this->leq_12_hours_last_alert_at = $last_noise_data->received_at;
                    $data["leq_type"] = "12h";
                    $data["exceeded_limit"] = $leq12hlimit;
                    $data["leq_value"] = round($calculated12hLeq, 1);
                    $this->send_alert($data);
                } else if ($leq_1_should_alert) {
                    if ($this->leq_1_hour_last_alert_at < $last_noise_data->received_at)
                        $this->leq_1_hour_last_alert_at = $last_noise_data->received_at;
                    $data["leq_type"] = "1h";
                    $data["exceeded_limit"] = $leq1hlimit;
                    $data["leq_value"] = round($calculated1hLeq, 1);
                    $this->send_alert($data);
                }

                $this->save();
            }
        }
    }

    private function check_alert_time($received_at)
    {
        $received_at_timing = null;
        if ($received_at) {
            $received_at_timing = $received_at->format('H:i');
        } else {
            $received_at_timing = now()->format('H:i');
        }
        $alertStartTime = Carbon::createFromFormat('H:i', $this->alert_start_time, 'Asia/Singapore');
        $alertEndTime = Carbon::createFromFormat('H:i', $this->alert_end_time, 'Asia/Singapore');
        return $received_at_timing >= $alertStartTime->format('H:i') && $received_at_timing <= $alertEndTime->format('H:i');
    }

    private function check_alert_day($received_at)
    {
        $currentDay = null;
        if ($received_at) {
            $currentDay = $received_at->format('D');
        } else {
            $currentDay = now()->format('D');
        }
        $alert_days_array = explode(', ', $this->alert_days);
        return in_array($currentDay, $alert_days_array);
    }

    private function check_alert_type()
    {
        switch ($this->alert_mode) {
            case 1:
                return [
                    "email_alert" => true,
                    "sms_alert" => false
                ];
            case 2:
                return [
                    "email_alert" => true,
                    "sms_alert" => true
                ];
            default:
                return [];
        }
    }

    public function check_alert_status($received_at = null)
    {
        if ($this->check_alert_day($received_at) && $this->check_alert_time($received_at)) {
            return $this->check_alert_type();
        }
        return [];
    }

    public function send_alert($data)
    {
        $contacts = $this->project->get_contact_details();

        foreach ($contacts as $contact) {
            $data["client_name"] = $contact['contact_person_name'];
            $email_messageid = null;
            $email_messagedebug = null;
            $sms_messageid = null;
            $sms_status = null;

            if ($data['email_alert']) {
                [$email_messageid, $email_messagedebug] = $this->send_email($data, $contact['email']);
            }
            if ($data['sms_alert']) {
                [$sms_messageid, $sms_status] = $this->send_sms($data, $contact['phone_number']);
            }

            DB::table('alert_logs')->insert([
                'event_timestamp' => $data["exceeded_time"],
                'email_messageId' => $email_messageid,
                'email_debug' => $email_messagedebug,
                'sms_messageId' => $sms_messageid,
                'sms_status' => $sms_status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    public function send_email($data, $emails)
    {
        if (!empty($emails)) {
            try {
                // Convert a string of emails into an array, if necessary
                $emailArray = is_string($emails) ? explode(';', $emails) : $emails;
                // Send the email to multiple recipients
                $email_response = Mail::to($emailArray)->send(new EmailAlert($data));

                // Get message ID and debug information if needed
                $email_messageid = $email_response->getSymfonySentMessage()->getMessageId();
                $email_messagedebug = $email_response->getSymfonySentMessage()->getDebug();
            } catch (Exception $e) {
                \Log::error("Error sending email", [$e->getMessage()]);
                $email_messagedebug = $e;
            }
        }
        return [$email_messageid, $email_messagedebug];
    }


    private function send_sms($data, $phone_number)
    {
        $sms_messageid = '';
        $sms_status = 'SMS not sent';
        if (!empty($phone_number)) {
            $phone_number = "+65" . $phone_number;
            try {
                $twilio_service = new TwilioService();
                if ($data["type"] == 'leq') {
                    $sms_response = $twilio_service->sendMessage($phone_number, self::LEQ_SMS_TEMPLATE, $data);
                } else if ($data["type"] == 'dose') {
                    $sms_response = $twilio_service->sendMessage($phone_number, self::DOSE_SMS_TEMPLATE, $data);
                } else if ($data['type'] == 'missing_data') {
                    $sms_response = $twilio_service->sendMessage($phone_number, self::MISSING_DATA_TEMPLATE, $data);
                }
                if (isNull($sms_response->sid)) {
                    $sms_messageid = $sms_response->sid;
                    $sms_status = "SMS sending";
                }
            } catch (Exception $e) {
                \Log::debug("Message not sent");
                \Log::error($e);
            }
        } else {
            \Log::debug("No Phone Number Found");
        }
        return [$sms_messageid, $sms_status];
    }

    public function leq_5_mins_exceed_and_alert($last_noise_data = null)
    {
        $limit = $this->soundLimit->leq5_limit($last_noise_data->received_at);
        $should_alert = $last_noise_data->leq >= $limit && $this->last_alert_allowed($this->leq_5_mins_last_alert_at, $last_noise_data->received_at);
        return [$should_alert, $limit];
    }

    public function leq_12_hours_exceed_and_alert($last_data_datetime, $last_noise_data = null)
    {

        [$twelve_hr_leq, $num_blanks] = $this->calc_12_hour_leq($last_noise_data->received_at);
        $limit = $this->soundLimit->leq12h_limit($last_data_datetime);
        $should_alert = round($twelve_hr_leq, 1) >= $limit && $this->last_alert_allowed($this->leq_12_hours_last_alert_at, $last_noise_data->received_at);
        return [$should_alert, $limit, $twelve_hr_leq, $num_blanks];
    }

    private function leq_1_hour_exceed_and_alert($last_data_datetime, $last_noise_data = null)
    {
        [$one_hr_leq, $num_blanks] = $this->calc_1_hour_leq($last_noise_data->received_at);

        $limit = $this->soundLimit->leq1h_limit($last_data_datetime);
        $should_alert = round($one_hr_leq, 1) >= $limit && $this->last_alert_allowed($this->leq_1_hour_last_alert_at, $last_noise_data->received_at);
        return [$should_alert, $limit, $one_hr_leq, $num_blanks];
    }

    private function get_current_date($last_noise_data_datetime = null, $param = null)
    {
        if ($last_noise_data_datetime == null) {
            $last_noise_data_datetime = $this->getLastLeqData()->received_at;
        } else {
            $last_noise_data_datetime = new DateTime($last_noise_data_datetime);
        }
        return $param == null ? $last_noise_data_datetime->format('Y-m-d') : $last_noise_data_datetime->modify($param)->format('Y-m-d');
    }

    private function get_final_time_start_stop($last_noise_data_date, $time_range)
    {
        $last_noise_data_start_date = $last_noise_data_date;
        $last_noise_data_end_date = $last_noise_data_date;
        if ($time_range == array_keys(self::$timeSlots)[3]) {
            $last_noise_data_start_date = $this->get_current_date($last_noise_data_date, '-1 day');
        } else if ($time_range != array_keys(self::$timeSlots)[0]) {
            $last_noise_data_end_date = $this->get_current_date($last_noise_data_date, '+1 day');
        }

        $start_time = new DateTime($last_noise_data_start_date . ' ' . self::$timeSlots[$time_range]['start']);
        $end_time = new DateTime($last_noise_data_end_date . ' ' . self::$timeSlots[$time_range]['end']);

        return [$start_time, $end_time];
    }

    public function get_hour_to_now_leq($last_noise_data_base_hour)
    {
        if ($last_noise_data_base_hour == null) {
            $last_noise_data_base_hour = $this->getLastLeqData()->received_at;
        }

        $last_noise_data_base_hour->setTime($last_noise_data_base_hour->format("H"), 0, 0);

        $last_noise_data_base_end_hour = clone $last_noise_data_base_hour;
        $last_noise_data_base_end_hour->modify('+1 hour');
        $last_noise_data_base_end_hour->modify('-1 minute');

        $hour_to_now_leqs = $this->noiseData()->whereBetween('received_at', [$last_noise_data_base_hour, $last_noise_data_base_end_hour])->get()->reverse();
        return $hour_to_now_leqs;
    }

    private function get_timesslot_start_end_datetime($time)
    {
        if ($time == null) {
            [$day, $time_range] = $this->soundLimit->getTimeRange($this->getLastLeqData()->received_at);
            $last_noise_data_date = $this->get_current_date();
        } else {
            [$day, $time_range] = $this->soundLimit->getTimeRange($time);
            $last_noise_data_date = $time->format('Y-m-d');
        }

        [$start_datetime, $end_datetime] = $this->get_final_time_start_stop($last_noise_data_date, $time_range);

        return [$start_datetime, $end_datetime];
    }

    public function get_timeslot_to_now_leq($time)
    {

        [$start_datetime, $end_datetime] = $this->get_timesslot_start_end_datetime($time);

        $timeslot_to_now_leqs = $this->noiseData()->whereBetween('received_at', [$start_datetime, $end_datetime])->get()->reverse();
        return $timeslot_to_now_leqs;
    }

    private function calc_leq($data)
    {
        if ($data->isNotEmpty()) {

            $sum = 0.0;
            foreach ($data as $leqData) {
                $currentLeq = $leqData->leq;
                $sum += round(linearise_leq($currentLeq), 1);
            }

            $avgLeq = $sum / count($data);
            $calculatedLeq = convert_to_db($avgLeq);

            return $calculatedLeq;
        }

        return 0;
    }

    public function calc_12_hour_leq(DateTime $time = null)
    {
        $timeslot_to_now_leqs = $this->get_timeslot_to_now_leq($time);

        $num_blanks = 144 - count($timeslot_to_now_leqs);
        return [$this->calc_leq($timeslot_to_now_leqs), $num_blanks];
    }

    public function calc_1_hour_leq(DateTime $time = null)
    {
        $hour_to_now_leqs = $this->get_hour_to_now_leq($time);
        $num_blanks = 12 - count($hour_to_now_leqs);
        return [$this->calc_leq($hour_to_now_leqs), $num_blanks];
    }

    private function last_alert_allowed($freq_last_alert_at, $last_received_datetime)
    {

        if (!is_null($freq_last_alert_at)) {
            $timeDifference = abs($last_received_datetime->getTimestamp() - $freq_last_alert_at->getTimestamp());
            if ($timeDifference <= 3 * 3600) {
                return false;
            }
        }
        return true;
    }

    public function getLastLeqData()
    {
        return $this->noiseData()->orderBy('received_at', 'desc')->first();
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }

    public function check_data_status()
    {
        if ($this->noiseMeter && $this->concentrator) {
            $last_data = $this->noiseData()->orderBy('received_at', 'desc')->first();
            if ($last_data) {
                $receivedAtCarbon = Carbon::parse($last_data->received_at);
                $currentTime = Carbon::now();
                $diffInMinutes = $currentTime->diffInMinutes($receivedAtCarbon);
                return $diffInMinutes <= 60;
            }
        }
        return true;
    }

    public function getFirstDataOfDay(string $date)
    {
        $startTime = Carbon::parse($date . ' 07:00:00');
        $endTime = Carbon::parse($date . ' 06:59:59')->addDay();

        $noise_data = $this->noiseData()->whereBetween('received_at', [$startTime, $endTime])
            ->orderBy('received_at', 'asc')
            ->first();
        if ($noise_data) {
            return $noise_data->noiseMeter->serial_number;
        }

        if ($this->noiseMeter) {
            return $this->noiseMeter->serial_number;
        }
        return null;
    }

    public function check_last_missing_data()
    {
        $currDate = Carbon::now();
        if ($this->missing_data_last_alert_at) {
            return $currDate->isSameDay($this->missing_data_last_alert_at);
        }
        return false;
    }
}
