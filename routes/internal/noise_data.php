<?php
use App\Http\Controllers\NoiseDataController;
use Illuminate\Support\Facades\Route;

Route::post("/noisedatas", [NoiseDataController::class, 'create']);
Route::get("/noisedatas", [NoiseDataController::class, 'index'])->name('noise_data_all');
Route::get("/noisedatas/{id}", [NoiseDataController::class, 'get'])->name('noise_data');
Route::patch("/noisedatas/{id}", [NoiseDataController::class, 'update']);
Route::delete("/noisedatas/{id}", [NoiseDataController::class, 'delete']);