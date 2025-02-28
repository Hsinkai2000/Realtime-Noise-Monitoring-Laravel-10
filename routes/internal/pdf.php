<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::get("/pdf/id={id}&start_date={start_date}&end_date={end_date}", [PdfController::class, 'generatePdf'])->name('pdf.generatePdf');
Route::get('/chart-image/{date}/{measurementPointID}', [PdfController::class, 'generateChartImage'])->name('chart-image');
