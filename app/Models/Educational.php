<?php

namespace App\Models;

use App\Enums\DegreeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Educational extends Model
{
    protected $fillable = [
        'user_id',
        'university', // نام موسسه یا دانشگاه
        'field_of_study', // رشته تحصیلی
        'degree',
        'start_date',
        'end_date',
        'city',
        'is_studying', // درحال تحصیل
        'description' // توضیحات
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_studying' => 'boolean',
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

    protected function startAtObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $dateExp = explode('-',verta()->instance($attributes['start_date'])->format('Y-m-d'));
                return [
                    'jalali' => $attributes['start_date'] ? verta()->instance($attributes['start_date'])->format('Y/m/d') : null,
                    'gregorian' => $attributes['start_date'] ? Carbon::parse($attributes['start_date'])->format('Y/m/d') : null,
                    'object' => [
                        'year'  => $attributes['start_date'] ? (int)removeFirstZeros($dateExp[0]) : null,
                        'month' => $attributes['start_date'] ? (int)removeFirstZeros($dateExp[1]) : null,
                        'day'   => $attributes['start_date'] ? (int)removeFirstZeros($dateExp[2]) : null,
                    ]
                ];
            }
        );
    }

    protected function endAtObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $dateExp = explode('-',verta()->instance($attributes['end_date'])->format('Y-m-d'));
                return [
                    'jalali' => $attributes['end_date'] ? verta()->instance($attributes['end_date'])->format('Y/m/d') : null,
                    'gregorian' => $attributes['end_date'] ? Carbon::parse($attributes['end_date'])->format('Y/m/d') : null,
                    'object' => [
                        'year'  => $attributes['end_date'] ? (int)removeFirstZeros($dateExp[0]) : null,
                        'month' => $attributes['end_date'] ? (int)removeFirstZeros($dateExp[1]) : null,
                        'day'   => $attributes['end_date'] ? (int)removeFirstZeros($dateExp[2]) : null,
                    ]
                ];
            }
        );
    }
}
