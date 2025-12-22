<?php

use App\Http\Controllers\ConcentratorController;
use Illuminate\Support\Facades\Route;

Route::post("/concentrators", [ConcentratorController::class, 'create']);
Route::get("/concentrators/show", [ConcentratorController::class, "show"])->name('concentrator.show');
Route::get("/concentrators", [ConcentratorController::class, 'index'])->name('concentrator.all');
Route::get("/concentrators/{id}", [ConcentratorController::class, 'get'])->name('concentrator');
Route::patch("/concentrators/{id}", [ConcentratorController::class, 'update']);
Route::delete("/concentrators/{id}", [ConcentratorController::class, 'delete']);