<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class BookCategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Books/Categories/List');
    }
}
