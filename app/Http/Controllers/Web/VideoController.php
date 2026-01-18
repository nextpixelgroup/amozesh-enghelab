<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessVideoJob;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoController extends Controller
{

    public function index($uuid)
    {
        $video = Video::with('quiz.questions.options')->where('uuid', $uuid)->first();
        Gate::authorize('view', $video);
        if($video) {
            $quiz = [];
            if ($video->quiz->is_active) {
                $quiz = [
                    //'id' => $video->quiz->id,
                    'title' => $video->quiz->title,
                    'description' => $video->quiz->description,
                    'questions' => $video->quiz->questions()->where('is_active', true)->get()->map(function ($question) {
                        return [
                            'id' => $question->id,
                            'question' => $question->question_text,
                            'options' => $question->options->map(function ($option) {
                                return [
                                    'id' => $option->id,
                                    'option' => $option->option_text,
                                ];
                            })->toArray(),
                        ];
                    })->toArray(),
                ];
            }
            $video = [
                'status' => $video->statusObject,
            ];

            return inertia('Web/Video/Index', compact('uuid', 'quiz', 'video'));
        }
        else{
            return redirect()->route('web.404');
        }
    }

    public function init(Request $request, $uuid)
    {

        try {
            $video = Video::where('uuid',$uuid)->first();

            if(!in_array($video->status,['pending', 'rejected'])){
                return response()->json(['status' => 'error', 'message' => 'شما نمی‌توانید در آزمون شرکت کنید.'], 500);
            }

            if ($video->path && Storage::exists($video->path)) {
                Storage::delete($video->path);
                Storage::delete($video->thumbnail);
            }

            $video->update([
                'status' => 'recording',
                'path' => null,
                'thumbnail' => null,
                'duration' => 0,
                'completed_at' => null,
            ]);
            $chunksPath = storage_path("app/temp_uploads/{$uuid}");

            if (File::exists($chunksPath)) {
                File::deleteDirectory($chunksPath);
            }
            return response()->json(['status' => 'success', 'uuid' => $uuid]);

        } catch (\Exception $e) {
            log_error($e);
            return response()->json(['status' => 'error', 'message' => 'Failed to initialize recording'], 500);
        }
    }

    public function cancel(Request $request, $uuid)
    {
        try {
            $video = Video::where('uuid', $uuid)->first();
            if (!$video) {
                return response()->json(['status' => 'error', 'message' => 'Video not found'], 404);
            }

            // ۱. اول دیتابیس را آپدیت کن (قفل کردن عملیات آپلود)
            // این کار باعث می‌شود شرطی که در uploadChunk گذاشتیم، برای درخواست‌های بعدی فعال شود
            $video->update([
                'status' => 'pending',
                'path' => null,
                'thumbnail' => null,
                'duration' => 0,
                'completed_at' => null,
            ]);

            // ۲. پاک کردن کلیدهای Redis (چون از ردیس استفاده می‌کنید خیلی مهمه)
            Redis::del("upload:{$uuid}:chunks");

            // ۳. پاک کردن فایل‌های اصلی (اگر وجود داشتند)
            if ($video->path && Storage::exists($video->path)) {
                Storage::delete($video->path);
            }
            if ($video->thumbnail && Storage::exists($video->thumbnail)) {
                Storage::delete($video->thumbnail);
            }

            // ۴. پاک کردن دایرکتوری موقت چانک‌ها
            $chunksPath = storage_path("app/temp_uploads/{$uuid}");
            if (File::exists($chunksPath)) {
                File::deleteDirectory($chunksPath);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            log_error($e);
            return response()->json(['status' => 'error', 'message' => 'Failed to cancel'], 500);
        }
    }


    // ... متدهای uploadChunk و finish که قبلا داشتید

    public function uploadChunk(Request $request)
    {
        // اعتبارسنجی سبک
        $request->validate([
            'uuid' => 'required|string',
            'chunk_index' => 'required|integer',
            'chunk' => 'required|file',
        ]);

        $uuid = $request->input('uuid');

        // *** افزودن چک وضعیت (Race Condition Fix) ***
        // فقط یک کوئری سبک میزنیم تا وضعیت رو بگیریم.
        // اگر از مدل Video استفاده می‌کنید:
        $videoStatus = Video::where('uuid', $uuid)->value('status');

        // اگر ویدیو پیدا نشد یا وضعیتش در حال ضبط نبود (یعنی کنسل شده)، عملیات را متوقف کن
        // توجه: فرض بر این است که وقتی کنسل می‌کنید وضعیت به pending برمی‌گردد
        // اگر مقدار enum دارید باید چک کنید برابر مقدار recording باشد
        // مثلا: if ($videoStatus !== 'recording') ...

        // اینجا فرض میکنیم اگر pending باشد یعنی ریست شده و نباید ذخیره شود
        if (!$videoStatus || $videoStatus === 'pending' || $videoStatus === 'rejected') {
            // فایل رو نادیده میگیریم و موفق برمیگردونیم تا کلاینت ارور نده
            return response()->json(['status' => 'ignored_cancelled']);
        }
        // *******************************************

        $index = $request->input('chunk_index');
        $file = $request->file('chunk');

        // مسیر موقت: storage/app/temp_uploads/{uuid}/
        $path = storage_path("app/temp_uploads/{$uuid}");

        if (!file_exists($path)) {
            // یک لایه امنیتی دیگر: اگر پوشه وجود ندارد و وضعیت هم رکوردینگ نیست، نسازش
            // اما چون بالا چک کردیم، اینجا فقط می‌سازیم
            mkdir($path, 0777, true);
        }

        // ذخیره فایل فیزیکی
        $file->move($path, "{$index}.tmp");

        // ثبت در Redis
        Redis::sadd("upload:{$uuid}:chunks", $index);
        Redis::expire("upload:{$uuid}:chunks", 86400);

        return response()->json(['status' => 'ok']);
    }


    public function finish(Request $request)
    {
        $uuid = $request->input('uuid');
        $video = Video::where('uuid',$uuid)->first();
        $totalChunks = $request->input('total_chunks');

        // چک کردن تعداد چانک‌های دریافت شده از Redis
        $receivedChunksCount = Redis::scard("upload:{$uuid}:chunks");

        if ($receivedChunksCount < $totalChunks) {
            // پیدا کردن چانک‌های گم شده (Optional: برای دیباگ یا درخواست دوباره)
            return response()->json([
                'status' => 'error',
                'message' => 'Upload incomplete',
                'received' => $receivedChunksCount,
                'expected' => $totalChunks
            ], 422);
        }

        // اگر همه چیز کامل بود، جاب سنگین را صدا بزن
        // در اینجا یک رکورد اولیه در دیتابیس می‌سازیم

        $video->update(['status' => 'process']);

        // ارسال به صف برای پردازش FFmpeg
        ProcessVideoJob::dispatch($video, $totalChunks);

        // پاک کردن کلید Redis چون دیگر نیاز نیست
        Redis::del("upload:{$uuid}:chunks");

        return response()->json(['status' => 'completed', 'video_id' => $uuid]);
    }


}
