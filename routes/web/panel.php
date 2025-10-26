<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/contact', function (){
    return Inertia::render('App/Contact');
});
