<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = ['user_id', 'course_id', 'certificate_number'];

    protected static function booted()
    {
        static::created(function ($certificate) {
            $certificate->updateQuietly([
                'certificate_number' => self::generateNumber($certificate->id),
            ]);
        });
    }

    private static function generateNumber(int $id): int
    {
        $A = 7919; // عدد اول

        // حداقل 6 رقم
        $digits = max(6, strlen((string)$id) + 5);

        $base  = 10 ** ($digits - 1);
        $range = 9 * $base;

        return $base + (($id * $A) % $range);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
