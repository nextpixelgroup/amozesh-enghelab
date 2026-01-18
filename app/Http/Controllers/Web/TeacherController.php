<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebCoursesResource;
use App\Http\Resources\WebTeacherDetailsResource;
use App\Http\Resources\WebTeachersResource;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherController extends Controller
{
    public function index()
    {
        $query = User::query()->whereHas('roles', function ($query) {
            $query->where('name', 'teacher');
        })->get();
        $teachers = WebTeachersResource::collection($query);
        $pageTitle = 'اساتید';
        return inertia('Web/Teachers/Index', compact('teachers', 'pageTitle'));
    }

    public function show(User $teacher)
    {
        $teacherSource = $teacher;
        $teacher = WebTeacherDetailsResource::make($teacher);

        $query = Course::with(['lessons','thumbnail', 'categories', 'teacher'])
            ->where('teacher_id',$teacher->id)
            ->where('status', 'publish');
        $courses = WebCoursesResource::collection($query->orderBy('published_at', 'desc')->paginate(env('COURSES_PER_PAGE')));

        $pageTitle = $teacherSource->firstname . ' ' . $teacherSource->lastname;
        return inertia('Web/Teachers/Show', compact('teacher', 'pageTitle', 'courses'));
    }
}
