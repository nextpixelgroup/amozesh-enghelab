<?php

use App\Http\Controllers\Panel\AuthController;
use App\Http\Controllers\Panel\BookmarkController;
use App\Http\Controllers\Panel\CertificateController;
use App\Http\Controllers\Panel\CourseController;
use App\Http\Controllers\Panel\OrderController;
use App\Http\Controllers\Panel\ProfileController;
use App\Http\Controllers\Panel\TicketController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::name('panel.')->prefix('panel')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('index');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login/send', [AuthController::class, 'sendCode'])->name('auth.sendCode');
    Route::post('/login/verify', [AuthController::class, 'verifyCode'])->name('auth.verifyCode');

    Route::middleware(['auth', 'client'])->group(function () {
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/bookmarks/books', [BookmarkController::class, 'books'])->name('bookmarks.books');
        Route::get('/bookmarks/courses', [BookmarkController::class, 'courses'])->name('bookmarks.courses');
        Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
        Route::get('/supports', [TicketController::class, 'index'])->name('supports.index');
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');


        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

});
