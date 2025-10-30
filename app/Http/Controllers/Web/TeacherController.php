<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherController extends Controller
{
    public function show(User $teacher)
    {
        return Inertia::render('App/Teachers/Show', compact('teacher'));
    }
}
