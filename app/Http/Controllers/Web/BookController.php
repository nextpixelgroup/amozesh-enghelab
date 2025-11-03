<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{
    public function show(Book $book)
    {
        return Inertia::render('Web/Books/Show', compact('book'));
    }
}
