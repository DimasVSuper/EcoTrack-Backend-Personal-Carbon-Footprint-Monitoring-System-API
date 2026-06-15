<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ElectricityLogController;
use App\Http\Controllers\TransportLogController;
use App\Models\TransportType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Lookup data yang dipakai di form input transportasi.
Route::get('/transport-types', function () {
    if (TransportType::query()->count() === 0) {
        $timestamp = now();

        TransportType::query()->insert([
            ['name' => 'Mobil Bensin', 'emission_factor_per_km' => 0.192, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Mobil Listrik', 'emission_factor_per_km' => 0.053, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Motor', 'emission_factor_per_km' => 0.103, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Bus Umum', 'emission_factor_per_km' => 0.105, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Kereta Listrik (KRL)', 'emission_factor_per_km' => 0.041, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ]);
    }

    return response()->json(['data' => TransportType::all()]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/user/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/transport-logs', [TransportLogController::class, 'index']);
    Route::post('/transport-logs', [TransportLogController::class, 'store']);
    Route::put('/transport-logs/{id}', [TransportLogController::class, 'update']);
    Route::patch('/transport-logs/{id}', [TransportLogController::class, 'update']);
    Route::delete('/transport-logs/{id}', [TransportLogController::class, 'destroy']);

    // Modul Listrik
    Route::get('/electricity-logs', [ElectricityLogController::class, 'index']);
    Route::post('/electricity-logs', [ElectricityLogController::class, 'store']);
    Route::put('/electricity-logs/{id}', [ElectricityLogController::class, 'update']);
    Route::patch('/electricity-logs/{id}', [ElectricityLogController::class, 'update']);
    Route::delete('/electricity-logs/{id}', [ElectricityLogController::class, 'destroy']);
});
