<?php

namespace App\View\Components;

use App\Models\MeasurementPoint;
use App\Models\NoiseData;
use Closure;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class ReportIndividualDataComponent extends Component
{
    public MeasurementPoint $measurementPoint;
    public DateTime $slotDate;
    public string $type;
    public ?array $preparedData;

    /**
     * Create a new component instance.
     * 
     * @param MeasurementPoint $measurementPoint
     * @param DateTime $slotDate
     * @param string $type
     * @param array|null $preparedData Pre-computed data to avoid database queries
     */
    public function __construct(
        MeasurementPoint $measurementPoint, 
        DateTime $slotDate, 
        string $type = '',
        ?array $preparedData = null
    ) {
        $this->measurementPoint = $measurementPoint;
        $this->slotDate = $slotDate;
        $this->type = $type;
        $this->preparedData = $preparedData;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View | Closure | string
    {
        // If we have pre-computed data, use it (OPTIMIZED PATH - NO DB QUERIES)
        if ($this->preparedData !== null) {
            return $this->renderFromPreparedData();
        }

        // Fallback to original logic (for backwards compatibility)
        return $this->renderFromDatabase();
    }

    /**
     * Optimized rendering using pre-computed data (NO DATABASE QUERIES)
     */
    private function renderFromPreparedData(): View | Closure | string
    {
        $leq_data = [
            'leq_data' => '-',
            'max' => null,
            'should_alert' => false,
        ];

        $dateKey = $this->slotDate->format('Y-m-d');
        $timeKey = $this->slotDate->format('Y-m-d H:i:s');
        $hour = (int)$this->slotDate->format('H');

        if (!isset($this->preparedData[$dateKey])) {
            return view('components.report-individual-data-component', $leq_data);
        }

        $dayData = $this->preparedData[$dateKey];

        if ($this->type == '1hLeq') {
            if (isset($dayData['hourly'][$hour])) {
                $leq_data = $dayData['hourly'][$hour];
            }
        } else if ($this->type == '12hLeq') {
            $period = $hour >= 7 && $hour < 19 ? 'morning' : 'evening';
            if (isset($dayData['12h'][$period])) {
                $leq_data = $dayData['12h'][$period];
            }
        } else if ($this->type == 'dose') {
            // Use hour index directly (dose is calculated for every hour at XX:55:00)
            if (isset($dayData['dose'][$hour])) {
                $leq_data = $dayData['dose'][$hour];
            }
        } else if ($this->type == 'max') {
            // Use hour index directly (max is calculated for every hour at XX:55:00)
            if (isset($dayData['max'][$hour])) {
                $leq_data = $dayData['max'][$hour];
            }
        } else {
            // 5-minute slot data
            if (isset($dayData['slots'][$timeKey])) {
                $leq_data = $dayData['slots'][$timeKey];
            }
        }

        return view('components.report-individual-data-component', $leq_data);
    }

    /**
     * Original rendering logic using database queries (FALLBACK - SLOWER)
     */
    private function renderFromDatabase(): View | Closure | string
    {
        $leq_data = [
            'leq_data' => '-',
            'max' => null,
            'should_alert' => false,
        ];
        
        $noiseData = $this->measurementPoint->noiseData()->where('received_at', $this->slotDate)->get();

        if ($this->type == '1hLeq') {
            [$one_hr_leq, $num_blanks] = $this->measurementPoint->calc_1_hour_leq($this->slotDate);
            $limit = $this->measurementPoint->soundLimit->leq1h_limit($this->slotDate);
            $one_hr_leq > $limit ? $leq_data['should_alert'] = true : '';
            $leq_data['leq_data'] = number_format(round($one_hr_leq, 1), 1);
        } else if ($this->type == '12hLeq') {
            [$twelve_hr_leq, $num_blanks] = $this->measurementPoint->calc_12_hour_leq($this->slotDate);
            $limit = $this->measurementPoint->soundLimit->leq12h_limit($this->slotDate);
            $twelve_hr_leq > $limit ? $leq_data['should_alert'] = true : '';
            $leq_data['leq_data'] = number_format(round($twelve_hr_leq, 1), 1);
        } else if ($this->type == 'dose') {
            // Fixed: was backwards - should be if (empty($noiseData))
            if (empty($noiseData) || $noiseData->isEmpty()) {
                $noiseData = new NoiseData(['received_at' => $this->slotDate]);
            } else {
                $noiseData = $noiseData[0];
            }
            [$calculated_dose_percentage, $num_blanks, $limit, $decision] = $this->measurementPoint->check_last_data_for_alert($noiseData);

            $leq_data['leq_data'] = number_format($calculated_dose_percentage, 2);

            if ($calculated_dose_percentage >= 70) {
                $leq_data['should_alert'] = true;
            }
        } else if ($this->type == 'max') {
            $datenow = Carbon::now()->addHours(8)->subDays(2);
            $date = new Carbon($this->slotDate);
            
            if ($date->hour == 7) {
                $date->hour = 18;
            }
            
            // Fixed: was backwards
            if (empty($noiseData) || $noiseData->isEmpty()) {
                $noiseData = new NoiseData(['received_at' => $date]);
            } else {
                $noiseData = $noiseData[0];
            }
            
            [$calculated_dose_percentage, $num_blanks, $limit, $decision] = $this->measurementPoint->check_last_data_for_alert($noiseData);
            
            if ($datenow > $date || $num_blanks == 0) {
                $leq_data['leq_data'] = 'FIN';
            } else {
                if ($calculated_dose_percentage < 100 && $num_blanks != 12 && $num_blanks != 144) {
                    $missingVal = $decision == '12h' ? 144 : 12;
                    [$leq_5mins_should_alert, $leq5limit] = $this->measurementPoint->leq_5_mins_exceed_and_alert($noiseData);

                    $sum = round(convert_to_db((1 - ($calculated_dose_percentage / 100)) * ((linearise_leq($limit) * $missingVal) / $num_blanks)), 1);

                    $leq_data['leq_data'] = $decision == '12h' ? min([$sum, $leq5limit]) : $sum;
                } else {
                    $leq_data['leq_data'] = 'N.A.';
                }
            }
        } else {
            if ($noiseData->isNotEmpty()) {
                [$should_alert, $limit] = $this->measurementPoint->leq_5_mins_exceed_and_alert($noiseData[0]);
                $leq_data['leq_data'] = number_format($noiseData[0]->leq, 1);
                $leq_data['should_alert'] = number_format($noiseData[0]->leq, 1) >= $limit ? true : false;
            }
        }

        return view('components.report-individual-data-component', $leq_data);
    }
}
