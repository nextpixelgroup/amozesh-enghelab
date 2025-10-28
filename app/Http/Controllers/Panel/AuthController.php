<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function index()
    {
        return Inertia::render('Panel/Auth/Index');
    }

    public function login()
    {
        return Inertia::render('Panel/Auth/Login');
    }
}
