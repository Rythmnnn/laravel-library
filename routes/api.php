<?php

use App\Http\Controllers\API\AuthServiceController;
use App\Http\Controllers\API\BookServiceController;
use App\Http\Controllers\API\BorrowServiceController;
use App\Http\Controllers\API\CategoryServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/book', [BookServiceController::class, 'index']);
Route::get('/book/{id}', [BookServiceController::class, 'show']);
Route::post('/auth', [AuthServiceController::class, 'auth']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/register', [AuthServiceController::class, 'register']);

    Route::post('/book', [BookServiceController::class, 'store']);
    Route::put('/book/{id}', [BookServiceController::class, 'update']);
    Route::delete('/book/{id}', [BookServiceController::class, 'destroy']);
    Route::apiResource('/category', CategoryServiceController::class);

    Route::get('/borrow', [BorrowServiceController::class, 'index']);
    Route::post('/borrow', [BorrowServiceController::class, 'store']);
    Route::put('/borrow/{id}', [BorrowServiceController::class, 'returnBorrow']);
});
