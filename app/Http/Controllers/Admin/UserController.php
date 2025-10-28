<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Users/List');
    }

    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    public function store()
    {
        return redirectMessage('error', 'salam');
    }

    public function edit()
    {
        return Inertia::render('Admin/Users/Edit');
    }
}
