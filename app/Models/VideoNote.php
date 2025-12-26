<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoNote extends Model
{
    protected $fillable = ['user_id', 'video_id', 'note'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function video(){
        return $this->belongsTo(Video::class);
    }
}
