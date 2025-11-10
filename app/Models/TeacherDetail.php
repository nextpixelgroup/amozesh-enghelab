<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherDetail extends Model
{
    protected $fillable = [
        'academic_title',
        'image_id',
        'teaching',
        'job_title',
        'degree',
        'history',
        'skills',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }
}
