<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PathController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Paths/List');
    }

    public function create()
    {
        return Inertia::render('Admin/Paths/Create');
    }

    public function edit()
    {
        return Inertia::render('Admin/Paths/Edit');
    }
}
