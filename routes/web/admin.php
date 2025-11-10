<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PathController;
use App\Http\Controllers\Admin\RestrictionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BookCategoryController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::name('admin.')->prefix('admin')->group(function () {

    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');

    /*Route::middleware(['auth', 'admin'])->group(function () {*/

    /********* Books *********/
    Route::get('/', fn() => redirect()->route('admin.courses.index'));
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books/store', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}/update', [BookController::class, 'update'])->name('books.update');

    Route::get('/books/categories', [BookCategoryController::class, 'index'])->name('books.categories.index');
    Route::post('/books/categories/store', [BookCategoryController::class, 'store'])->name('books.categories.store');
    Route::put('/books/categories/{category}/update', [BookCategoryController::class, 'update'])->name('books.categories.update');
    Route::delete('/books/categories/{category}/destroy', [BookCategoryController::class, 'destroy'])->name('books.categories.destroy');

    /********* Courses *********/
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses/store', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::get('/courses/search', [CourseController::class, 'search'])->name('courses.a');
    Route::put('/courses/{path}/update', [CourseController::class, 'update'])->name('courses.update');

    Route::get('/courses/categories', [CourseCategoryController::class, 'index'])->name('courses.categories.index');
    Route::post('/courses/categories/store', [CourseCategoryController::class, 'store'])->name('courses.categories.store');
    Route::put('/courses/categories/{category}/update', [CourseCategoryController::class, 'update'])->name('courses.categories.update');
    Route::delete('/courses/categories/{category}/destroy', [CourseCategoryController::class, 'destroy'])->name('courses.categories.destroy');

    /********* Paths *********/
    Route::get('/paths', [PathController::class, 'index'])->name('paths.index');
    Route::get('/paths/create', [PathController::class, 'create'])->name('paths.create');
    Route::post('/paths/store', [PathController::class, 'store'])->name('paths.store');
    Route::get('/paths/{path}/edit', [PathController::class, 'edit'])->name('paths.edit');
    Route::put('/paths/{path}/update', [PathController::class, 'update'])->name('paths.update');
    Route::delete('/paths/{path}/destroy', [PathController::class, 'destroy'])->name('paths.destroy');

    /********* Orders *********/
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    /********* Users *********/
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}/update', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/restrictions/', [RestrictionController::class, 'store'])->name('users.restrictions.store');
    Route::put('/users/restrictions/{restriction}', [RestrictionController::class, 'update'])->name('users.restrictions.update');


    /********* Users *********/
    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');

    /********* supports *********/
    Route::get('/supports', [TicketController::class, 'index'])->name('tickets.index');
    Route::put('/supports/{ticket}/archive', [TicketController::class, 'archive'])->name('tickets.archive');

    /********* Setting *********/
    Route::get('/settings/general', [SettingController::class, 'general'])->name('settings.index');
    Route::get('/settings/menus', [SettingController::class, 'menus'])->name('settings.menu');

    Route::post('upload/books/image', [UploadController::class, 'bookImage'])->name('upload.books.image');
    Route::post('upload/courses/image', [UploadController::class, 'courseImage'])->name('upload.courses.image');


    /********* Auth *********/
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/test', [TestController::class, 'index']);
    /* });*/

});


