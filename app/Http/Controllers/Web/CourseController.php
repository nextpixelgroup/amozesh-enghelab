<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebCoursesResource;
use App\Models\Category;
use App\Models\Course;
use App\Models\Setting;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        sleep(1);
        $categories = Category::where('type', 'course')->orderBy('created_at', 'desc')->get()->map(function ($item) {
           return [
               'value' => $item->slug,
               'title' => $item->title
           ];
        })->prepend([
            'value' => 'all',
            'title' => 'همه دسته‌ها',
        ]);
        $query = Course::with(['lessons','thumbnail', 'categories', 'teacher'])
            ->when($request->filled('category') && $request->category !== 'all', function ($query) use ($request) {
                $query->whereRelation('categories', 'slug', $request->category);
            })
        ->when($request->filled('search'), function ($query) use ($request) {
            $query->where('title', 'like', "%{$request->search}%")
            ->orWhere('description', 'like', "%{$request->search}%");
        })
        ->when($request->filled('sort'), function ($query) use ($request) {
            $query->orderBy('published_at', $request->sort);
        });
        $courses = WebCoursesResource::collection($query->orderBy('published_at', 'desc')->paginate(env('COURSES_PER_PAGE')));
        $stats = Setting::getValue('statCourses');
        $stats['courses'] = number_format($stats['courses']);
        $stats['ratings'] = number_format($stats['ratings'],1);
        $stats['students'] = number_format($stats['students']);
        $stats['seasons'] = number_format($stats['seasons']);
        return inertia('Web/Courses/Index', compact('categories', 'courses', 'stats'));
    }

    public function show(Request $request, Course $course)
    {
        return inertia('Web/Courses/Show', []);
    }
}
