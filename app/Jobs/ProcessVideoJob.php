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

class ProcessVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;
    public $totalChunks;
    public $timeout = 3600; // افزایش تایم‌اوت برای ویدیوهای طولانی

    public function __construct(Video $video, $totalChunks)
    {
        $this->video = $video;
        $this->totalChunks = $totalChunks;
    }

    public function handle()
    {
        $uuid = $this->video->id;
        $tempPath = storage_path("app/temp_uploads/{$uuid}");
        $outputDir = storage_path("app/public/videos");
        $finalVideoPath = "{$outputDir}/{$uuid}.mp4";
        $thumbnailPath = "{$outputDir}/{$uuid}.jpg";

        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        try {
            // 1. ساخت لیست فایل‌ها
            $listFile = "{$tempPath}/list.txt";
            $fileContent = "";
            $chunksFound = 0;

            // چک کردن چانک‌ها
            for ($i = 0; $i < $this->totalChunks; $i++) {
                $chunkFile = "{$tempPath}/{$i}.tmp";
                if (File::exists($chunkFile)) {
                    $fileContent .= "file '{$chunkFile}'\n";
                    $chunksFound++;
                } else {
                    Log::warning("Chunk missing in merge process: {$chunkFile}");
                }
            }

            if ($chunksFound === 0) {
                throw new \Exception("No chunks found to merge for video {$uuid}");
            }

            File::put($listFile, $fileContent);

            // 2. FFmpeg command for concatenation and conversion
            $command = [
                'ffmpeg',
                '-f', 'concat',
                '-safe', '0',
                '-i', $listFile,
                
                // Input options
                '-fflags', '+genpts',
                
                // Video codec options
                '-c:v', 'libx264',
                '-preset', 'veryfast',
                '-crf', '23',
                '-pix_fmt', 'yuv420p',
                '-r', '30',
                '-vsync', 'cfr',  // Changed from vfr to cfr for better compatibility
                
                // Audio codec options
                '-c:a', 'aac',
                '-b:a', '128k',
                '-ar', '44100',
                '-ac', '2',
                
                // Output options
                '-movflags', '+faststart',
                '-y',  // Overwrite output file if exists
                $finalVideoPath
            ];

            $this->runProcess($command);

            // 3. دریافت مدت زمان واقعی
            $duration = $this->getVideoDuration($finalVideoPath);
            Log::info("Processed Video Duration: {$duration} seconds");

            // 4. ساخت تامنیل هوشمند
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

            // 5. آپدیت نهایی
            $this->video->update([
                'status' => 'completed',
                'path' => "videos/{$uuid}.mp4",
                'thumbnail' => "videos/{$uuid}.jpg",
                'duration' => $duration,
                'size' => File::size($finalVideoPath),
            ]);

        } catch (\Exception $e) {
            Log::error("Video Processing Failed: " . $e->getMessage());
            $this->video->update(['status' => 'failed']);
            throw $e;
        } finally {
            if (File::exists($tempPath)) {
                File::deleteDirectory($tempPath);
            }
        }
    }

    private function runProcess(array $command)
    {
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
}
