<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\MeasurementPoint;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PdfController extends Controller
{

    public function generatePdf(Request $request)
    {
        $measurmentPointId = $request->route('id');
        $measurementPoint = MeasurementPoint::find($measurmentPointId);
        $user = Auth::user();
        if (Gate::authorize('viewOnlyGuestProject', [$measurementPoint->project, $user])) {
            $start_date = Carbon::createFromFormat('d-m-Y', $request->route('start_date'));
            $end_date = Carbon::createFromFormat('d-m-Y', $request->route('end_date'));
            $contacts = Contact::where('project_id', $measurementPoint->project->id)->get();

            $data = [
                'measurementPoint' => $measurementPoint,
                'contacts' => $contacts,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ];

            $footerHtml = view('pdfs.footer');
            $html = view("pdfs.noise-data-report", $data)->render();
            // $pdf = PDF::loadHTML($html)->setPaper('a4');
            $pdf = PDF::loadView('pdfs.noise-data-report', $data)->setPaper('a4');
            $pdf->setoptions([
                'enable-local-file-access' => true,
                'javascript-delay' => 5000,
                'margin-bottom' => 8,
                'footer-spacing' => 0,
                'footer-html' => $footerHtml
            ]);

            return $pdf->inline();
            // return view('pdfs.noise-data-report', $data);
        }
    }
}
