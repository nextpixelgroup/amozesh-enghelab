<?php

namespace App\Models;

use App\Enums\VideoStatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    public function statusObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $status = VideoStatusEnum::fromKey($attributes['status'])->value;
                return [
                    'value' => $attributes['status'],
                    'title' => $status,
                ];
            }
        );
    }

    protected function createdAtObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $value = $attributes['created_at'];
                $label = verta()->instance($value)->format('Y/m/d H:i');
                return ['value' => $value, 'title' => $label];
            }
        );
    }
}
