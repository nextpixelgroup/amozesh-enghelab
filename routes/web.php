<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\Web\AboutController;
use App\Http\Controllers\Web\BookController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\CourseController;
use App\Http\Controllers\Web\IndexController;
use App\Http\Controllers\Web\PathController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\TeacherController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

if(env('APP_ENV') === 'production') {
    Route::domain('amozesh.enghelab.ir')->group(function () {
        Route::get('/', function () {
            return view('welcome');
        });
    });
}

Route::name('web.')->group(function () {



    Route::get('/', [IndexController::class, 'index'])->name('index');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');

    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/archives', [BookController::class, 'archives'])->name('books.archives');
    //Route::get('/books/{book:slug}', [BookController::class, 'show'])->name('books.show');
    Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');

    Route::get('/teachers', [TeacherController::class, 'index'])->name('teacher.index');
    Route::get('/t/{teacher:slug}', [TeacherController::class, 'show'])->name('teacher.show');

    Route::get('/path', [PathController::class, 'index'])->name('path.index');

    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');

    Route::get('/about', [AboutController::class, 'index'])->name('about.index');

    Route::get('/cart', [PaymentController::class, 'cart'])->name('payment.cart');
    Route::get('/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/pay', [PaymentController::class, 'pay'])->name('payment.pay');
    Route::get('/thank-you', [PaymentController::class, 'thankYou'])->name('payment.thankYou');


});

require_once 'web/admin.php';
require_once 'web/panel.php';
