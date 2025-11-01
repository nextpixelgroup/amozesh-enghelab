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
        $site_url = env('APP_URL');
        return Inertia::render('Admin/Books/Create', compact('site_url'));
    }

    public function store(Request $request)
    {
        dd($request->all());
    }

    public function edit()
    {
        return Inertia::render('Admin/Books/Edit');
    }
}
