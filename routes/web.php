<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\Web\BookController;
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

    Route::get('/', function () {
        return Inertia::render('Web/Index');
    })->name('home');



    Route::get('/t/{teacher:slug}', [TeacherController::class, 'show'])->name('teacher.show');
    Route::get('/books/{book:slug}', [BookController::class, 'show'])->name('books.show');

});

require_once 'web/admin.php';
require_once 'web/panel.php';
