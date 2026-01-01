<?php

namespace App\Console\Commands;

use App\Models\MeasurementPoint;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearPdfCache extends Command
{

    // # USAGE EXAMPLES
    // # ----------------------------------------------------------------------
    // # Clear ALL PDF cache (all projects, all MPs, all dates)
    // php artisan pdf:clear-cache --all

    // # Clear cache for a specific project (all dates)
    // php artisan pdf:clear-cache --project=1

    // # Clear cache for a specific measurement point (all dates)
    // php artisan pdf:clear-cache --measurement-point=5

    // # Clear cache for a specific date (all projects/MPs)
    // php artisan pdf:clear-cache --date=2025-12-29

    // # Clear cache for a date range (all projects/MPs)
    // php artisan pdf:clear-cache --date-range=2025-12-01,2025-12-31

    // # Clear cache for specific project + specific date
    // php artisan pdf:clear-cache --project=1 --date=2025-12-29

    // # Clear cache for specific project + date range
    // php artisan pdf:clear-cache --project=1 --date-range=2025-12-01,2025-12-31

    // # Clear cache for specific measurement point + specific date
    // php artisan pdf:clear-cache --measurement-point=5 --date=2025-12-29

    // # Clear cache for specific measurement point + date range
    // php artisan pdf:clear-cache --measurement-point=5 --date-range=2025-12-01,2025-12-31


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdf:clear-cache 
                            {--all : Clear all PDF cache}
                            {--project= : Clear cache for specific project ID}
                            {--measurement-point= : Clear cache for specific measurement point ID}
                            {--date= : Clear cache for specific date (YYYY-MM-DD)}
                            {--date-range= : Clear cache for date range (YYYY-MM-DD,YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear PDF cache with various filter options';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $clearedCount = 0;
        
        if ($this->option('all')) {
            // Clear all PDF cache
            $clearedCount = $this->clearAllCache();
            $this->info("✓ Cleared all PDF cache ({$clearedCount} keys)");
            
        } elseif ($this->option('measurement-point')) {
            // Clear cache for specific measurement point
            $mpId = $this->option('measurement-point');
            $mp = MeasurementPoint::find($mpId);
            
            if (!$mp) {
                $this->error("Measurement point with ID {$mpId} not found");
                return Command::FAILURE;
            }
            
            if ($this->option('date')) {
                $date = Carbon::parse($this->option('date'));
                $clearedCount = $this->clearMeasurementPointDate($mp, $date);
                $this->info("✓ Cleared cache for MP {$mp->point_name} on {$date->format('Y-m-d')} ({$clearedCount} keys)");
                
            } elseif ($this->option('date-range')) {
                [$start, $end] = explode(',', $this->option('date-range'));
                $startDate = Carbon::parse($start);
                $endDate = Carbon::parse($end);
                $clearedCount = $this->clearMeasurementPointDateRange($mp, $startDate, $endDate);
                $this->info("✓ Cleared cache for MP {$mp->point_name} from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')} ({$clearedCount} keys)");
                
            } else {
                $clearedCount = $this->clearMeasurementPoint($mp);
                $this->info("✓ Cleared all cache for MP {$mp->point_name} ({$clearedCount} keys)");
            }
            
        } elseif ($this->option('project')) {
            // Clear cache for specific project
            $projectId = $this->option('project');
            
            if ($this->option('date')) {
                $date = Carbon::parse($this->option('date'));
                $clearedCount = $this->clearProjectDate($projectId, $date);
                $this->info("✓ Cleared cache for project {$projectId} on {$date->format('Y-m-d')} ({$clearedCount} keys)");
                
            } elseif ($this->option('date-range')) {
                [$start, $end] = explode(',', $this->option('date-range'));
                $startDate = Carbon::parse($start);
                $endDate = Carbon::parse($end);
                $clearedCount = $this->clearProjectDateRange($projectId, $startDate, $endDate);
                $this->info("✓ Cleared cache for project {$projectId} from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')} ({$clearedCount} keys)");
                
            } else {
                $clearedCount = $this->clearProject($projectId);
                $this->info("✓ Cleared all cache for project {$projectId} ({$clearedCount} keys)");
            }
            
        } elseif ($this->option('date')) {
            // Clear cache for specific date across all projects/MPs
            $date = Carbon::parse($this->option('date'));
            $clearedCount = $this->clearDate($date);
            $this->info("✓ Cleared cache for date {$date->format('Y-m-d')} across all MPs ({$clearedCount} keys)");
            
        } elseif ($this->option('date-range')) {
            // Clear cache for date range across all projects/MPs
            [$start, $end] = explode(',', $this->option('date-range'));
            $startDate = Carbon::parse($start);
            $endDate = Carbon::parse($end);
            $clearedCount = $this->clearDateRange($startDate, $endDate);
            $this->info("✓ Cleared cache from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')} across all MPs ({$clearedCount} keys)");
            
        } else {
            $this->error('Please specify at least one option: --all, --project, --measurement-point, --date, or --date-range');
            $this->info('Examples:');
            $this->info('  php artisan pdf:clear-cache --all');
            $this->info('  php artisan pdf:clear-cache --project=1');
            $this->info('  php artisan pdf:clear-cache --measurement-point=5');
            $this->info('  php artisan pdf:clear-cache --date=2025-12-29');
            $this->info('  php artisan pdf:clear-cache --project=1 --date=2025-12-29');
            $this->info('  php artisan pdf:clear-cache --measurement-point=5 --date-range=2025-12-01,2025-12-31');
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
    
    /**
     * Clear all PDF cache
     */
    private function clearAllCache(): int
    {
        $count = 0;
        $mps = MeasurementPoint::all();
        
        foreach ($mps as $mp) {
            $count += $this->clearMeasurementPoint($mp);
        }
        
        return $count;
    }
    
    /**
     * Clear cache for a specific measurement point (all dates)
     */
    private function clearMeasurementPoint(MeasurementPoint $mp): int
    {
        $count = 0;
        
        // Clear last 365 days worth of cache
        $date = Carbon::now()->subDays(365);
        $endDate = Carbon::now();
        
        while ($date->lte($endDate)) {
            $cacheKey = "pdf_data_{$mp->project_id}_{$mp->id}_{$date->format('Ymd')}";
            if (Cache::has($cacheKey)) {
                Cache::forget($cacheKey);
                $count++;
            }
            $date->addDay();
        }
        
        return $count;
    }
    
    /**
     * Clear cache for a specific project (all MPs, all dates)
     */
    private function clearProject(int $projectId): int
    {
        $count = 0;
        $mps = MeasurementPoint::where('project_id', $projectId)->get();
        
        foreach ($mps as $mp) {
            $count += $this->clearMeasurementPoint($mp);
        }
        
        return $count;
    }
    
    /**
     * Clear cache for a specific date (all projects/MPs)
     */
    private function clearDate(Carbon $date): int
    {
        $count = 0;
        $mps = MeasurementPoint::all();
        
        foreach ($mps as $mp) {
            $cacheKey = "pdf_data_{$mp->project_id}_{$mp->id}_{$date->format('Ymd')}";
            if (Cache::has($cacheKey)) {
                Cache::forget($cacheKey);
                $count++;
            }
        }
        
        return $count;
    }
    
    /**
     * Clear cache for a date range (all projects/MPs)
     */
    private function clearDateRange(Carbon $startDate, Carbon $endDate): int
    {
        $count = 0;
        $mps = MeasurementPoint::all();
        
        foreach ($mps as $mp) {
            $count += $this->clearMeasurementPointDateRange($mp, $startDate, $endDate);
        }
        
        return $count;
    }
    
    /**
     * Clear cache for a specific measurement point and date
     */
    private function clearMeasurementPointDate(MeasurementPoint $mp, Carbon $date): int
    {
        $cacheKey = "pdf_data_{$mp->project_id}_{$mp->id}_{$date->format('Ymd')}";
        if (Cache::has($cacheKey)) {
            Cache::forget($cacheKey);
            return 1;
        }
        return 0;
    }
    
    /**
     * Clear cache for a specific measurement point and date range
     */
    private function clearMeasurementPointDateRange(MeasurementPoint $mp, Carbon $startDate, Carbon $endDate): int
    {
        $count = 0;
        $date = $startDate->copy();
        
        while ($date->lte($endDate)) {
            $cacheKey = "pdf_data_{$mp->project_id}_{$mp->id}_{$date->format('Ymd')}";
            if (Cache::has($cacheKey)) {
                Cache::forget($cacheKey);
                $count++;
            }
            $date->addDay();
        }
        
        return $count;
    }
    
    /**
     * Clear cache for a specific project and date
     */
    private function clearProjectDate(int $projectId, Carbon $date): int
    {
        $count = 0;
        $mps = MeasurementPoint::where('project_id', $projectId)->get();
        
        foreach ($mps as $mp) {
            $count += $this->clearMeasurementPointDate($mp, $date);
        }
        
        return $count;
    }
    
    /**
     * Clear cache for a specific project and date range
     */
    private function clearProjectDateRange(int $projectId, Carbon $startDate, Carbon $endDate): int
    {
        $count = 0;
        $mps = MeasurementPoint::where('project_id', $projectId)->get();
        
        foreach ($mps as $mp) {
            $count += $this->clearMeasurementPointDateRange($mp, $startDate, $endDate);
        }
        
        return $count;
    }
}

