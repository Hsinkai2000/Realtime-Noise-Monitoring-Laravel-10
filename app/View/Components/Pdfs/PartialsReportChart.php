<?php

namespace App\View\Components\Pdfs;

use App\Models\MeasurementPoint;
use App\Models\NoiseData;
use Closure;
use Illuminate\Contracts\View\View;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class PartialsReportChart extends Component
{
    public DateTime $date;
    public Collection $noiseData;
    public MeasurementPoint $measurementPoint;
    public Collection $limitData;
    public DateTime $start;
    public DateTime $end;

    public function __construct(
        DateTime $date,
        MeasurementPoint $measurementPoint
    ) {
        //
        $this->date = $date;
        $this->measurementPoint = $measurementPoint;


        $this->start = $this->date->setTime(7, 0, 0);
        $this->end = (clone $this->date)->modify('+1 day')->setTime(6, 59, 59);

        $this->noiseData = collect($this->getNoiseData());
        $this->limitData = collect($this->getLimitData());
    }

    private function getLimitData()
    {
        $currLimitData = [];

        for ($time = clone $this->start; $time <= $this->end; $time->modify('+5 minutes')) {
            $dayOfWeek = (int)$time->format('w');
            $isWeekend = ($dayOfWeek === 0);
            $hours = (int)$time->format('H');
            $yValue = 0;

            if (!$isWeekend) {
                if ($hours >= 7 && $hours < 19) {
                    $yValue = $this->measurementPoint->soundLimit->mon_sat_7am_7pm_leq5min;
                } elseif ($hours >= 19 && $hours < 22) {
                    $yValue = $this->measurementPoint->soundLimit->mon_sat_7pm_10pm_leq5min;
                } elseif ($hours >= 22 && $hours < 24) {
                    $yValue = $this->measurementPoint->soundLimit->mon_sat_10pm_12am_leq5min;
                } else {
                    $yValue = $this->measurementPoint->soundLimit->mon_sat_12am_7am_leq5min;
                }
            } else {
                if ($hours >= 7 && $hours < 19) {
                    $yValue = $this->measurementPoint->soundLimit->sun_ph_7am_7pm_leq5min;
                } elseif ($hours >= 19 && $hours < 22) {
                    $yValue = $this->measurementPoint->soundLimit->sun_ph_7pm_10pm_leq5min;
                } elseif ($hours >= 22 && $hours < 24) {
                    $yValue = $this->measurementPoint->soundLimit->sun_ph_10pm_12am_leq5min;
                } else {
                    $yValue = $this->measurementPoint->soundLimit->sun_ph_12am_7am_leq5min;
                }
            }

            $currLimitData[] = [
                'x' => $time->format('Y-m-d\TH:i:s'),
                'y' => $yValue
            ];
        }


        return $currLimitData;
    }

    private function getNoiseData()
    {
        $currNoiseData = [];

        for ($time = clone $this->start; $time <= $this->end; $time->modify('+5 minutes')) {
            $noise_data = $this->measurementPoint->noiseData()
                ->where('received_at', $time->format('Y-m-d H:i:s'))
                ->first()?->leq;
            $currNoiseData[] = [
                'x' => $time->format('Y-m-d\TH:i:s'),
                'y' => $noise_data ? $noise_data : null
            ];
        }

        return $currNoiseData;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.pdfs.partials-report-chart');
    }
}
