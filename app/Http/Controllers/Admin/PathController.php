<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Path;
use App\Models\PathItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PathController extends Controller
{
    public function index()
    {
        $courses = Course::orderBy('id', 'desc')->get()->map(fn ($course) => ['value' => $course->id, 'title' => $course->title]);
        $paths = Path::with(['items.course' => function($query) {
            $query->select('id', 'title');
        }])->orderBy('order', 'asc')
            ->get()
            ->map(function($path) {
                return [
                    'id' => $path->id,
                    'title' => $path->title,
                    'order' => $path->order,
                    'items' => $path->items->map(function($item) {
                        return [
                            'id' => $item->course->id,
                            'title' => $item->course->title
                        ];
                    })
                ];
            });

        return Inertia::render('Admin/Paths/List', compact('courses', 'paths'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $order = 1;
        foreach ($request->all() as $key => $item) {
            if(isset($item['id']) && $item['id'] != null){
                $path = Path::find($item['id']);
                $path->update([
                    'title' => $item['title'],
                    'order' => $order++,
                ]);
                $pathItem = PathItem::where('path_id', $item['id'])->delete();
                $courseOrder = 1;
                foreach ($item['courses'] as $course) {
                    $pathItem = PathItem::create([
                        'path_id' => $path->id,
                        'course_id' => $course,
                        'order' => $courseOrder++
                    ]);
                }
            }
            else{
                $path = Path::create([
                    'title' => $item['title'],
                    'order' => $order++,
                ]);
                if($path){
                    $courseOrder = 1;
                    foreach ($item['courses'] as $course) {
                        $pathItem = PathItem::create([
                            'path_id' => $path->id,
                            'course_id' => $course,
                            'order' => $courseOrder++
                        ]);

                    }
                }
            }
        }
        return redirectMessage('success', 'با موفقیت ذخیره شد');
    }

    public function destroy(Path $path)
    {
        try {
            $path->delete();
            return sendJson('success', 'حذف موفقیت آمیز بود');
        }
        catch (\Exception $exception){
            return sendJson('error', $exception->getMessage());
        }
    }

}
