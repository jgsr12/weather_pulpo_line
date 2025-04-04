<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'API is working']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/weather', [WeatherController::class, 'getWeather']);
    Route::get('/history', [WeatherController::class, 'history']);
    Route::post('/favorites', [WeatherController::class, 'addFavorite']);
    Route::get('/favorites', [WeatherController::class, 'favorites']);
    Route::get('/admin/searches', [AdminController::class, 'allSearches'])
      ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class.':admin');
});
