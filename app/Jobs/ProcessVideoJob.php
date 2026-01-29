<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Throwable;

class ProcessVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;
    public $totalChunks;

    // افزایش تایم‌اوت به ۳ ساعت (۱۰۸۰۰ ثانیه) برای ویدیوهای طولانی
    public $timeout = 10800;
    public $tries = 1;
    // اگر جاب به دلیل تایم‌اوت فیل شد، مارک شود
    public $failOnTimeout = true;

    public function __construct(Video $video, $totalChunks)
    {
        $this->onConnection('redis')->onQueue('quizVideo');
        $this->video = $video;
        $this->totalChunks = $totalChunks;
    }

    public function handle()
    {
        // افزایش مموری برای این اسکریپت خاص
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 10800);

        $uuid = $this->video->uuid;
        $tempPath = storage_path("app/temp_uploads/{$uuid}");
        $outputDir = storage_path("app/private/videos");
        $finalVideoPath = "{$outputDir}/{$uuid}.mp4";
        $thumbnailPath = "{$outputDir}/{$uuid}.jpg";

        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        try {
            Log::info("Job started for Video: $uuid | Chunks: {$this->totalChunks}");

            // =========================================================
            // مرحله ۱: ساخت لیست فایل‌ها برای FFmpeg (روش Concat Demuxer)
            // =========================================================
            // این روش به جای چسباندن فیزیکی فایل‌ها، یک لیست متنی به ffmpeg می‌دهد
            // که بسیار سریع‌تر است و مشکل I/O ندارد.

            $listFilePath = "{$tempPath}/mylist.txt";
            $fileListContent = "";

            // پیدا کردن تمام فایل‌های .tmp در پوشه و مرتب‌سازی دقیق
            $chunkFiles = glob("{$tempPath}/*.tmp");
            sort($chunkFiles); // مرتب‌سازی الفبایی (چون در کنترلر zero-padding کردیم درست کار میکند)

            if (empty($chunkFiles)) {
                throw new \Exception("No chunk files found in $tempPath");
            }

            foreach ($chunkFiles as $file) {
                // ساختار فایل لیست باید به صورت: file '/path/to/file' باشد
                $fileListContent .= "file '" . $file . "'\n";
            }

            File::put($listFilePath, $fileListContent);

            Log::info("Created concat list file for $uuid");

            // =========================================================
            // مرحله ۲: پردازش اصلی با FFmpeg
            // =========================================================

            // اگر سرور شما چند هسته‌ای است، Threads را 0 یا 2 بگذارید.
            // 1 خیلی کند است. اگر سرور ضعیف است روی 1 بماند.
            // من روی 0 میگذارم (Automatic) ولی با nice process
            $threads = '1';

            $command = [
                'ffmpeg',
                '-f', 'concat',       // استفاده از متد concat
                '-safe', '0',         // اجازه خواندن مسیرهای absolute
                '-i', $listFilePath,  // فایل متنی لیست چانک‌ها

                '-threads', $threads,

                // تنظیمات ویدیو (H.264)
                '-c:v', 'libx264',
                '-preset', 'ultrafast', // سریع‌ترین حالت فشرده‌سازی
                '-crf', '28',           // کیفیت متوسط رو به بالا (عدد کمتر = کیفیت بهتر/حجم بیشتر)
                '-pix_fmt', 'yuv420p',  // سازگاری با همه پلیرها
                '-r', '24',             // تثبیت فریم ریت روی ۲۴ (جلوگیری از مشکلات صدا/تصویر)

                // تنظیمات صدا
                '-c:a', 'aac',
                '-b:a', '96k',          // کاهش بیت‌ریت صدا برای کاهش حجم
                '-ac', '2',
                '-ar', '44100',

                '-movflags', '+faststart', // بهینه‌سازی برای پخش وب
                '-y',
                $finalVideoPath
            ];

            Log::info("Running FFmpeg conversion for $uuid...");

            // اجرای دستور
            $this->runProcess($command);

            // چک کردن خروجی
            if (!file_exists($finalVideoPath) || filesize($finalVideoPath) < 1024) {
                throw new \Exception("Output video file is missing or empty.");
            }

            // =========================================================
            // مرحله ۳: ساخت تامنیل
            // =========================================================

            $duration = $this->getVideoDuration($finalVideoPath);
            // گرفتن عکس از ۲۰٪ ویدیو (جایی که معمولا تصویر سیاه نیست)
            $seekSeconds = max(1, floor($duration * 0.2));
            $seekTime = gmdate('H:i:s', $seekSeconds);

            $thumbCommand = [
                'ffmpeg',
                '-ss', $seekTime,
                '-i', $finalVideoPath,
                '-vframes', '1',
                '-q:v', '5', // کیفیت تامنیل (کمتر=بهتر)
                '-y',
                $thumbnailPath
            ];

            try {
                $this->runProcess($thumbCommand);
            } catch (\Exception $e) {
                Log::warning("Thumbnail creation failed for $uuid (ignoring): " . $e->getMessage());
                // ادامه می‌دهیم، چون ویدیو اصلی ساخته شده
            }

            // =========================================================
            // مرحله ۴: ذخیره در دیتابیس و پاکسازی
            // =========================================================

            $this->video->update([
                'status' => 'completed',
                'path' => "videos/{$uuid}.mp4",
                'thumbnail' => file_exists($thumbnailPath) ? "videos/{$uuid}.jpg" : null,
                'duration' => $duration,
                'size' => File::size($finalVideoPath),
            ]);

            Log::info("Video processing completed successfully for $uuid");

            // پاکسازی فایل‌های موقت
            File::deleteDirectory($tempPath);

        } catch (\Exception $e) {
            Log::error("Video processing FAILED for $uuid: " . $e->getMessage());
            // اینجا throw می‌کنیم تا متد failed صدا زده شود
            throw $e;
        }
    }

    private function runProcess(array $command)
    {
        // استفاده از timeout که در کلاس تعریف شده
        $process = Process::timeout($this->timeout)->start($command);

        // صبر تا پایان پردازش
        $result = $process->wait();

        if ($result->failed()) {
            $errorMsg = $result->errorOutput();
            if (empty($errorMsg)) {
                $errorMsg = $result->output();
            }
            throw new \Exception("FFmpeg Error: " . substr($errorMsg, -500)); // فقط ۵۰۰ کاراکتر آخر ارور
        }
    }

    private function getVideoDuration($path)
    {
        $command = ['ffprobe', '-v', 'error', '-show_entries', 'format=duration', '-of', 'default=noprint_wrappers=1:nokey=1', $path];
        $result = Process::run($command);
        return $result->successful() && is_numeric(trim($result->output())) ? (float) trim($result->output()) : 0;
    }

    public function failed(Throwable $exception)
    {
        // ثبت وضعیت خطا در دیتابیس
        if ($this->video) {
            $this->video->update(['status' => 'failed']);
        }

        // پاکسازی فایل‌های موقت اگر مانده باشند
        $uuid = $this->video->uuid ?? 'unknown';
        $tempPath = storage_path("app/temp_uploads/{$uuid}");
        if (File::exists($tempPath)) {
            File::deleteDirectory($tempPath);
        }

        Log::error("Final failure for video {$uuid}: " . $exception->getMessage());
    }
}
