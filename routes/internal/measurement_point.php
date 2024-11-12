<?php
use App\Http\Controllers\MeasurementPointController;
use Illuminate\Support\Facades\Route;

Route::get("/measurement_point/{id}", [MeasurementPointController::class, 'show'])->name('measurement_point.show');

Route::post("/measurement_point", [MeasurementPointController::class, 'create'])->name('measurement_point.create');
Route::get("/measurement_points", [MeasurementPointController::class, 'index'])->name('measurement_point_all');
Route::get("/measurement_points/{id}", [MeasurementPointController::class, 'get'])->name('measurement_point');
Route::patch("/measurement_points/{id}", [MeasurementPointController::class, 'update'])->name('measurement_point.update');
Route::delete("/measurement_points/{id}", [MeasurementPointController::class, 'delete'])->name('measurement_point.delete');
