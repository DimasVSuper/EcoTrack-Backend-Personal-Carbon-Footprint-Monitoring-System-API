<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ElectricityLogController;
use App\Http\Controllers\TransportLogController;
use App\Http\Controllers\Auth\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/user/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Modul Transportasi
    Route::get('/transport-logs', [TransportLogController::class, 'index']);
    Route::post('/transport-logs', [TransportLogController::class, 'store']);

    // Modul Listrik
    Route::get('/electricity-logs', [ElectricityLogController::class, 'index']);
    Route::post('/electricity-logs', [ElectricityLogController::class, 'store']);
});