<?php

namespace App\Console\Commands;

use App\Models\MeasurementPoint;
use App\Services\PdfDataPreparationService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessPdfDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdf:process-day {measurement_point_id} {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process PDF data for a single day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $measurementPointId = $this->argument('measurement_point_id');
        $dateString = $this->argument('date');
        
        try {
            // Load measurement point with relationships
            $measurementPoint = MeasurementPoint::with([
                'noiseMeter',
                'soundLimit',
                'project',
                'concentrator'
            ])->findOrFail($measurementPointId);
            
            $date = Carbon::parse($dateString);
            
            // Create service and load data for this day (extended range)
            $dataService = new PdfDataPreparationService($measurementPoint);
            
            // Load data from previous day to 2 days forward
            $startDate = $date->copy()->subDay();
            $endDate = $date->copy()->addDays(2);
            $dataService->loadNoiseData($startDate, $endDate);
            
            // Process this specific day
            $dayData = $dataService->prepareDayData($date);
            
            // Output as JSON (will be captured by parent process)
            echo json_encode($dayData);
            
            return 0;
            
        } catch (\Exception $e) {
            Log::error("Error processing day {$dateString}: " . $e->getMessage());
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
