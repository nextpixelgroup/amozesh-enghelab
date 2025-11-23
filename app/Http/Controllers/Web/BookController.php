<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebBooksResource;
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
        $query = Book::where('status','publish')->paginate(env('BOOKS_PER_PAGE'));
        $books = WebBooksResource::collection($query);
        return inertia('Web/Books/Archives', compact('books'));
    }
    public function show(Book $book)
    {
        return Inertia::render('Web/Books/Show', compact('book'));
    }
}
