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
    /**
     * Create a new component instance.
     */
    public function __construct(
        MeasurementPoint $measurementPoint,
        DateTime $date
    ) {
        $this->measurementPoint = $measurementPoint;

        $this->date = $date;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View | Closure | string
    {
        debug_log('running');
        return view('components.pdfs.partials-report-data');
    }
}