<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebCoursesResource;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {

        $query = Course::with(['lessons','thumbnail', 'categories', 'teacher'])
            ->where('status', 'publish');

        $courses = WebCoursesResource::collection($query->orderBy('published_at', 'desc')->paginate(env('COURSES_PER_PAGE')));
        return Inertia::render('Panel/Courses/Index', compact('courses'));
    }
}
