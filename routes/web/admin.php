<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PathController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::name('admin.')->prefix('admin')->group(function () {

    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');

    Route::middleware('auth')->group(function () {
        Route::get('/books', [BookController::class, 'index'])->name('books.index');
        Route::get('/books/create', [BookController::class, 'create'])->name('index');
        Route::get('/books/edit/{id}', [BookController::class, 'edit'])->name('index');

        Route::get('/courses', [CourseController::class, 'index'])->name('index');
        Route::get('/courses/create', [BookController::class, 'create'])->name('index');
        Route::get('/courses/edit/{id}', [BookController::class, 'edit'])->name('index');

        Route::get('/paths', [PathController::class, 'index'])->name('index');
        Route::get('/courses/create', [PathController::class, 'create'])->name('index');
        Route::get('/courses/edit/{id}', [PathController::class, 'edit'])->name('index');

        Route::get('/orders', [UserController::class, 'index'])->name('index');


        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');

        Route::get('/test', [TestController::class, 'index']);
    });

});

