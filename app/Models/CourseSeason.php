<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSeason extends Model
{
    protected $fillable = ['course_id', 'title', 'description', 'order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(CourseLesson::class, 'season_id')->orderBy('order');
    }

    public function activeLessons()
    {
        return $this->lessons()->where('is_published', true);
    }
}
