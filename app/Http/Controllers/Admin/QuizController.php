<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VideoStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminVideoDetailsResource;
use App\Http\Resources\AdminVideosResource;
use App\Jobs\SendSmsForQuiz;
use App\Models\Certificate;
use App\Models\Video;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $query = Video::query()->with(['quiz', 'course', 'user'])
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->whereHas('user', function($q) use ($search) {
                    $q->whereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ["%{$search}%"])
                        ->orWhere('mobile', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });

        $quizzes = AdminVideosResource::collection($query->orderBy('created_at', 'desc')->paginate(env('PER_PAGE')));
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
            if($request->input('status') === 'rejected'){
                SendSmsForQuiz::dispatch($video->user,$video->uuid,'retryFinalQuiz');
            }
            elseif ($request->input('status') === 'approved'){
                $certificate = Certificate::where('user_id', $user->id)->where('course_id', $video->course_id)->exists();
                if (!$certificate){
                Certificate::create([
                    'user_id' => $video->user_id,
                    'course_id' => $video->course_id,
                ]);
                }
            }
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

