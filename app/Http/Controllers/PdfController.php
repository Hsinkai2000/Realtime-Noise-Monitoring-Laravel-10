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

            Log::info("=== PDF Generation Started ===");
            Log::info("Measurement Point: {$measurementPoint->point_name} (ID: {$measurmentPointId})");
            Log::info("Date Range: {$start_date->format('Y-m-d')} to {$end_date->format('Y-m-d')}");
            
            // Fetch all data from database in one query
            $dataService = new PdfDataPreparationService($measurementPoint);
            $dataService->loadNoiseData($start_date, $end_date);
            $preparedData = $dataService->prepareAllDaysData($start_date, $end_date);

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
