<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForecastController;

Route::get('/locations', [ForecastController::class, 'locations']);
Route::get('/locations/{location}/forecasts', [ForecastController::class, 'forecasts']);