<?php

use App\Http\Controllers\SoundLimitController;
use Illuminate\Support\Facades\Route;

Route::post("/soundlimits", [SoundLimitController::class, 'create']);
Route::get("/soundlimits", [SoundLimitController::class, 'index'])->name('sound_limit_all');
Route::get("/soundlimits/{id}", [SoundLimitController::class, 'get'])->name('sound_limit');
Route::patch("/soundlimits/{id}", [SoundLimitController::class, 'update']);
Route::delete("/soundlimits/{id}", [SoundLimitController::class, 'delete']);
