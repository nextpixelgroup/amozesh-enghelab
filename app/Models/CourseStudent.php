<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseStudent extends Model
{
    protected $fillable = ['course_id', 'user_id', 'enrolled_at', 'has_requested_certificate', 'completed_at', 'has_passed', 'progress'];

    protected $casts = [
        'has_requested_certificate' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
