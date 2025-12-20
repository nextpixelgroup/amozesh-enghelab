<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebBooksResource;
use App\Http\Resources\WebCoursesResource;
use App\Models\Book;
use App\Models\Course;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function books()
    {
        $user = auth()->user();
        $query = $user->bookmarks()->where('bookmarkable_type', Book::class)->get()->pluck('bookmarkable');
        $books = WebBooksResource::collection($query);
        return inertia('Panel/Bookmarks/Books', compact('books'));
    }

    public function courses()
    {
        $user = auth()->user();
        $query = $user->bookmarks()->where('bookmarkable_type', Course::class)->get()->pluck('bookmarkable');
        $courses = WebCoursesResource::collection($query);
        return inertia('Panel/Bookmarks/Courses', compact('courses'));
    }
}
