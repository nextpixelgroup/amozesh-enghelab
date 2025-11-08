<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminCategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookCategoryController extends Controller
{
    public function index()
    {
        $categories = AdminCategoryResource::collection(Category::where('type', 'book')->orderBy('id', 'desc')->paginate(config('app.per_page')));
        return Inertia::render('Admin/Books/Categories/List', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:categories,title',
            'description' => 'nullable|string',
        ],[
            'title.required' => 'فیلد عنوان الزامی است.',
            'title.string' => 'فیلد عنوان باید متن باشد.',
            'title.max' => 'فیلد عنوان نباید بیشتر از :max کاراکتر باشد.',
            'title.unique' => 'عنوان در پایگاه داده وجود دارد، عنوان دیگری وارد کنید',
            'description' => 'توضیحات به درستی وارد نشده است.',
        ]);

        try {
            $slug = $request->slug ? createSlug($request->slug) : createSlug($request->title);
            $slug = makeSlugUnique($slug, Category::class);
            $category = Category::create([
                'title'       => $validated['title'],
                'description' => $validated['description'] ?? null,
                'slug'        => $slug,
                'type'        => 'book',
            ]);
            return redirectMessage('success', 'دسته با موفقیت ایجاد شد.', redirect: route('admin.books.categories.index'));
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
        ],[
            'title.required' => 'فیلد عنوان الزامی است.',
            'title.string' => 'فیلد عنوان باید متن باشد.',
            'title.max' => 'فیلد عنوان نباید بیشتر از :max کاراکتر باشد.',
            'description' => 'توضیحات به درستی وارد نشده است.',
        ]);

        try {
            if($category->slug !== $request->slug) {
                $slug = $request->slug ? createSlug($request->slug) : createSlug($request->title);
                $slug = makeSlugUnique($slug, Category::class);
            }
            else{
                $slug = $request->slug;
            }
            $category->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'slug' => $slug,
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
