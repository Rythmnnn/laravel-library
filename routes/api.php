<?php

use App\Http\Controllers\API\AuthServiceController;
use App\Http\Controllers\API\BookServiceController;
use App\Http\Controllers\API\CategoryServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/book', [BookServiceController::class, 'index']);
Route::get('/book/{id}', [BookServiceController::class, 'show']);
Route::post('/auth', [AuthServiceController::class, 'auth']);
Route::post('/register', [AuthServiceController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/book', [BookServiceController::class, 'store']);
    Route::put('/book/{id}', [BookServiceController::class, 'update']);
    Route::delete('/book/{id}', [BookServiceController::class, 'destroy']);
    Route::apiResource('/category', CategoryServiceController::class);

});
