<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{

    public function index()
    {
        return inertia('Web/Books/Index');
    }

    public function archives()
    {
        return inertia('Web/Books/Archives');
    }
    public function show(Book $book)
    {
        return Inertia::render('Web/Books/Show', compact('book'));
    }
}
