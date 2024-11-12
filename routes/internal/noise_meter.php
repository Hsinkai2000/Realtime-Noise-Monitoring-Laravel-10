<?php

use App\Http\Controllers\NoiseMeterController;
use Illuminate\Support\Facades\Route;

Route::get("/noise_meters", [NoiseMeterController::class, 'index'])->name('noise_meter.all');
Route::post("/noise_meters", [NoiseMeterController::class, 'create'])->name('noise_meter.create');
Route::patch("/noise_meters/{id}", [NoiseMeterController::class, 'update']);
Route::delete("/noise_meters/{id}", [NoiseMeterController::class, 'delete']);
Route::get("/noise_meters/show", [NoiseMeterController::class, 'show'])->name('noise_meter.show');
Route::get("/noise_meters/{id}", [NoiseMeterController::class, 'get'])->name('noise_meter');