<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookCreateRequest;
use App\Http\Requests\Admin\BookUpdateRequest;
use App\Http\Resources\AdminBookResource;
use App\Models\Book;
use App\Models\Category;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        // فیلتر بر اساس وضعیت
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if($request->filled('author')) {
            $query->whereLike('author', "%{$request->author}%");
        }
        if($request->filled('search')){
            $query->where(function ($query) use ($request) {
                $query->where('title', 'like', "%{$request->search}%")
                    ->orWhere('subtitle', 'like', "%{$request->search}%")
                    ->orWhere('summary', 'like', "%{$request->search}%")
                    ->orWhere('content', 'like', "%{$request->search}%");
            });
        }

        $books = AdminBookResource::collection($query->orderBy('id','desc')->paginate(env('PER_PAGE')));
        $status = enumFormated(BookStatusEnum::cases());
        return Inertia::render('Admin/Books/List', compact('books', 'status'));
    }

    public function create()
    {
        $site_url = env('APP_URL').'/books/';
        $status = enumFormated(BookStatusEnum::cases());
        $categories = Category::where('type', 'book')->get()->map(fn ($item) => ['value' => $item->id, 'title' => $item->title]);
        return Inertia::render('Admin/Books/Create', compact('site_url', 'status', 'categories'));
    }

    public function store(BookCreateRequest $request)
    {
        //dd($request->all());
        try {
            $book = DB::transaction(function () use ($request) {
                $slug = $request->slug ? createSlug($request->slug) : createSlug($request->title);
                $slug = makeSlugUnique($slug, Book::class);
                $args = [
                    'title'          => $request->title,
                    'subtitle'       => $request->subtitle,
                    'slug'           => $slug,
                    'summary'        => $request->summary,
                    'content'        => $request->input('content'),
                    'thumbnail_id'   => $request->thumbnail_id,
                    'publisher'      => $request->publisher,
                    'price'          => $request->price,
                    'special_price'  => $request->special_price,
                    'is_stock'       => in_array($request->is_stock,['limited', 'yes']),
                    'stock'          => $request->is_stock == 'limited' ? null : $request->stock,
                    'max_order'      => $request->max_order,
                    'year_published' => $request->year_published,
                    'edition'        => $request->edition,
                    'size'           => $request->size,
                    'author'         => $request->author,
                    'status'         => $request->status,
                ];
                $book = Book::create($args);
                if($book){
                    if ($request->has('category') && is_array($request->category)) {
                        $book->categories()->sync($request->category);
                    }
                }
                return $book;
            });
            return redirectMessage('success', 'کتاب با موفقیت ایجاد شد.',redirect:  route('admin.books.edit',$book->id));
        }
        catch (\Exception $e) {
            $error = log_error($e);
            return redirectMessage('error', "خطایی پیش آمد (کدخطا: $error->id)");
        }
    }

    public function edit(Book $book)
    {
        $site_url = env('APP_URL').'/books/';
        $status = enumFormated(BookStatusEnum::cases());
        $categories = Category::where('type', 'book')->get()->map(fn ($item) => ['value' => $item->id, 'title' => $item->title]);
        $book = new AdminBookResource($book);
        return Inertia::render('Admin/Books/Edit', compact('site_url', 'status', 'categories', 'book'));
    }

    public function update(Book $book, BookUpdateRequest $request)
    {
        try {
            DB::transaction(function () use ($request, $book) {
                $slug = $request->slug;
                if($book->slug !== $request->slug) {
                    $slug = $request->slug ? createSlug($request->slug) : createSlug($request->title);
                    $slug = makeSlugUnique($slug, Book::class);
                }
                $update = $book->update([
                    'title'          => $request->title,
                    'subtitle'       => $request->subtitle,
                    'slug'           => $slug,
                    'summary'        => $request->summary,
                    'content'        => $request->input('content'),
                    'thumbnail_id'   => $request->thumbnail_id,
                    'publisher'      => $request->publisher,
                    'price'          => $request->price,
                    'special_price'  => $request->special_price,
                    'is_stock'       => in_array($request->is_stock,['limited', 'yes']),
                    'stock'          => $request->is_stock == 'limited' ? null : $request->stock,
                    'max_order'      => $request->max_order,
                    'year_published' => $request->year_published,
                    'edition'        => $request->edition,
                    'size'           => $request->size,
                    'author'         => $request->author,
                    'status'         => $request->status,
                ]);
                if($update){
                    if ($request->has('category') && is_array($request->category)) {
                        $book->categories()->sync($request->category);
                    }
                }
                return $update;
            });
            return redirectMessage('success', 'کتاب با موفقیت ویرایش شد.');
        }
        catch (\Exception $e) {
            $error = log_error($e);
            return redirectMessage('error', "خطایی پیش آمد (کدخطا: $error->id)");
        }
    }

}
