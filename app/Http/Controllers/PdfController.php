<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\MeasurementPoint;
use App\Services\PdfDataPreparationService;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;

class PdfController extends Controller
{

    public function generatePdf(Request $request)
    {
        $startTime = microtime(true);
        
        $measurmentPointId = $request->route('id');

        // Eager load all relationships to prevent N+1 queries
        $measurementPoint = MeasurementPoint::with([
            'noiseMeter',
            'soundLimit',
            'project',
            'concentrator'
        ])->findOrFail($measurmentPointId);
        
        $user = Auth::user();
        
        if (Gate::authorize('viewOnlyGuestProject', [$measurementPoint->project, $user])) {
            $start_date = Carbon::createFromFormat('d-m-Y', $request->route('start_date'));
            $end_date = Carbon::createFromFormat('d-m-Y', $request->route('end_date'));
            
            $contacts = Contact::where('project_id', $measurementPoint->project->id)->get();

            $now = Carbon::now();
            $preparedData = [];
            $uncachedDates = [];
            $cachedDates = [];
            
            Log::info("=== PDF Generation Started ===");
            Log::info("Measurement Point: {$measurementPoint->point_name} (ID: {$measurmentPointId})");
            Log::info("Date Range: {$start_date->format('Y-m-d')} to {$end_date->format('Y-m-d')}");
            
            // First pass: check cache for each day
            $currentDate = $start_date->copy();
            while ($currentDate->lte($end_date)) {
                $dateKey = $currentDate->format('Ymd');
                $dayStart = $currentDate->copy()->setTime(7, 0, 0);
                $dayEnd = $currentDate->copy()->addDay()->setTime(6, 55, 0);
                $isCurrentDay = $now->between($dayStart, $dayEnd);
                
                // Only use cache for completed days (not the current day)
                if (!$isCurrentDay && $dayEnd < $now) {
                    $cacheKey = "pdf_data_{$measurementPoint->project_id}_{$measurmentPointId}_{$dateKey}";
                    $dayData = Cache::get($cacheKey);
                    
                    if ($dayData) {
                        // Found cached data
Log::info("cache hit for ". $cacheKey);
                        $preparedData = array_merge($preparedData, $dayData);
                    } else {
                        // Not in cache, need to fetch
Log::info("cache miss for ". $cacheKey);           
             $uncachedDates[] = $currentDate->copy();
                    }
                } else {
                    // Current day or future - always fetch fresh
                    $uncachedDates[] = $currentDate->copy();
                }
                
                $currentDate->addDay();
            }
            
            // Second pass: fetch uncached dates in consecutive ranges
            if (!empty($uncachedDates)) {
                // Group consecutive dates into ranges to minimize queries
                sort($uncachedDates);
                $ranges = [];
                $rangeStart = $uncachedDates[0];
                $rangeEnd = $uncachedDates[0];
                
                for ($i = 1; $i < count($uncachedDates); $i++) {
                    $current = $uncachedDates[$i];
                    $previous = $uncachedDates[$i - 1];
                    
                    // If consecutive days, extend the range
                    if ($current->diffInDays($previous) === 1) {
                        $rangeEnd = $current;
                    } else {
                        // Gap found, save current range and start a new one
                        $ranges[] = ['start' => $rangeStart->copy(), 'end' => $rangeEnd->copy()];
                        $rangeStart = $current;
                        $rangeEnd = $current;
                    }
                }
                // Don't forget the last range
                $ranges[] = ['start' => $rangeStart->copy(), 'end' => $rangeEnd->copy()];
                
                Log::info("Querying " . count($ranges) . " date range(s) for uncached data");
                
                // Query each range separately
                $dataService = new PdfDataPreparationService($measurementPoint);
                
                foreach ($ranges as $range) {
                    Log::info("  Range: {$range['start']->format('Y-m-d')} to {$range['end']->format('Y-m-d')}");
                    
                    // loadNoiseData will automatically extend the range for dose calculations
                    $dataService->loadNoiseData($range['start'], $range['end']);
                    $freshData = $dataService->prepareAllDaysData($range['start'], $range['end']);
                    
                    $preparedData = array_merge($preparedData, $freshData);
                }
            }

            Log::info("PDF data preparation completed in " . round(microtime(true) - $startTime, 2) . " seconds");
            Log::info("=== Starting PDF Rendering ===");

            $data = [
                'measurementPoint' => $measurementPoint,
                'contacts' => $contacts,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'preparedData' => $preparedData, // Pre-computed data
            ];

            $footerHtml = view('pdfs.footer');

            $pdf = PDF::loadView('pdfs.noise-data-report', $data)->setPaper('a4');

            Log::info("PDF rendered in " . round(microtime(true) - $startTime, 2) . " seconds");
            Log::info("=== PDF Generation Completed Successfully ===");
            
            $pdf->setoptions([
                'enable-local-file-access' => true,
                'margin-bottom' => 8,
                'footer-spacing' => 0,
                'footer-html' => $footerHtml,
                
                // JavaScript (minimal delay for patched Qt)
                'enable-javascript' => true,
                'javascript-delay' => 300,       
                'no-stop-slow-scripts' => true,
                
                // Quality/Speed trade-off
                'lowquality' => false,           
                'dpi' => 96,                    
                
                // Rendering optimizations
                'disable-smart-shrinking' => true,
                'print-media-type' => true,
                'no-background' => false,
                
                // Speed improvements
                'no-outline' => true,            
                'disable-external-links' => true,
                'disable-internal-links' => true, 
                'quiet' => true,                 
                'disable-forms' => true,     
            ]);

            $generationTime = round(microtime(true) - $startTime, 2);
            Log::info("PDF generation completed in {$generationTime} seconds");

            return $pdf->inline();
            // return view('pdfs.noise-data-report', $data);
        }
    }
}
