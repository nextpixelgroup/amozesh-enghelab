<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseStudent extends Model
{
    protected $fillable = ['course_id', 'user_id', 'enrolled_at', 'completed_at', 'has_passed', 'progress'];
}
