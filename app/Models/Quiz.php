<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'lesson_id', 'title', 'description', 'time_limit', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function userAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function getMaxScoreAttribute()
    {
        return $this->questions()->sum('points');
    }
}
