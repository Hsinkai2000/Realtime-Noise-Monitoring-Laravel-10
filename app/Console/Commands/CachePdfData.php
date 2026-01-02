<?php

namespace App\Console\Commands;

use App\Models\MeasurementPoint;
use App\Services\PdfDataPreparationService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CachePdfData extends Command
{
    // # USAGE EXAMPLES
    // # ----------------------------------------------------------------------
    // # Initial setup: Cache the past 7 days (default)
    // php artisan pdf:cache-daily-data
    
    // # Initial setup: Cache the past 30 days
    // php artisan pdf:cache-daily-data --days=30
    
    // # Daily scheduler: Cache yesterday only (when scheduled)
    // php artisan pdf:cache-daily-data --days=1
    
    // # Cache a specific date
    // php artisan pdf:cache-daily-data --date=2025-12-29
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdf:cache-daily-data 
                            {--date= : The date to cache (YYYY-MM-DD format, defaults to yesterday)}
                            {--days=7 : Number of days to cache (for initial setup, defaults to 7)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache PDF data for each measurement point for a specific day (7am -> next day 6:55am)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startTime = microtime(true);
        
        // Determine date range to cache
        $dateOption = $this->option('date');
        $daysOption = $this->option('days');
        
        if ($dateOption) {
            // Cache a specific single date
            $startDate = Carbon::parse($dateOption);
            $endDate = $startDate->copy();
        } else {
            // Cache multiple days backwards from yesterday
            $endDate = Carbon::now()->subDay();
            $startDate = $endDate->copy()->subDays($daysOption - 1);
        }
        
        $dayCount = $startDate->diffInDays($endDate) + 1;
        $this->info("Caching PDF data from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')} ({$dayCount} days)");
        
        // Get all active measurement points
        $measurementPoints = MeasurementPoint::with(['project', 'soundLimit', 'noiseMeter'])->get();
        $this->info("Found {$measurementPoints->count()} measurement points");
        
        $totalCachedCount = 0;
        $totalErrorCount = 0;
        $errorDetails = [];
        
        // Progress bar for better visibility
        $totalOperations = $measurementPoints->count() * $dayCount;
        $bar = $this->output->createProgressBar($totalOperations);
        $bar->start();
        
        foreach ($measurementPoints as $mp) {
            // Process each date for this measurement point
            $currentDate = $startDate->copy();
            
            while ($currentDate->lte($endDate)) {
                try {
                    // Create data service for this measurement point
                    $dataService = new PdfDataPreparationService($mp);
                    
                    // Load data from previous day to next day (for dose calculations)
                    $queryStartDate = $currentDate->copy()->subDay();
                    $queryEndDate = $currentDate->copy()->addDay();
                    
                    $dataService->loadNoiseData($queryStartDate, $queryEndDate);
                    
                    // Prepare data for just this one day
                    $preparedData = $dataService->prepareAllDaysData($currentDate, $currentDate);
                    
                    // Cache key format: pdf_data_{project_id}_{measurement_point_id}_{YYYYMMDD}
                    $cacheKey = "pdf_data_{$mp->project_id}_{$mp->id}_{$currentDate->format('Ymd')}";
                    
                    // Cache for 30 days (this is historical data that won't change)
                    Cache::put($cacheKey, $preparedData, 60 * 24 * 7);
                    
                    $totalCachedCount++;
                    
                } catch (\Exception $e) {
                    $totalErrorCount++;
                    // Store first occurrence of each error type to avoid spam
                    $errorType = get_class($e);
                    $errorDetails[$errorType]['count']++;
                }
                
                $bar->advance();
                $currentDate->addDay();
            }
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $duration = round(microtime(true) - $startTime, 2);
        
        $this->info(str_repeat('=', 50));
        $this->info("Caching completed in {$duration} seconds");
        $this->info("Successfully cached: {$totalCachedCount} data");
        
        
        $this->info(str_repeat('=', 50));
        
        return Command::SUCCESS;
    }
}

