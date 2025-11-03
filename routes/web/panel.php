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
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');

});
