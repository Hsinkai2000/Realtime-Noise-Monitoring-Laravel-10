<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get("/users", [UserController::class, 'index']);
Route::get("/users/{project_id}", [UserController::class, 'get_by_project']);
Route::get("/users/{id}", [UserController::class, 'get']);
Route::patch("/users/{id}", [UserController::class, 'update']);
Route::delete("/users/{id}", [UserController::class, 'delete']);
Route::post("/user", [UserController::class, 'patch_users'])->name('user.create');
Route::get("/user/{username}", [UserController::class, 'existing_user']);