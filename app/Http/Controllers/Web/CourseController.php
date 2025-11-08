<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        return inertia('Web/Courses/Index', []);
    }

    public function show(Request $request, Course $course)
    {
        return inertia('Web/Courses/Show', []);
    }
}
