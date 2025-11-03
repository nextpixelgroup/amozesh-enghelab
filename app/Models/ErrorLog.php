<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ErrorLog extends Model
{
    protected $fillable = [
        'code',
        'message',
        'details',
        'file',
        'line',
        'trace',
        'url',
        'method',
        'input',
        'user_id',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'input' => 'array',
        'trace' => 'array',
    ];

    public static function log(\Throwable $exception, array $extra = [])
    {

        $message = $exception->getMessage();
        $maxLength = 255;

        return static::create([
            'code'       => $exception->getCode(),
            'message'    => '',
            'details'    => $exception->getMessage(),
            'file'       => $exception->getFile(),
            'line'       => $exception->getLine(),
            'trace'      => $exception->getTrace(),
            'url'        => Request::fullUrl(),
            'method'     => Request::method(),
            'input'      => array_merge(Request::all(), $extra),
            'user_id'    => Auth::id(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeRecent($query)
    {
        return $query->latest()->take(100);
    }

    public function scopeCritical($query)
    {
        return $query->whereIn('code', [500, 400]);
    }
}
