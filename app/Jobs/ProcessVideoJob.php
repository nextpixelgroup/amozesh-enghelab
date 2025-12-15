<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Video;

class ProcessVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $videoId;
    protected $chunkPath;

    public function __construct($videoId, $chunkPath)
    {
        $this->videoId = $videoId;
        $this->chunkPath = $chunkPath;
    }

    public function handle()
    {
        // مسیر فیزیکی پوشه‌ها
        $chunkDir = storage_path("app/{$this->chunkPath}");
        $finalPath = storage_path("app/public/videos/{$this->videoId}.mp4"); // خروجی نهایی
        $tempRawPath = storage_path("app/temp/{$this->videoId}.webm"); // فایل خام ادغام شده

        // ساخت پوشه‌ها اگر نیستند
        if (!file_exists(dirname($tempRawPath))) mkdir(dirname($tempRawPath), 0777, true);
        if (!file_exists(dirname($finalPath))) mkdir(dirname($finalPath), 0777, true);

        // 1. ادغام تکه‌ها (Merge)
        $files = glob("{$chunkDir}/*.tmp");
        sort($files); // مطمئن می‌شویم ترتیب 0, 1, 2 رعایت شود

        $outputHandle = fopen($tempRawPath, 'wb');
        foreach ($files as $file) {
            $chunkHandle = fopen($file, 'rb');
            while (!feof($chunkHandle)) {
                fwrite($outputHandle, fread($chunkHandle, 1024 * 1024));
            }
            fclose($chunkHandle);
            unlink($file); // حذف تکه
        }
        fclose($outputHandle);
        rmdir($chunkDir); // حذف پوشه خالی شده

        // 2. تبدیل با FFmpeg (اختیاری ولی توصیه شده برای سازگاری موبایل)
        // اگر FFMpeg روی سرور نصب نیست، می‌توانید فایل webm را نگه دارید
        // اما mp4 استانداردتر است.

        $command = "ffmpeg -y -i {$tempRawPath} -c:v libx264 -preset fast -crf 28 -c:a aac -b:a 128k {$finalPath} 2>&1";
        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            // موفقیت
            unlink($tempRawPath); // حذف فایل خام

            Video::where('id', $this->videoId)->update([
                'status' => 'completed',
                'path' => "videos/{$this->videoId}.mp4"
            ]);
        } else {
            // شکست
            Video::where('id', $this->videoId)->update(['status' => 'failed']);
            \Log::error("FFmpeg failed for {$this->videoId}", $output);
        }
    }
}
