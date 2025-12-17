<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index()
    {
        return inertia('Panel/Bookmarks/Index');
    }
}
