<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Books/List');
    }

    public function create()
    {
        return Inertia::render('Admin/Books/Create');
    }

    public function edit()
    {
        return Inertia::render('Admin/Books/Edit');
    }
}
