<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\IrrigationLogsController;

// Authenticate
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    // Route User
    Route::resource('/user', UserController::class);

    // Route Sector
    Route::resource('/sector', SectorController::class);

    // Route Device
    Route::resource('/device', DeviceController::class);
});