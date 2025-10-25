<?php

use App\Http\Controllers\Api\Admin\V1\AuthController;
use App\Http\Controllers\Api\Admin\V1\CourseController;
use Illuminate\Support\Facades\Route;

// Public admin routes (no auth required)
Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

    Route::middleware(['auth:sanctum','admin'])->group(function () {
        Route::get('/courses', [CourseController::class, 'index']);
        Route::post('/course/create', [CourseController::class, 'create']);
        Route::get('/me', [AuthController::class, 'me']);

    });

});
