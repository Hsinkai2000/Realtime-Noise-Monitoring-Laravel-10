<?php

namespace App\View\Components;

use App\Models\MeasurementPoint;
use App\Models\NoiseData;
use Closure;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\View\Component;

class ReportIndividualDataComponent extends Component
{
    public MeasurementPoint $measurementPoint;
    // public DateTime $received_at;

    public DateTime $slotDate;

    public string $type;

    /**
     * Create a new component instance.
     */
    public function __construct(MeasurementPoint $measurementPoint, DateTime $slotDate, string $type = '')
    {
        $this->measurementPoint = $measurementPoint;
        $this->slotDate = $slotDate;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View | Closure | string
    {

        $leq_data = [
            'leq_data' => '-',
            'max' => null,
            'should_alert' => false,
        ];
        $noiseData = $this->measurementPoint->noiseData()->where('received_at', $this->slotDate)->get();

        if ($this->type == '1hLeq') {
            [$one_hr_leq, $num_blanks] = $this->measurementPoint->calc_1_hour_leq($this->slotDate);
            $leq_data['leq_data'] = number_format(round($one_hr_leq, 1), 1);
        } else if ($this->type == '12hLeq') {
            [$twelve_hr_leq, $num_blanks] = $this->measurementPoint->calc_12_hour_leq($this->slotDate);

            $leq_data['leq_data'] = number_format(round($twelve_hr_leq, 1), 1);
        } else if ($this->type == 'dose') {
            if (!empty($noiseData)) {
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
            $datenow = Carbon::now('Asia/Singapore');
            $date = new Carbon($this->slotDate);
            if ($date->hour == 7) {
                $date->hour = 18;
            }
            if (!empty($noiseData)) {
                $noiseData = new NoiseData(['received_at' => $date]);
            } else {
                $noiseData = $noiseData[0];
            }
            [$calculated_dose_percentage, $num_blanks, $limit, $decision] = $this->measurementPoint->check_last_data_for_alert($noiseData);
            if ($datenow > $date || $num_blanks == 0) {
                $leq_data['leq_data'] = 'FIN';
            } else {
                if ($calculated_dose_percentage < 1 && $num_blanks != 12 && $num_blanks != 144) {
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
