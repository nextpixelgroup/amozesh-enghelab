<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebTeacherDetailsResource;
use App\Http\Resources\WebTeachersResource;
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
        return inertia('Web/Teachers/Index', compact('teachers'));
    }

    public function show(User $teacher)
    {
        $teacher = WebTeacherDetailsResource::make($teacher);
        return inertia('Web/Teachers/Show', compact('teacher'));
    }
}
