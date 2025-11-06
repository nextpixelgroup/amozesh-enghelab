<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{

    protected $fillable = [
        'season_id', 'title', 'description', 'video_id', 'poster_id', 'duration', 'order', 'is_published'
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function season()
    {
        return $this->belongsTo(CourseSeason::class);
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class, 'lesson_id');
    }

    public function course()
    {
        return $this->season->course();
    }

    public function getIsUnlockedAttribute()
    {
        // Check if previous lessons are completed
        $previousLesson = $this->season->lessons()
            ->where('order', '<', $this->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previousLesson) {
            // Check if previous lesson is completed
            return auth()->user()->hasCompletedLesson($previousLesson);
        }

        return true;
    }
}
