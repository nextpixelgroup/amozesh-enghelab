<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminPagesResource;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $query = Page::query()->orderBy('published_at','desc')->paginate(10);
        $pages = AdminPagesResource::collection($query);
        return inertia('Admin/Pages/List', compact('pages'));
    }

    public function edit(Page $page)
    {
        return inertia('Admin/Pages/Edit',compact('page'));
    }
}
