<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\Web\AboutController;
use App\Http\Controllers\Web\BookController;
use App\Http\Controllers\Web\CommentController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\CourseController;
use App\Http\Controllers\Web\IndexController;
use App\Http\Controllers\Web\PathController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\TeacherController;
use App\Models\CartItem;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

if(env('APP_ENV') === 'production') {
    Route::domain('amozesh.enghelab.ir')->group(function () {
        Route::get('/', function () {
            return view('welcome');
        });
    });
}

Route::name('web.')->group(function () {

    Route::get('/', [IndexController::class, 'index'])->name('index');
    Route::get('/courses/download/video/{filename}', [CourseController::class, 'download'])->name('courses.download.video');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::post('/courses/enroll/{course}', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::post('/courses/lesson/{lesson}/completed', [CourseController::class, 'LessonCompleted'])->name('courses.lesson.completed');
    Route::post('/courses/lesson/{lesson}/quiz', [CourseController::class, 'LessonQuizStore'])->name('courses.lesson.quiz.store');
    Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{course:slug}/rating', [CourseController::class, 'rating'])->name('courses.rating');

    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/archives', [BookController::class, 'archives'])->name('books.archives');
    Route::get('/books/{book:slug}', [BookController::class, 'show'])->name('books.show');
    Route::post('/books/{book:slug}/rating', [BookController::class, 'rating'])->name('books.rating');

    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teacher/{teacher:slug}', [TeacherController::class, 'show'])->name('teachers.show');

    Route::get('/path', [PathController::class, 'index'])->name('path.index');

    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/contact/store', [ContactController::class, 'store'])->name('contact.store');
    Route::get('/about', [AboutController::class, 'index'])->name('about.index');

    Route::get('/comments/courses/{course:slug}', [CommentController::class, 'courseComments'])->name('comments.course.index');
    Route::post('/comment/courses/{course:slug}/store', [CommentController::class, 'courseStore'])->name('comments.course.store');

    Route::get('/comments/books/{book:slug}', [CommentController::class, 'bookComments'])->name('comments.book.index');
    Route::post('/comments/books/{book:slug}/store', [CommentController::class, 'bookStore'])->name('comments.book.store');

    Route::post('/comments/{comment}', [CommentController::class, 'reply'])->name('comments.reply');

    Route::get('/cart', [PaymentController::class, 'cart'])->name('cart');
    Route::post('/cart/{book:slug}/store', [PaymentController::class, 'store'])->name('cart.store');
    Route::post('/cart', [PaymentController::class, 'cart'])->name('cart');
    Route::put('/cart/{cartItem}/update', [PaymentController::class, 'update'])->name('cart.item.update');
    Route::delete('/cart/{cartItem}/destroy', [PaymentController::class, 'destroy'])->name('cart.item.destroy');
    Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/pay', [PaymentController::class, 'pay'])->name('pay');
    Route::get('/paying', [PaymentController::class, 'paying'])->name('paying');
    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

    Route::get('/video/record', [TestController::class, 'record'])->name('record');
    Route::post('/video/init', [TestController::class, 'init'])->name('video.init');
    Route::post('/video/chunk', [TestController::class, 'uploadChunk'])->name('video.chunk');
    Route::post('/video/finish', [TestController::class, 'finish'])->name('video.finish');





});

require_once 'web/admin.php';
require_once 'web/panel.php';
