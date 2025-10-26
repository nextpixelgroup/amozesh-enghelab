<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

if(env('APP_ENV') === 'production') {
    Route::domain('amozesh.enghelab.ir')->group(function () {
        Route::get('/', function () {
            return view('welcome');
        });
    });
}

Route::get('/', function (){
    return Inertia::render('App/Index', [
        'name' => 'Hossein'
    ]);
});

Route::get('/contact', function (){
    return Inertia::render('App/Contact');
});

Route::get('/admin/login', function (){
    return Inertia::render('Admin/Auth/Login');
});

Route::get('/admin/courses', function (){
    return Inertia::render('Admin/Courses/List');
});

Route::get('/admin/users', function (){
    return Inertia::render('Admin/Users/List');
});


Route::get('/test', [TestController::class, 'index']);
