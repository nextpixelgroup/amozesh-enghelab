<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageMeta extends Model
{
    protected $table = 'page_meta';

    protected $fillable = [
        'page_id',
        'key',
        'value',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
