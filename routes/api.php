<?php

use App\Http\Controllers\API\BookServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/book', [BookServiceController::class, 'index']);
Route::get('/book/{id}', [BookServiceController::class, 'show']);
Route::post('/book', [BookServiceController::class, 'store']);
Route::put('/book/{id}', [BookServiceController::class, 'update']);
Route::delete('/book/{id}', [BookServiceController::class, 'destroy']);
