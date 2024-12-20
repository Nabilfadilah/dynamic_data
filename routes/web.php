<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/create', [AccountController::class, 'index']);
Route::post('/fetch-states/{id}', [AccountController::class, 'fetchStates']);
Route::post('/fetch-cities/{id}', [AccountController::class, 'fetchCities']);

Route::post('/save', [AccountController::class, 'save']);
Route::get('/list', [AccountController::class, 'list']);

Route::get('/edit/{id}', [AccountController::class, 'edit']);
Route::post('/edit/{id}', [AccountController::class, 'update']);
