<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessVideoJob;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class VideoController extends Controller
{

    public function record()
    {
        return inertia('Record');
    }

    public function init(Request $request)
    {
        // ۱. تولید UUID یکتا
        $uuid = (string) Str::uuid();

        // ۲. ایجاد رکورد در دیتابیس
        // فرض بر این است که مدل Video شما فیلد id یا uuid دارد
        $video = Video::create([
            'id' => $uuid, // اگر کلید اصلی شما UUID نیست، این را در ستون مربوطه ذخیره کنید
            'status' => 'uploading', // وضعیت اولیه
            // 'user_id' => auth()->id(), // اگر نیاز به احراز هویت دارید
        ]);

        // ۳. ایجاد پوشه موقت برای ذخیره چانک‌ها
        // این کار باعث می‌شود ریسک خطای همزمانی در ساخت پوشه از بین برود
        $tempPath = storage_path("app/temp_uploads/{$uuid}");

        if (!File::exists($tempPath)) {
            File::makeDirectory($tempPath, 0755, true);
        }

        // ۴. بازگشت UUID به فرانت
        return response()->json([
            'uuid' => $uuid,
            'message' => 'Session initialized'
        ]);
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
        $index = $request->input('chunk_index');
        $file = $request->file('chunk');

        // مسیر موقت: storage/app/temp_uploads/{uuid}/
        $path = storage_path("app/temp_uploads/{$uuid}");

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        // ذخیره فایل فیزیکی (روی SSD سرور خیلی سریع است)
        $file->move($path, "{$index}.tmp");

        // ثبت در Redis (بسیار سریع و اتمیک)
        // کلید: upload:{uuid}:chunks
        Redis::sadd("upload:{$uuid}:chunks", $index);

        // تنظیم انقضا برای جلوگیری از پر شدن رم (مثلا ۲۴ ساعت)
        Redis::expire("upload:{$uuid}:chunks", 86400);

        return response()->json(['status' => 'ok']);
    }

    public function finish(Request $request)
    {
        $uuid = $request->input('uuid');
        $video = Video::where('id',$uuid)->first();
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

        // ارسال به صف برای پردازش FFmpeg
        ProcessVideoJob::dispatch($video, $totalChunks);

        // پاک کردن کلید Redis چون دیگر نیاز نیست
        Redis::del("upload:{$uuid}:chunks");

        return response()->json(['status' => 'completed', 'video_id' => $uuid]);
    }

    public function stream(Request $request, $uuid)
    {
        // ۱. پیدا کردن ویدیو
        $video = Video::where('id', $uuid)->firstOrFail();

        // ۲. چک کردن دسترسی (Security Check)
        // مثلا فقط صاحب ویدیو یا ادمین بتواند ببیند
        // if ($request->user()->id !== $video->user_id) {
        //     abort(403);
        // }

        // ۳. ساخت مسیر کامل فایل
        // توجه: مسیر path در دیتابیس 'private_videos/UUID.mp4' ذخیره شده است
        $path = storage_path('app/public/' . $video->path);

        if (!file_exists($path)) {
            abort(404);
        }

        // ۴. پخش فایل (Stream)
        // متد file() در لاراول به صورت خودکار هدرهای Range را مدیریت می‌کند
        // که برای جلو/عقب زدن ویدیو ضروری است.
        return response()->file($path);
    }
}
