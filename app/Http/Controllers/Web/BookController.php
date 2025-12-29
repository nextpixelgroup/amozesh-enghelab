<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebBookDetailsResource;
use App\Http\Resources\WebBooksResource;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{

    public function index()
    {
        $query = Book::where('status', 'publish')->whereRelation('categories', 'slug', 'special')->orderBy('created_at', 'desc')->paginate(15);
        $section1 = WebBooksResource::collection($query);
        $query = Book::where('status', 'publish')->whereRelation('categories', 'slug', 'popular')->orderBy('created_at', 'desc')->paginate(15);
        $section2 = WebBooksResource::collection($query);
        $section3 = WebBooksResource::collection($query)->response()->getData(true);
        $section3['moreUrl'] = route('web.books.archives', ['category' => 'popular']);
        $section4 = WebBooksResource::collection($query);
        $section5 = WebBooksResource::collection($query)->response()->getData(true);
        $section5['moreUrl'] = route('web.books.archives', ['category' => 'معارف-اسلامی']);
        $pageTitle = 'فروشگاه کتاب';
        return inertia('Web/Books/Index', compact('section1', 'section2', 'section3', 'section4', 'section5', 'pageTitle'));
    }

    public function archives(Request $request)
    {
        $categories = $this->categories();
        $query = Book::where('status', 'publish')
            ->when($request->filled('category') && $request->category !== 'all', function ($query) use ($request) {
                $query->whereRelation('categories', 'slug', $request->category);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('title', 'like', "%{$request->search}%")
                        ->orWhere('content', 'like', "%{$request->search}%")
                        ->orWhere('summary', 'like', "%{$request->search}%");
                });
            })
            ->when($request->filled('sort'), function ($query) use ($request) {
                $query->orderBy('created_at', $request->sort);
            });
        $books = WebBooksResource::collection($query->orderBy('created_at', 'desc')->paginate(env('BOOKS_PER_PAGE')));

        $pageTitle = 'آرشیو کتب';
        return inertia('Web/Books/Archives', compact('books', 'categories', 'pageTitle'));
    }

    public function show(Book $book)
    {
        $user = auth()->user();
        $bookSource = $book;
        $book->update(['views' => $book->views + 1]);
        $book = WebBookDetailsResource::make($book);
        $similarCourses = Book::whereHas('categories', function ($query) use ($book) {
            $query->whereIn('id', $book->categories->pluck('id'));
        })
            ->where('id', '!=', $book->id)
            ->where('status', 'publish')
            ->take(5)
            ->get();
        $related = WebBooksResource::collection($similarCourses);
        $pageTitle = $bookSource->title;
        return Inertia::render('Web/Books/Show', compact('book', 'user', 'related', 'pageTitle'));
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


    public function rating(Request $request, Book $book)
    {
        $user = auth()->user();
        if (!$user) {
            return sendJson('error', 'ابتدا وارد سایت شوید');
        } elseif ($request->rate < 1 || $request->rate > 5) {
            return sendJson('error', 'امتیاز باید بین ۱ تا ۵ باشد');
        }
        if ($user->hasRatedBook($book)) {
            $findRate = $book->ratings()->where('user_id', $user->id)->first();
            $diffInMinutes = now()->diffInMinutes($findRate->updated_at, true);
            if ($diffInMinutes <= 1) {
                return sendJson('error', 'شما فقط هر یک دقیقه یک‌بار می‌توانید امتیاز را تغییر دهید');
            }
            $findRate->update(['rate' => $request->rate]);
        } else {
            $book->ratings()->create([
                'user_id' => $user->id,
                'rate' => $request->rate
            ]);
        }
        return sendJson('success', 'امتیاز با موفقیت ثبت شد', ['rate' => number_format(Book::find($book->id)->rate, 1)]);
    }
}
