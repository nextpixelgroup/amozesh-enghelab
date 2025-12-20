<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use HasFactory, SoftDeletes;

    // 1. غیرفعال کردن Auto Increment چون ID را دستی ست می‌کنیم
    public $incrementing = false;

    // 2. تعیین نوع کلید اصلی به عنوان رشته
    protected $keyType = 'string';

    // 3. فیلدهای قابل پر شدن
    protected $fillable = [
        'id',           // مهم: باید اینجا باشد چون دستی پر می‌شود
        'user_id',
        'course_id',
        'quiz_id',
        'status',
        'path',
        'thumbnail',
        'duration',
        'size'
    ];

    // رابطه با کاربر (اگر نیاز دارید)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
