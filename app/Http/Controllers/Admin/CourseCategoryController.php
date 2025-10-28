<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CourseCategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Courses/Categories/List');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        try {
            $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);
            $slug = makeSlugUnique($slug, Category::class);
            $category = Category::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'slug' => $slug,
                'status' => $validated['status'],
            ]);
            return redirectMessage('success', 'دسته با موفقیت ایجاد شد.');
        }
        catch (\Exception $exception){
            return redirectMessage('error', $exception->getMessage());
        }
    }
}
