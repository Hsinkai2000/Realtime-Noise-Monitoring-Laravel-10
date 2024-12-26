<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\Project;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'guest'], function () {
    // Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::get('/test', function () {
        return Project::first();
    });
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
});

Route::get('/font/{path}', function ($path) {
    return response()->file(public_path('font/' . $path));
})->where('path', '.*');



Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [AuthController::class, 'verify_logged_in'])->name('verify_logged_in');
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');

    require __DIR__ . '/internal/project.php';
    require __DIR__ . '/internal/soundlimit.php';
    require __DIR__ . '/internal/user.php';
    require __DIR__ . '/internal/noise_data.php';
    require __DIR__ . '/internal/contact.php';
    require __DIR__ . '/internal/measurement_point.php';
    require __DIR__ . '/internal/contact.php';
    require __DIR__ . '/internal/concentrator.php';
    require __DIR__ . '/internal/noise_meter.php';
    require __DIR__ . '/internal/pdf.php';

    // Clear session errors
    Route::post('/clear-session-error', function () {
        session()->forget('errors');
        return response()->json(['success' => true]);
    })->name('clear.session.error');
});
