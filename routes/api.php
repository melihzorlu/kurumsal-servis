<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ExternalDataController;
use App\Http\Controllers\Api\RestController;

Route::middleware('auth:api')->get('/external-data', [ExternalDataController::class, 'index']);
Route::get('/soap-data', [\App\Http\Controllers\Api\SoapDataController::class, 'index']);


Route::post('register', [AuthController::class, 'register']);
Route::post('login',    [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me',      [AuthController::class, 'me']);
});

Route::middleware('auth:api')->get('/fetch-rest', [RestController::class, 'fetch']);
