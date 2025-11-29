<?php

use App\Http\Controllers\Panel\AuthController;
use App\Http\Controllers\Panel\CourseController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/contact', function (){
    return Inertia::render('Web/Contact');
});

Route::name('panel.')->prefix('panel')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('index');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login/send', [AuthController::class, 'sendCode'])->name('auth.sendCode');
    Route::post('/login/verify', [AuthController::class, 'verifyCode'])->name('auth.verifyCode');

    Route::middleware(['auth', 'client'])->group(function () {
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/orders', [CourseController::class, 'index'])->name('orders.index');


        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

});
