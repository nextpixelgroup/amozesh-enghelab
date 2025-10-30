<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PathController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::name('admin.')->prefix('admin')->group(function () {

    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');

    Route::middleware(['auth', 'admin'])->group(function () {

        /********* Books *********/
        Route::get('/books', [BookController::class, 'index'])->name('books.index');
        Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::get('/books/edit/{id}', [BookController::class, 'edit'])->name('books.edit');

        /********* Courses *********/
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::get('/courses/edit/{id}', [CourseController::class, 'edit'])->name('courses.edit');

        Route::get('/courses/categories', [CourseCategoryController::class, 'index'])->name('courses.categories.index');
        Route::post('/courses/categories/create', [CourseCategoryController::class, 'store'])->name('courses.categories.store');
        Route::put('/courses/categories/{category}', [CourseCategoryController::class, 'update'])->name('courses.categories.update');
        Route::delete('/courses/categories/{category}', [CourseCategoryController::class, 'destroy'])->name('courses.categories.destroy');

        /********* Paths *********/
        Route::get('/paths', [PathController::class, 'index'])->name('paths.index');
        Route::get('/paths/create', [PathController::class, 'create'])->name('paths.create');
        Route::get('/paths/edit/{id}', [PathController::class, 'edit'])->name('paths.edit');

        /********* Orders *********/
        Route::get('/orders', [UserController::class, 'index'])->name('orders.index');

        /********* Users *********/
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

        /********* Setting *********/
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');

        /********* Auth *********/
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/test', [TestController::class, 'index']);
    });

});

