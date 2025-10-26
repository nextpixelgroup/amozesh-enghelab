<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

if(env('APP_ENV') === 'production') {
    Route::domain('amozesh.enghelab.ir')->group(function () {
        Route::get('/', function () {
            return view('welcome');
        });
    });
}


Route::get('/test', [TestController::class, 'index']);
