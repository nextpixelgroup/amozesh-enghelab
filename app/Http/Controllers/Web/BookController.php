<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebBooksResource;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{

    public function index()
    {
        return inertia('Web/Books/Index');
    }

    public function archives(Request $request)
    {
        $categories = $this->categories();
        $query = Book::where('status', 'publish')
            ->when($request->filled('category') && $request->category !== 'all', function ($query) use ($request) {
                $query->whereRelation('categories', 'slug', $request->category);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', "%{$request->search}%")
                    ->orWhere('content', 'like', "%{$request->search}%")
                    ->orWhere('summary', 'like', "%{$request->search}%");
            })
            ->when($request->filled('sort'), function ($query) use ($request) {
                $query->orderBy('created_at', $request->sort);
            });
        $books = WebBooksResource::collection($query->orderBy('created_at', 'desc')->paginate(1));
        return inertia('Web/Books/Archives', compact('books', 'categories'));
    }

    public function show(Book $book)
    {
        return Inertia::render('Web/Books/Show', compact('book'));
    }

    private function categories()
    {
        $categories = Category::where('type', 'book')->orderBy('created_at', 'desc')->get()->map(function ($item) {
            return [
                'value' => $item->slug,
                'title' => $item->title
            ];
        })->prepend([
            'value' => 'all',
            'title' => 'همه دسته‌ها',
        ]);
        return $categories;
    }
}
