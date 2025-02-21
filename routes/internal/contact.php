<?php

use App\Http\Controllers\ContactsController;
use Illuminate\Support\Facades\Route;

Route::post("/contacts", [ContactsController::class, 'create']);
Route::get("/contacts", [ContactsController::class, 'index'])->name('contract_all');
Route::get("/contacts/{id}", [ContactsController::class, 'get'])->name('contract');
Route::patch("/contacts/{id}", [ContactsController::class, 'update']);
Route::delete("/contacts/{id}", [ContactsController::class, 'delete']);
