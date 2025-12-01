<?php

namespace App\Models;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'user_id',
        'file_type',
        'alt',
        'file_name',
        'mime_type',
        'ssl',
        'domain',
        'path',
        'size',
    ];

    public function url() : Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $ssl = $this->ssl ? 'https://' : 'http://';
                return  $ssl . $this->domain .'/'. $this->path;
            }
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courseThumbnail()
    {
        return $this->hasOne(Course::class, 'thumbnail_id', 'id');
    }

    public function bookThumbnail()
    {
        return $this->hasOne(book::class, 'thumbnail_id', 'id');
    }

    public function lessonPoster()
    {
        return $this->hasOne(CourseLesson::class, 'poster_id', 'id');
    }

    public function videoCourses()
    {
        return $this->hasOne(Course::class, 'video_id', 'id');
    }

    public function pageThumbnail()
    {
        return $this->hasOne(Page::class, 'thumbnail_id', 'id');
    }

    public static function uploadFile(UploadedFile $file, string $type = 'image', string $directory = 'uploads'): ?self
    {
        $user = auth()->user();
        $filenameWithoutExt = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        // ساخت نام فایل ایمن
        $filename = createSlug($filenameWithoutExt) . '_' . time() . '.' . $extension;
        $path = rtrim($directory, '/') . '/' . $filename;

        $localPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $disk = Storage::disk('ftp');

        // متغیر برای تشخیص موفقیت نهایی
        $uploadSuccess = false;

        try {
            // استفاده از استریم (مود باینری rb مهم است)
            $stream = fopen($localPath, 'rb');

            // تلاش برای آپلود
            if ($disk->put($path, $stream)) {
                $uploadSuccess = true;
            }

            if (is_resource($stream)) {
                fclose($stream);
            }

        } catch (\Exception $e) {
            // اگر استریم باز مانده، ببندیمش
            if (isset($stream) && is_resource($stream)) {
                fclose($stream);
            }

            // *** بخش حیاتی برای حل مشکل شما ***
            // اگر خطا مربوط به Timeout بود، بررسی می‌کنیم فایل در مقصد هست یا نه
            $msg = strtolower($e->getMessage());
            if (str_contains($msg, 'timed out') || str_contains($msg, 'time out')) {

                // 1. آیا فایل وجود دارد؟
                // 2. آیا سایز فایل در سرور با سایز فایل اصلی یکی است؟
                try {
                    if ($disk->exists($path)) {
                        $remoteSize = $disk->size($path);

                        // مقایسه سایز (با کمی ارفاق برای تفاوت‌های احتمالی سیستم فایل)
                        if ($remoteSize > 0 && abs($remoteSize - $fileSize) < 1024) {
                            Log::info("FTP Timeout ignored because file uploaded successfully: {$filename}");
                            $uploadSuccess = true; // خطا را نادیده می‌گیریم و موفق فرض می‌کنیم
                        } else {
                            Log::error("FTP Timeout: File exists but size mismatch. Local: {$fileSize}, Remote: {$remoteSize}");
                        }
                    }
                } catch (\Exception $checkEx) {
                    // اگر حتی نتوانست سایز را چک کند، یعنی ارتباط کاملا قطع است
                    Log::error("FTP checking failed: " . $checkEx->getMessage());
                }
            }

            // اگر با روش بالا موفق نشدیم، یعنی خطای واقعی بوده
            if (!$uploadSuccess) {
                throw $e;
            }
        }

        if (!$uploadSuccess) {
            throw new \RuntimeException('Unknown error during FTP upload.');
        }

        // ایجاد رکورد در دیتابیس
        return $user->media()->create([
            'file_type' => $type,
            'alt'       => $filenameWithoutExt,
            'file_name' => $filename,
            'mime_type' => $file->getMimeType(),
            'ssl'       => true,
            'domain'    => env('FTP_DOMAIN'),
            'path'      => $path,
            'size'      => $fileSize,
        ]);
    }

    public function deleteFile()
    {
        try {
            $this->delete();
            Storage::disk('ftp')->delete($this->path);
        } catch (\Exception $e) {
            log_error($e);
            return null;
        }
    }
}
