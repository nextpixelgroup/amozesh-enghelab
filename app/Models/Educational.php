<?php

namespace App\Models;

use App\Enums\DegreeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Educational extends Model
{
    protected $fillable = [
        'user_id',
        'institution', // نام موسسه یا دانشگاه
        'field_of_study', // رشته تحصیلی
        'degree',
        'start_date',
        'end_date',
        'is_currently_studying', // درحال تحصیل
        'description' // توضیحات
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_currently_studying' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // متد کمکی برای نمایش خوانای مقطع تحصیلی
    public function getDegreeLabelAttribute(): string
    {
        return DegreeEnum::fromKey($this->degree)->value;
    }
}
