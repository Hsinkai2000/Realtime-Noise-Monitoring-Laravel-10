<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoundLimit extends Model
{
    use HasFactory;
    protected $table = 'sound_limits';

    protected $fillable = [
        'measurement_point_id',
        'category',
        'mon_sat_7am_7pm_leq5min',
        'mon_sat_7pm_10pm_leq5min',
        'mon_sat_10pm_12am_leq5min',
        'mon_sat_12am_7am_leq5min',
        // 'mon_sat_10pm_7am_leq5min',

        'sun_ph_7am_7pm_leq5min',
        'sun_ph_7pm_10pm_leq5min',
        'sun_ph_10pm_12am_leq5min',
        'sun_ph_12am_7am_leq5min',
        // 'sun_ph_10pm_7am_leq5min',

        'mon_sat_7am_7pm_leq12hr',
        'mon_sat_7pm_10pm_leq12hr',
        'mon_sat_10pm_12am_leq12hr',
        'mon_sat_12am_7am_leq12hr',
        // 'mon_sat_10pm_7am_leq12hr',

        'sun_ph_7am_7pm_leq12hr',
        'sun_ph_7pm_10pm_leq12hr',
        'sun_ph_10pm_12am_leq12hr',
        'sun_ph_12am_7am_leq12hr',
        // 'sun_ph_10pm_7am_leq12hr',

        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'measurement_point_id' => 'integer',
        'mon_sat_7am_7pm_leq5min' => 'float',
        'mon_sat_7pm_10pm_leq5min' => 'float',
        'mon_sat_10pm_12am_leq5min' => 'float',
        'mon_sat_12am_7am_leq5min' => 'float',
        'sun_ph_7am_7pm_leq5min' => 'float',
        'sun_ph_7pm_10pm_leq5min' => 'float',
        'sun_ph_10pm_12am_leq5min' => 'float',
        'sun_ph_12am_7am_leq5min' => 'float',
        'mon_sat_7am_7pm_leq12hr' => 'float',
        'mon_sat_7pm_10pm_leq12hr' => 'float',
        'mon_sat_10pm_12am_leq12hr' => 'float',
        'mon_sat_12am_7am_leq12hr' => 'float',
        'sun_ph_7am_7pm_leq12hr' => 'float',
        'sun_ph_7pm_10pm_leq12hr' => 'float',
        'sun_ph_10pm_12am_leq12hr' => 'float',
        'sun_ph_12am_7am_leq12hr' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (isset($this->category)) {
            $this->initializeValues();
        }
    }

    private function initializeValues()
    {
        switch ($this->category) {
            case 'Residential':
                $this->mon_sat_7am_7pm_leq5min = 90.0;
                $this->mon_sat_7pm_10pm_leq5min = 70.0;
                $this->mon_sat_10pm_12am_leq5min = 55.0;
                $this->mon_sat_12am_7am_leq5min = 55.0;
                $this->sun_ph_7am_7pm_leq5min = 75.0;
                $this->sun_ph_7pm_10pm_leq5min = 65.0;
                $this->sun_ph_10pm_12am_leq5min = 55.0;
                $this->sun_ph_12am_7am_leq5min = 55.0;
                $this->mon_sat_7am_7pm_leq12hr = 75.0;
                $this->mon_sat_7pm_10pm_leq12hr = 65.0;
                $this->mon_sat_10pm_12am_leq12hr = 55.0;
                $this->mon_sat_12am_7am_leq12hr = 55.0;
                $this->sun_ph_7am_7pm_leq12hr = 75.0;
                $this->sun_ph_7pm_10pm_leq12hr = 140.0;
                $this->sun_ph_10pm_12am_leq12hr = 140.0;
                $this->sun_ph_12am_7am_leq12hr = 140.0;
                break;

            case 'Hospital/Schools':
                $this->mon_sat_7am_7pm_leq5min = 75.0;
                $this->mon_sat_7pm_10pm_leq5min = 55.0;
                $this->mon_sat_10pm_12am_leq5min = 55.0;
                $this->mon_sat_12am_7am_leq5min = 55.0;
                $this->sun_ph_7am_7pm_leq5min = 75.0;
                $this->sun_ph_7pm_10pm_leq5min = 55.0;
                $this->sun_ph_10pm_12am_leq5min = 55.0;
                $this->sun_ph_12am_7am_leq5min = 55.0;
                $this->mon_sat_7am_7pm_leq12hr = 60.0;
                $this->mon_sat_7pm_10pm_leq12hr = 50.0;
                $this->mon_sat_10pm_12am_leq12hr = 50.0;
                $this->mon_sat_12am_7am_leq12hr = 50.0;
                $this->sun_ph_7am_7pm_leq12hr = 60.0;
                $this->sun_ph_7pm_10pm_leq12hr = 50.0;
                $this->sun_ph_10pm_12am_leq12hr = 50.0;
                $this->sun_ph_12am_7am_leq12hr = 50.0;
                break;

            default:
                $this->mon_sat_7am_7pm_leq5min = 90.0;
                $this->mon_sat_7pm_10pm_leq5min = 70.0;
                $this->mon_sat_10pm_12am_leq5min = 70.0;
                $this->mon_sat_12am_7am_leq5min = 70.0;
                $this->sun_ph_7am_7pm_leq5min = 90.0;
                $this->sun_ph_7pm_10pm_leq5min = 70.0;
                $this->sun_ph_10pm_12am_leq5min = 70.0;
                $this->sun_ph_12am_7am_leq5min = 70.0;
                $this->mon_sat_7am_7pm_leq12hr = 75.0;
                $this->mon_sat_7pm_10pm_leq12hr = 65.0;
                $this->mon_sat_10pm_12am_leq12hr = 65.0;
                $this->mon_sat_12am_7am_leq12hr = 65.0;
                $this->sun_ph_7am_7pm_leq12hr = 75.0;
                $this->sun_ph_7pm_10pm_leq12hr = 65.0;
                $this->sun_ph_10pm_12am_leq12hr = 65.0;
                $this->sun_ph_12am_7am_leq12hr = 65.0;
                break;
        }
    }

    public function measurementPoint(): BelongsTo
    {
        return $this->belongsTo(MeasurementPoint::class, 'measurement_point_id', 'id');
    }

    private function sound_limits_values_5min()
    {
        return [
            "mon_sat" => [$this->mon_sat_7am_7pm_leq5min, $this->mon_sat_7pm_10pm_leq5min, $this->mon_sat_10pm_12am_leq5min, $this->mon_sat_12am_7am_leq5min],
            "sun_ph" => [$this->sun_ph_7am_7pm_leq5min, $this->sun_ph_7pm_10pm_leq5min, $this->sun_ph_10pm_12am_leq5min, $this->sun_ph_12am_7am_leq5min],
        ];
    }

    private function sound_limits_values_12hr()
    {
        return [
            "mon_sat" => [$this->mon_sat_7am_7pm_leq12hr, $this->mon_sat_7pm_10pm_leq12hr, $this->mon_sat_10pm_12am_leq12hr, $this->mon_sat_12am_7am_leq12hr],
            "sun_ph" => [$this->sun_ph_7am_7pm_leq12hr, $this->sun_ph_7pm_10pm_leq12hr, $this->sun_ph_10pm_12am_leq12hr, $this->sun_ph_12am_7am_leq12hr],
        ];
    }

    private static $time_mapper = [
        '7am_7pm' => 0,
        '7pm_10pm' => 1,
        '10pm_12am' => 2,
        '12am_7am' => 3,
    ];

    private function time_to_keys($last_data_datetime)
    {
        $day = $last_data_datetime->format('w') == 0 ? 'sun_ph' : 'mon_sat';

        $time_range = $this->getTimeRangeText($last_data_datetime);

        return [$day, $time_range];
    }

    public function getTimeRange($last_data_datetime)
    {
        [$day, $time_range] = $this->time_to_keys($last_data_datetime);
        return [$day, $time_range];
    }

    public function leq5_limit($last_data_datetime_string)
    {
        [$day, $time_range] = $this->getTimeRange($last_data_datetime_string);
        $time_map = self::$time_mapper[$time_range];
        $limit = $this->sound_limits_values_5min()[$day][$time_map];
        return $limit;
    }

    public function leq1h_limit($last_data_datetime)
    {
        [$day, $time_range] = $this->time_to_keys($last_data_datetime);
        $time_map = self::$time_mapper[$time_range];
        if (!($this->is_residential() && $time_map != 0 && $day == 'mon_sat')) {
            return 140.0;
        }
        $limit = $this->sound_limits_values_12hr()[$day][$time_map];
        return $limit;
    }

    public function leq12h_limit($last_data_datetime)
    {
        [$day, $time_range] = $this->time_to_keys($last_data_datetime);
        $time_map = self::$time_mapper[$time_range];
        if ($this->is_residential() && $time_map != 0) {
            return 140.0;
        }
        $limit = $this->sound_limits_values_12hr()[$day][$time_map];
        return $limit;
    }

    private function check_transition_days($last_data_datetime)
    {
        $day_of_week = $last_data_datetime->format('w');
        if ($day_of_week == 0 || $day_of_week == 1) {
            return $day_of_week;
        }
        return null;
    }

    private function set_to_previous_night_1159pm($date)
    {
        // Subtract one day
        $date->modify('-1 day');
        // Set time to 11:59 PM
        $date->setTime(23, 59, 0);
        return $date;
    }

    public function check_12_1_hour_limit_type($last_data_datetime)
    {
        $day_of_week = $this->check_transition_days($last_data_datetime);
        [$day, $time_range] = $this->time_to_keys($last_data_datetime);
        $time_map = self::$time_mapper[$time_range];

        if ($day_of_week != null && $time_map == 3) {
            #morning on sun and mon: take prev night limit
            $last_data_datetime = $this->set_to_previous_night_1159pm($last_data_datetime);
            [$day, $time_range] = $this->time_to_keys($last_data_datetime);
            $time_map = self::$time_mapper[$time_range];
        }

        if ($this->is_residential() && $time_map != 0 && $day == 'mon_sat') {
            return ['1h', $last_data_datetime];
        }
        return ['12h', $last_data_datetime];
    }

    private function getTimeRangeText(DateTime $time)
    {
        $hour = (int) $time->format('G');

        if ($hour >= 7 && $hour <= 18) {
            return '7am_7pm';
        } elseif ($hour >= 19 && $hour <= 21) {
            return '7pm_10pm';
        } elseif ($hour >= 22) {
            return '10pm_12am';
        } else {
            return '12am_7am';
        }
    }

    public function calculate_dose_perc($calculatedLeq, $limit, $num_blanks, $base)
    {
        $dose_calculation_part1 = pow(10, $calculatedLeq / 10);
        $dose_calculation_part2 = ($base - $num_blanks) / ((pow(10, $limit / 10)) * $base);

        return round(min(100, 100 * $dose_calculation_part1 * $dose_calculation_part2), 2);
    }

    public function is_residential()
    {
        return $this->category == "Residential" ? true : false;
    }

    public function is_7am_7pm($time_range)
    {
        return $time_range == '7am_7pm' ? true : false;
    }

    public function is_mon_sat($day)
    {
        return $day == 'mon_sat' ? true : false;
    }

    public function get_max_leq5_limit($date)
    {
        $day_of_week = $date->format('w');
        if ($day_of_week == 0) {
            return max($this->sun_ph_7am_7pm_leq5min, $this->sun_ph_7pm_10pm_leq5min, $this->sun_ph_10pm_12am_leq5min, $this->sun_ph_12am_7am_leq5min);
        }
        return max($this->mon_sat_7am_7pm_leq5min, $this->mon_sat_7pm_10pm_leq5min, $this->mon_sat_10pm_12am_leq5min, $this->mon_sat_12am_7am_leq5min);
    }
}
