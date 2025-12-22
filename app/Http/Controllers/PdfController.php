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
            $currentDate = $start_date->copy();

            // First pass: check cache and collect uncached dates
            while ($currentDate->lte($end_date)) {
                $dateKey = $currentDate->format('Ymd');
                $dayStart = $currentDate->copy()->setTime(7, 0, 0);
                $dayEnd = $currentDate->copy()->addDay()->setTime(6, 55, 0);
                $isCurrentDay = $now->between($dayStart, $dayEnd);

                if ($isCurrentDay) {
                    $uncachedDates[] = $currentDate->copy();
                } else if ($dayEnd < $now){
                    $cacheKey = "pdf_data_{$measurmentPointId}_{$dateKey}";
                    $dayData = Cache::get($cacheKey, null);
                    
                    if ($dayData == null) {
                        $uncachedDates[] = $currentDate->copy();
                    } else {
                        $preparedData = array_merge($preparedData, $dayData);
                    }
                }
                $currentDate->addDay();
            }

            if (!empty($uncachedDates)) {
                $queryStartDate = $uncachedDates[0]->copy()->subDay();
                $queryEndDate = $uncachedDates[count($uncachedDates)-1]->copy()->addDay();
                
                Log::info("Loading data from {$queryStartDate->format('Y-m-d')} to {$queryEndDate->format('Y-m-d')} for uncached dates: " . implode(', ', array_map(fn($d) => $d->format('Y-m-d'), $uncachedDates)));
                
                $dataService = new PdfDataPreparationService($measurementPoint);
                $dataService->loadNoiseData($queryStartDate, $queryEndDate);
                
                $actualStartDate = $uncachedDates[0];
                $actualEndDate = $uncachedDates[count($uncachedDates)-1];
                $allDaysData = $dataService->prepareAllDaysData($actualStartDate, $actualEndDate);
                
                // Extract and cache each day's data
                foreach ($uncachedDates as $date) {
                    $dateKey = $date->format('Ymd');
                    $dateString = $date->format('Y-m-d');
                    
                    $singleDayData = [
                        $dateString => $allDaysData[$dateString] ?? []
                    ];
                    
                    $dayStart = $date->copy()->setTime(7, 0, 0);
                    $dayEnd = $date->copy()->addDay()->setTime(6, 55, 0);
                    $isCurrentDay = $now->between($dayStart, $dayEnd);
                    
                    if (!$isCurrentDay) {
                        $cacheKey = "pdf_data_{$measurmentPointId}_{$dateKey}";
                        Cache::put($cacheKey, $singleDayData, 60*5); // cache for 5 min
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

            Log::info("PDF generation completed in " . round(microtime(true) - $startTime, 2) . " seconds");

            return $pdf->inline();
            // return view('pdfs.noise-data-report', $data);
        }
    }
}
