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
            
            // Invalidate cache for the date range (to refresh with new extended data format)
            // $cacheDate = $start_date->copy();
            // while ($cacheDate->lte($end_date)) {
            //     $dateKey = $cacheDate->format('Ymd');
            //     Cache::forget("pdf_data_{$measurmentPointId}_{$dateKey}");
            //     $cacheDate->addDay();
            // }
            
            // Fetch contacts once
            $contacts = Contact::where('project_id', $measurementPoint->project->id)->get();

            $now = Carbon::now();

            $preparedData = [];
            $uncachedDates = [];
            $currentDate = $start_date->copy();

            // First pass: check cache and collect uncached dates
            while ($currentDate->lte($end_date)) {
                $dateKey = $currentDate->format('Ymd');
                $dayStart = $currentDate->copy()->setTime(7, 0, 0);
                $dayEnd = $currentDate->copy()->addDay()->setTime(6, 55, 0);
                $isCurrentDay = $now->between($dayStart, $dayEnd);

                if ($isCurrentDay) {
                    // Current day - always fetch fresh (no cache)
                    $uncachedDates[] = $currentDate->copy();
                } else if ($dayEnd < $now){
                    $cacheKey = "pdf_data_{$measurmentPointId}_{$dateKey}";
                    $dayData = Cache::get($cacheKey, null);
                    
                    if ($dayData == null) {
                        // Not in cache - add to query list
                        $uncachedDates[] = $currentDate->copy();
                    } else {
                        // In cache - use it
                        $preparedData = array_merge($preparedData, $dayData);
                    }
                }
                $currentDate->addDay();
            }

            // Handle uncached data - load all at once, prepare all at once
            if (!empty($uncachedDates)) {
                // Load all noise data for the entire range in one query
                // Extend by 2 days to ensure we get data for days that span past midnight
                $queryStartDate = $uncachedDates[0];
                $queryEndDate = $uncachedDates[count($uncachedDates)-1]->copy()->addDays(2);
                
                $dataService = new PdfDataPreparationService($measurementPoint);
                $dataService->loadNoiseData($queryStartDate, $queryEndDate);
                
                // Prepare only the actual days requested (not the extended range)
                // prepareAllDaysData will handle the fact that each "day" extends to next calendar day
                $actualStartDate = $uncachedDates[0];
                $actualEndDate = $uncachedDates[count($uncachedDates)-1];
                $allDaysData = $dataService->prepareAllDaysData($actualStartDate, $actualEndDate);
                
                // Extract and cache each day's data
                foreach ($uncachedDates as $date) {
                    $dateKey = $date->format('Ymd');
                    $dateString = $date->format('Y-m-d');
                    
                    // Extract this day's data from the result
                    $singleDayData = [
                        $dateString => $allDaysData[$dateString] ?? []
                    ];
                    
                    // Cache the data for future use (skip caching for current day)
                    $dayStart = $date->copy()->setTime(7, 0, 0);
                    $dayEnd = $date->copy()->addDay()->setTime(6, 55, 0);
                    $isCurrentDay = $now->between($dayStart, $dayEnd);
                    
                    if (!$isCurrentDay) {
                        $cacheKey = "pdf_data_{$measurmentPointId}_{$dateKey}";
                        Cache::put($cacheKey, $singleDayData, 60*60*8); // cache for 8 hours
                    }
                    
                    $preparedData = array_merge($preparedData, $singleDayData);
                }
            }

            Log::info("PDF data preparation completed in " . round(microtime(true) - $startTime, 2) . " seconds");

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
            
            $pdf->setoptions([
                'enable-local-file-access' => true,
                'margin-bottom' => 8,
                'footer-spacing' => 0,
                'footer-html' => $footerHtml,
                'no-stop-slow-scripts' => true,
                'javascript-delay' => 500,
            ]);

            Log::info("PDF generation completed in " . round(microtime(true) - $startTime, 2) . " seconds");

            return $pdf->inline();
            // return view('pdfs.noise-data-report', $data);
        }
    }
}
