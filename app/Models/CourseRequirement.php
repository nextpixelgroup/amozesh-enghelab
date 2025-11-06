<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseRequirement extends Model
{
    protected $table = 'course_requirements';

    public $incrementing = true;
    public $timestamps = true;

    /**
     * Get the course that owns the requirement.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the requirement that belongs to the course.
     */
    public function requirement()
    {
        return $this->belongsTo(Course::class, 'requirement_id');
    }
}
