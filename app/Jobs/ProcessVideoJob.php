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

    // افزایش تایم‌اوت کلاس به ۲ ساعت برای ویدیوهای طولانی روی سرور ضعیف
    public $timeout = 7200;

    // تعداد تلاش فقط ۱ بار باشد، چون اگر یکبار به خاطر ارور منطقی فیل شد، بار دوم هم می‌شود
    public $tries = 1;

    public function __construct(Video $video, $totalChunks)
    {
        $this->video = $video;
        $this->totalChunks = $totalChunks;
    }

    public function handle()
    {
        // افزایش مموری لیمیت فقط برای این پروسه (در صورت نیاز)
        ini_set('memory_limit', '1024M');

        $uuid = $this->video->uuid;
        $tempPath = storage_path("app/temp_uploads/{$uuid}");
        $outputDir = storage_path("app/private/videos");
        $finalVideoPath = "{$outputDir}/{$uuid}.mp4";
        $thumbnailPath = "{$outputDir}/{$uuid}.jpg";
        $mergedSourcePath = "{$tempPath}/full_source.webm";

        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        try {
            // =========================================================
            // مرحله ۱: ادغام با استریم (بهینه سازی حافظه RAM)
            // =========================================================

            // باز کردن فایل مقصد برای نوشتن
            $destStream = fopen($mergedSourcePath, 'ab'); // b flag for binary safe
            if (!$destStream) {
                throw new \Exception("Could not open destination file stream.");
            }

            $chunksFound = 0;

            for ($i = 0; $i < $this->totalChunks; $i++) {
                $chunkFile = "{$tempPath}/{$i}.tmp";

                if (file_exists($chunkFile)) {
                    // خواندن فایل به صورت استریم (پایپ کردن) بدون لود کردن در رم
                    $srcStream = fopen($chunkFile, 'rb');
                    if ($srcStream) {
                        stream_copy_to_stream($srcStream, $destStream);
                        fclose($srcStream);
                        $chunksFound++;

                        // حذف آنی چانک برای خالی شدن دیسک
                        unlink($chunkFile);
                    }
                }
            }

            fclose($destStream);

            if ($chunksFound === 0) {
                throw new \Exception("No chunks found to merge for video $uuid");
            }

            // =========================================================
            // مرحله ۲: تبدیل سریع با FFmpeg
            // =========================================================

            $command = [
                'ffmpeg',
                '-y',
                '-i', $mergedSourcePath,

                // استفاده از تمام هسته‌های CPU
                '-threads', '0',

                // تنظیمات ویدیو
                '-c:v', 'libx264',

                // تغییر به ultrafast برای سرعت بیشتر (حجم کمی بالا می‌رود ولی سرعت دوبرابر می‌شود)
                // گزینه‌ها: ultrafast, superfast, veryfast, faster, fast, medium (default)
                '-preset', 'ultrafast',

                // تنظیم Tune برای تاخیر کمتر
                '-tune', 'zerolatency',

                '-crf', '28', // کیفیت استاندارد وب (کمی کیفیت را فدای سرعت می‌کنیم)
                '-pix_fmt', 'yuv420p',

                // تنظیمات صدا
                '-c:a', 'aac',
                '-b:a', '128k',
                '-ac', '2', // Stereo
                '-ar', '44100',

                // Web Optimization
                '-movflags', '+faststart',

                $finalVideoPath
            ];

            $this->runProcess($command);

            // =========================================================
            // مرحله ۳: ساخت تامنیل و آپدیت
            // =========================================================

            $duration = $this->getVideoDuration($finalVideoPath);

            // گرفتن عکس از ثانیه ۱ یا ۱۰٪ ویدیو
            $seekTime = ($duration > 10) ? '00:00:05.000' : '00:00:01.000';

            $thumbCommand = [
                'ffmpeg',
                '-ss', $seekTime,
                '-i', $finalVideoPath,
                '-vframes', '1',
                '-q:v', '2',
                '-y',
                $thumbnailPath
            ];

            $this->runProcess($thumbCommand);

            $this->video->update([
                'status' => 'completed',
                'path' => "videos/{$uuid}.mp4",
                'thumbnail' => "videos/{$uuid}.jpg",
                'duration' => $duration,
                'size' => File::size($finalVideoPath),
            ]);

        } catch (\Exception $e) {
            Log::error("Video processing failed for {$uuid}: " . $e->getMessage());
            throw $e;
        } finally {
            // پاکسازی نهایی (اگر چانکی باقی مانده یا فایل سورس)
            if (File::exists($tempPath)) {
                File::deleteDirectory($tempPath);
            }
        }
    }

    private function runProcess(array $command)
    {
        // تایم اوت پردازش را همسو با تایم‌اوت جاب بالا ببرید
        $result = Process::timeout($this->timeout)->run($command);

        if ($result->failed()) {
            // لاگ کردن خروجی ارور FFmpeg برای دیباگ دقیق‌تر
            Log::error("FFmpeg Error Output: " . $result->errorOutput());
            throw new \Exception("FFmpeg Process Failed");
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
        $this->video->update(['status' => 'failed']);
        Log::error("Job Failed Final: " . $exception->getMessage());
    }
}
