<?php

namespace App\View\Components\Pdfs;

use App\Models\MeasurementPoint;
use Closure;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PartialsReportData extends Component
{
    public MeasurementPoint $measurementPoint;

    public DateTime $date;
    
    public array $preparedData;
    
    /**
     * Create a new component instance.
     */
    public function __construct(
        MeasurementPoint $measurementPoint,
        DateTime $date,
        array $preparedData = []
    ) {
        $this->measurementPoint = $measurementPoint;
        $this->date = $date;
        $this->preparedData = $preparedData;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View | Closure | string
    {
        return view('components.pdfs.partials-report-data');
    }
}
