<?php

namespace App\Models;

use Illuminate\Http\UploadedFile;

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

    public static function uploadFile(UploadedFile $file, string $type = 'image', string $directory = 'uploads'): ?self
    {
        try {
            $user = auth()->user();
            $filenameWithoutExt = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = createSlug($filenameWithoutExt) . '_' . time() . '.' . $extension;
            $path = rtrim($directory, '/') . '/' . ltrim($type, '/') . 's/' . $filename;

            // Upload to FTP
            Storage::disk('ftp')->put($path, file_get_contents($file));

            // Create media record
            return $user->media()->create([
                'file_type' => $type,
                'alt'       => $filenameWithoutExt,
                'file_name' => $filename,
                'mime_type' => $file->getMimeType(),
                'ssl'       => true,
                'domain'    => env('FTP_DOMAIN'),
                'path'      => $path,
                'size'      => $file->getSize(),
            ]);
        } catch (\Exception $e) {
            log_error($e);
            return null;
        }
    }
}
