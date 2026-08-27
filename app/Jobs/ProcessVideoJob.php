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
        // تلاش برای افزایش حافظه‌ی اسکریپت؛ اگر سرور اجازه ندهد (مثلاً به دلیل سقف max_memory_limit)
        // فقط هشدار ثبت می‌شود و جاب با حافظه‌ی فعلی ادامه پیدا می‌کند، نه اینکه کل جاب fail شود
        $this->ensureMemoryLimit('1024M');

        $uuid = $this->video->uuid;
        $tempPath = storage_path("app/temp_uploads/{$uuid}");
        $outputDir = storage_path("app/private/videos");

        // مسیر فایل خام ادغام شده (قبل از تبدیل)
        $rawMergedPath = "{$tempPath}/merged_raw_video.tmp";
        // مسیر نهایی MP4
        $finalVideoPath = "{$outputDir}/{$uuid}.mp4";
        $thumbnailPath = "{$outputDir}/{$uuid}.jpg";

        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        try {
            // =========================================================
            // مرحله ۱: ادغام فیزیکی چانک‌ها (به روش کم‌مصرف Stream)
            // =========================================================
            Log::info("Starting merge stream for $uuid. Total chunks: {$this->totalChunks}");

            // باز کردن فایل مقصد برای نوشتن (Append Mode)
            $targetStream = fopen($rawMergedPath, 'ab');

            if ($targetStream === false) {
                throw new \Exception("Could not open target file for writing: $rawMergedPath");
            }

            $missingChunks = [];

            for ($i = 0; $i < $this->totalChunks; $i++) {
                $chunkFile = "{$tempPath}/{$i}.tmp";

                if (!file_exists($chunkFile)) {
                    Log::warning("Chunk missing: $chunkFile");
                    $missingChunks[] = $i;
                    continue;
                }

                $chunkStream = fopen($chunkFile, 'rb');
                if ($chunkStream) {
                    // کپی مستقیم از دیسک به دیسک (بدون بارگذاری در رم)
                    stream_copy_to_stream($chunkStream, $targetStream);
                    fclose($chunkStream);
                } else {
                    Log::warning("Could not open chunk: $chunkFile");
                }
            }

            // اگر هر یک از چانک‌ها موجود نباشد، ادغام ناقص خواهد بود؛ بهتر است همینجا شکست بخورد
            if (!empty($missingChunks)) {
                throw new \Exception(
                    "Some upload chunks are missing: [" . implode(', ', $missingChunks) . "]"
                );
            }

            fclose($targetStream);

            if (!file_exists($rawMergedPath) || filesize($rawMergedPath) < 1024) {
                throw new \Exception("Merged file is empty or too small.");
            }

            Log::info("Merge completed. File size: " . filesize($rawMergedPath));


            // =========================================================
            // مرحله ۲: تبدیل با FFmpeg
            // =========================================================
            // الان ما یک فایل واحد داریم، پس ورودی ffmpeg فایل rawMergedPath است

            $command = [
                'ffmpeg',
                '-i', $rawMergedPath,  // ورودی فایل ادغام شده است

                '-threads', '1',       // بسیار مهم برای سرور شما

                '-c:v', 'libx264',
                '-preset', 'ultrafast',
                '-crf', '28',
                '-pix_fmt', 'yuv420p',

                '-c:a', 'aac',
                '-b:a', '128k',
                '-ac', '2',
                '-ar', '44100',

                '-movflags', '+faststart',
                '-y',
                $finalVideoPath
            ];


            Log::info("Starting FFmpeg conversion for $uuid...");

            $this->runProcess($command);

            // =========================================================
            // مرحله ۳: تامنیل و پایان
            // =========================================================

            $duration = $this->getVideoDuration($finalVideoPath);
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

            Log::info("Video $uuid processed successfully.");

        } catch (\Exception $e) {
            Log::error("Failed video $uuid: " . $e->getMessage());
            throw $e;
        } finally {
            // پاکسازی همه فایل‌های موقت
            if (File::exists($rawMergedPath)) {
                @unlink($rawMergedPath);
            }
            if (File::exists($tempPath)) {
                File::deleteDirectory($tempPath);
            }
        }
    }

    private function runProcess(array $command)
    {
        $process = Process::timeout($this->timeout)->start($command);
        $result = $process->wait();

        if ($result->failed()) {
            $errorMsg = $result->errorOutput();
            if (empty($errorMsg)) {
                $errorMsg = $result->output();
            }
            throw new \Exception("FFmpeg Error: " . $errorMsg);
        }
    }

    private function getVideoDuration($path)
    {
        $command = ['ffprobe', '-v', 'error', '-show_entries', 'format=duration', '-of', 'default=noprint_wrappers=1:nokey=1', $path];
        $result = Process::run($command);
        return $result->successful() && is_numeric(trim($result->output())) ? (float) trim($result->output()) : 0;
    }

    /**
     * اگر memory_limit فعلی کمتر از حد نیاز باشد آن را افزایش می‌دهد.
     * روی سرورهایی که بیلد PHPشان سقف max_memory_limit دارد، ini_set با هشدار شکست می‌خورد و
     * Laravel این هشدار را به ErrorException تبدیل می‌کند؛ پس تلاش را با @ انجام می‌دهیم
     * تا در صورت عدم موفقیت، جاب به جای خطا با حافظه‌ی فعلی ادامه دهد.
     */
    private function ensureMemoryLimit(string $target): void
    {
        $currentRaw = strtolower(trim((string) ini_get('memory_limit')));
        $targetBytes = self::memoryToBytes($target);
        $currentBytes = self::memoryToBytes($currentRaw);

        // نامحدود (-1) یا قابل تحلیل نبودن مقدار فعلی → نیازی/امکانِ تغییر نداریم
        if ($currentBytes === null || $targetBytes === null || $currentBytes >= $targetBytes) {
            return;
        }

        $result = @ini_set('memory_limit', $target);

        if ($result !== false && self::memoryToBytes((string) ini_get('memory_limit')) >= $targetBytes) {
            Log::info("Memory limit raised from {$currentRaw} to {$target}.");
        } else {
            Log::warning(
                "Could not raise memory_limit from {$currentRaw} to {$target}; continuing with current limit."
            );
        }
    }

    /**
     * تبدیل مقدار shorthand مثل 512M یا 1024M به بایت. -1 (نامحدود) یا مقادیر نامعتبر → null
     */
    private static function memoryToBytes(string $value): ?int
    {
        $value = strtolower(trim($value));

        if ($value === '' || $value === '-1') {
            return null;
        }

        if (!preg_match('/^(\d+(?:\.\d+)?)\s*(k|m|g)?$/', $value, $matches)) {
            return null;
        }

        return (int) ((float) $matches[1] * match ($matches[2] ?? '') {
            'k' => 1024,
            'm' => 1024 ** 2,
            'g' => 1024 ** 3,
            default => 1,
        });
    }

    public function failed(Throwable $exception)
    {
        $this->video->update(['status' => 'failed']);
        Log::error("Job Failed Trace: " . $exception);
    }
}
