<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherController extends Controller
{
    public function index()
    {
        return inertia('Web/Teachers/Index');
    }

    public function show(User $teacher)
    {
        return inertia('Web/Teachers/Show', compact('teacher'));
    }
}
