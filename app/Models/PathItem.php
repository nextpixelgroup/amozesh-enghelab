<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathItem extends Model
{
    protected $fillable = ['path_id', 'course_id', 'order'];

    public function path()
    {
        return $this->belongsTo(Path::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
