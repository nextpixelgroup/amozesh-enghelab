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
    public $timeout = 3600; // 1 Hour

    public function __construct(Video $video, $totalChunks)
    {
        $this->video = $video;
        $this->totalChunks = $totalChunks;
    }

    public function handle()
    {
        $uuid = $this->video->uuid;
        // مسیر فایل‌های موقت
        $tempPath = storage_path("app/temp_uploads/{$uuid}");
        // مسیر خروجی نهایی
        $outputDir = storage_path("app/private/videos");
        $finalVideoPath = "{$outputDir}/{$uuid}.mp4";
        $thumbnailPath = "{$outputDir}/{$uuid}.jpg";

        // مسیر فایل ادغام شده اولیه (WebM خام)
        $mergedSourcePath = "{$tempPath}/full_source.webm";

        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        try {
            // =========================================================
            // مرحله ۱: ادغام باینری چانک‌ها (Binary Concatenation)
            // =========================================================
            // به جای استفاده از ffmpeg concat demuxer، فایل‌ها را بایت به بایت به هم می‌چسبانیم.
            // این روش برای استریم‌های WebM که از مرورگر می‌آیند الزامی است.

            $mergedFileHandle = fopen($mergedSourcePath, 'a+');
            $chunksFound = 0;

            for ($i = 0; $i < $this->totalChunks; $i++) {
                $chunkFile = "{$tempPath}/{$i}.tmp";

                if (file_exists($chunkFile)) {
                    // خواندن چانک و نوشتن در فایل نهایی
                    $chunkContent = file_get_contents($chunkFile);
                    fwrite($mergedFileHandle, $chunkContent);
                    $chunksFound++;

                    // آزاد سازی حافظه متغیر در لوپ
                    unset($chunkContent);
                } else {
                   // Chunk missing during binary merge: {$chunkFile}
                }
            }

            fclose($mergedFileHandle);

            if ($chunksFound === 0) {
                throw new \Exception("No chunks found to merge for video $uuid");
            }


            // =========================================================
            // مرحله ۲: تبدیل فایل ادغام شده به MP4 با FFmpeg
            // =========================================================

            $command = [
                'ffmpeg',
                '-y', // Overwrite
                '-i', $mergedSourcePath, // ورودی فایل WebM یکپارچه است

                // Video Codec
                '-c:v', 'libx264',
                '-preset', 'veryfast', // سرعت بالا
                '-crf', '26', // کیفیت مناسب (هرچه کمتر، کیفیت بالاتر/حجم بیشتر. ۲۳ تا ۲۸ استاندارد وب)
                '-pix_fmt', 'yuv420p',

                // Audio Codec
                '-c:a', 'aac',
                '-b:a', '128k',
                '-ar', '44100',

                // Fix Timestamps: اگر تایم‌لاین به هم ریخته باشد این‌ها کمک می‌کنند
                '-fflags', '+genpts',

                // Web Optimization
                '-movflags', '+faststart',

                $finalVideoPath
            ];

            $this->runProcess($command);

            // =========================================================
            // مرحله ۳: دریافت اطلاعات و تامنیل
            // =========================================================

            // دریافت مدت زمان
            $duration = $this->getVideoDuration($finalVideoPath);

            // ساخت تامنیل
            // اگر ویدیو خیلی کوتاه بود (زیر ۱ ثانیه)، از فریم اول عکس بگیر
            $seekTime = ($duration > 1) ? '00:00:01.000' : '00:00:00.000';

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

            // =========================================================
            // مرحله ۴: بروزرسانی دیتابیس
            // =========================================================
            $this->video->update([
                'status' => 'completed',
                'path' => "videos/{$uuid}.mp4",
                'thumbnail' => "videos/{$uuid}.jpg",
                'duration' => $duration,
                'size' => File::size($finalVideoPath),
            ]);

        } catch (\Exception $e) {
            // پرتاب مجدد خطا برای اینکه جاب در صف به عنوان failed ثبت شود
            throw $e;
        } finally {
            // پاکسازی فایل‌های موقت
            if (File::exists($tempPath)) {
                File::deleteDirectory($tempPath);
            }
        }
    }

    private function runProcess(array $command)
    {
        // اجرای دستور با تایم‌اوت بالا
        $result = Process::timeout(3600)->run($command);

        if ($result->failed()) {
            throw new \Exception("FFmpeg Error: " . $result->errorOutput());
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
        $this->video->update([
            'status' => 'failed',
        ]);
        log_error($exception);

    }
}
