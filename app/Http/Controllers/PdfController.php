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
            
            // Fetch contacts once
            $contacts = Contact::where('project_id', $measurementPoint->project->id)->get();

            // Cache data per day instead of per date range
            // A "day" in this system runs from 7:00 AM to 6:55 AM next day
            $now = Carbon::now();
            $preparedData = [];
            
            // Adjust end_date to include the next day since a "day" spans into the next calendar day
            $adjustedEndDate = $end_date->copy()->addDay();
            
            // Loop through each day in the range
            $currentDate = $start_date->copy();
            while ($currentDate->lte($end_date)) {
                $dateKey = $currentDate->format('Ymd');
                
                // Check if this "day" is still ongoing
                // A day starts at 7 AM and ends at 6:55 AM the next day
                $dayStart = $currentDate->copy()->setTime(7, 0, 0);
                $dayEnd = $currentDate->copy()->addDay()->setTime(6, 55, 0);
                $isCurrentDay = $now->between($dayStart, $dayEnd);
                
                // For data preparation, we need to include the next calendar day
                // because the "day" extends from 7 AM to 6:55 AM next day
                $dataEndDate = $currentDate->copy()->addDay();
                
                if ($isCurrentDay) {
                    // Don't use cache for current day - always fetch fresh data
                    $dataService = new PdfDataPreparationService($measurementPoint);
                    $dataService->loadNoiseData($currentDate, $dataEndDate);
                    $dayData = $dataService->prepareAllDaysData($currentDate, $dataEndDate);
                    $preparedData = array_merge($preparedData, $dayData);
                } else {
                    // Use cache for past days (cache for 24 hours)
                    $cacheKey = "pdf_data_{$measurmentPointId}_{$dateKey}";
                    $dayData = Cache::remember($cacheKey, 86400, function () use ($measurementPoint, $currentDate, $dataEndDate) {
                        $dataService = new PdfDataPreparationService($measurementPoint);
                        $dataService->loadNoiseData($currentDate, $dataEndDate);
                        return $dataService->prepareAllDaysData($currentDate, $dataEndDate);
                    });
                    $preparedData = array_merge($preparedData, $dayData);
                }
                
                $currentDate->addDay();
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
            $pdf->setoptions([
                'enable-local-file-access' => true,
                'margin-bottom' => 8,
                'debug-javascript' => true,
                'footer-spacing' => 0,
                'footer-html' => $footerHtml
            ]);

            Log::info("PDF generation completed in " . round(microtime(true) - $startTime, 2) . " seconds");

            return $pdf->inline();
            // return view('pdfs.noise-data-report', $data);
        }
    }
}
