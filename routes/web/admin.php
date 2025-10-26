<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PathController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::name('admin.')->prefix('admin')->group(function () {

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

    Route::get('/books', [BookController::class, 'index'])->name('index');
    Route::get('/books/create', [BookController::class, 'create'])->name('index');
    Route::get('/books/edit/{id}', [BookController::class, 'edit'])->name('index');

    Route::get('/courses', [CourseController::class, 'index'])->name('index');
    Route::get('/courses/create', [BookController::class, 'create'])->name('index');
    Route::get('/courses/edit/{id}', [BookController::class, 'edit'])->name('index');

    Route::get('/paths', [PathController::class, 'index'])->name('index');
    Route::get('/courses/create', [PathController::class, 'create'])->name('index');
    Route::get('/courses/edit/{id}', [PathController::class, 'edit'])->name('index');

    Route::get('/orders', [OrderController::class, 'index'])->name('index');

    Route::get('/users', function () {
        return Inertia::render('Admin/Users/List');
    });

    Route::get('/test', [TestController::class, 'index']);

});
