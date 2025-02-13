<?php

namespace App\View\Components\Pdfs;

use App\Models\MeasurementPoint;
use App\Models\NoiseData;
use Closure;
use Illuminate\Contracts\View\View;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class PartialsReportChart extends Component
{
    public DateTime $date;
    public Collection|NoiseData $noiseData;
    public MeasurementPoint $measurementPoint;

    public function __construct(
        DateTime $date,
        MeasurementPoint $measurementPoint
    ) {
        //
        $this->date = $date;
        $this->measurementPoint = $measurementPoint;

        $this->noiseData = $this->getNoiseData();
    }

    private function getNoiseData()
    {
        $start = $this->date->setTime(7, 0, 0);
        $end = (clone $this->date)->modify('+1 day')->setTime(6, 59, 59);
        return $this->measurementPoint->noiseData()->whereBetween('received_at', [$start, $end])->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.pdfs.partials-report-chart');
    }
}
