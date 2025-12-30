<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\MeasurementPoint;
use App\Services\PdfDataPreparationService;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

            // Load all data directly without caching
            // Extend range by -1 day backward (for 12am-6:55am dose calculations) 
            // and +1 day forward (for next-day morning slots)
            $queryStartDate = $start_date->copy()->subDay();
            $queryEndDate = $end_date->copy()->addDay();
            
            Log::info("Loading data from {$queryStartDate->format('Y-m-d')} to {$queryEndDate->format('Y-m-d')} for date range: {$start_date->format('Y-m-d')} to {$end_date->format('Y-m-d')}");
            
            $dataService = new PdfDataPreparationService($measurementPoint);
            $dataService->loadNoiseData($queryStartDate, $queryEndDate);
            
            // Prepare all days in the requested range
            $preparedData = $dataService->prepareAllDaysData($start_date, $end_date);

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
