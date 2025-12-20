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

        $userId = auth()->id();
        $query = Course::with(['thumbnail', 'teacher'])
            ->where('status', 'publish')
            ->whereHas('students', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->with(['students' => function($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->orderBy(function($query) use ($userId) {
                $query->select('created_at')
                    ->from('course_students')
                    ->whereColumn('course_id', 'courses.id')
                    ->where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->limit(1);
            }, 'desc')
            ->get();
        $courses = WebCoursesResource::collection($query);
        return Inertia::render('Panel/Courses/Index', compact('courses'));
    }
}
