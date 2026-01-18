<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollPath extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'path_id',
        'has_requested_certificate',
    ];

    // ارتباط با کاربر
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ارتباط با دوره
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // ارتباط با مسیر
    public function path()
    {
        return $this->belongsTo(Path::class);
    }

}
