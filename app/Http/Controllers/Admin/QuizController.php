<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VideoStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminVideoDetailsResource;
use App\Http\Resources\AdminVideosResource;
use App\Models\Video;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $query = Video::query()->with(['quiz', 'course', 'user']);
        $quizzes = AdminVideosResource::collection($query->paginate(env('PER_PAGE')));
        $status = enumFormated(VideoStatusEnum::cases());
        return inertia('Admin/Quizzes/List', compact('quizzes', 'status'));
    }

    public function edit(Video $video)
    {
        $quiz = AdminVideoDetailsResource::make($video);
        return inertia('Admin/Quizzes/Edit', compact('quiz'));
    }

    public function update(Request $request, Video $video)
    {
        $user = auth()->user();
        if($request->filled('status')){
            $video->update(['status' => $request->input('status')]);
        }
        if($request->filled('note')){
            $video->notes()->create([
                'user_id' => $user->id,
                'note' => $request->input('note'),
            ]);
        }
        return redirectMessage('success', 'با موفقیت ذخیره شد');
    }

    public function url($uuid)
    {
        $video = Video::where('id', $uuid)->firstOrFail();
        $path = storage_path('app/private/' . $video->path);
        if (!file_exists($path) || !$video->path) {
            abort(404);
        }
        return response()->file($path);
    }

    public function poster($uuid)
    {
        $video = Video::where('id', $uuid)->firstOrFail();
        $path = storage_path('app/private/' . $video->thumbnail);
        if (!file_exists($path) || !$video->thumbnail) {
            abort(404);
        }
        return response()->file($path);
    }
}

