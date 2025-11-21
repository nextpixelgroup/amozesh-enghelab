<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'course_id', 'lesson_id', 'title', 'description', 'time_limit', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function quizCompletions()
    {
        return $this->hasMany(QuizCompletion::class);
    }

    /*public function userAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }*/

    public function getMaxScoreAttribute()
    {
        return $this->questions()->sum('points');
    }
}
