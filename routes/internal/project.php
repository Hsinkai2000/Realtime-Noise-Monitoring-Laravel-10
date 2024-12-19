<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get("/project/admin", [ProjectController::class, 'show_admin'])->name('project.admin');
Route::get("/project/{id}", [ProjectController::class, 'show_project'])->name('project.show');
Route::post("/project", [ProjectController::class, 'create'])->name('project.create');
Route::post("/projects", [ProjectController::class, 'index'])->name('project_all');
Route::patch("/project/{id}", [ProjectController::class, 'update'])->name('project.update');
Route::delete("/project/{id}", [ProjectController::class, 'delete'])->name('project.delete');