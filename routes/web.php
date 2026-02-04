<?php

// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\BookController;
// use App\Http\Controllers\BorrowController;
// use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
// Route::get('/dashboard', [HomeController::class, 'dashboard']);
// Route::get('/login', [AuthController::class, 'index']);

// Route::prefix('/admin')->group(function () {
//     Route::apiResource('borrow', BorrowController::class);
//     Route::apiResource('category', CategoryController::class);
//     Route::apiResource('book', BookController::class);
// });
