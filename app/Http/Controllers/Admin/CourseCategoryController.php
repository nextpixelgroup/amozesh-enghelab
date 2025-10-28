<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminCategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CourseCategoryController extends Controller
{
    public function index()
    {
        $categories = AdminCategoryResource::collection(Category::where('type', 'course')->paginate(10));
        return Inertia::render('Admin/Courses/Categories/List', compact('categories'));

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        try {
            $slug = $request->slug ? createSlug($request->slug) : createSlug($request->title);
            $slug = makeSlugUnique($slug, Category::class);
            $category = Category::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'slug' => $slug,
                'type' => 'course',
                'is_active' => $validated['is_active'],
            ]);
            return redirectMessage('success', 'دسته با موفقیت ایجاد شد.');
        }
        catch (\Exception $exception){
            return redirectMessage('error', $exception->getMessage());
        }
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        try {
            $category->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'],
            ]);
            return redirectMessage('success', 'دسته با موفقیت به روز رسانی شد.');
        }
        catch (\Exception $exception){
            return redirectMessage('error', $exception->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirectMessage('success', 'دسته با موفقیت حذف شد.');
        }
        catch (\Exception $exception){
            return redirectMessage('error', $exception->getMessage());
        }
    }
}
