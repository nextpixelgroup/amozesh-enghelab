<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $attributes = [
        'user_id',
        'type',
        'file_name',
        'mime_type',
        'disk',
        'path',
        'size',
    ];
}
