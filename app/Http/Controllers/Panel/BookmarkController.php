<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function books()
    {
        return inertia('Panel/Bookmarks/Books');
    }

    public function courses()
    {
        return inertia('Panel/Bookmarks/Courses');
    }
}
