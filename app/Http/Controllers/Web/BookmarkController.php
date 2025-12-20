<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Bookmark;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookmarkController extends Controller
{
    public function courseStore(Course $course)
    {
        $user = auth()->user();
        $user->bookmarks()->create([
            'bookmarkable_id' => $course->id,
            'bookmarkable_type' => get_class($course)
        ]);
        return sendJson('success','به لیست علاقه‌مندی افزوده شد');
    }

    public function bookStore(Book $book)
    {
        $user = auth()->user();
        $user->bookmarks()->create([
            'bookmarkable_id' => $book->id,
            'bookmarkable_type' => get_class($book)
        ]);
    }

    public function destroy(Bookmark $bookmark)
    {
        Gate::authorize('delete', $bookmark);
        $bookmark->delete();
        return sendJson('success','از لیست علاقه‌مندی حذف شد');
    }
}
