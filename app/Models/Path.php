<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Path extends Model
{
    protected $fillable = ['title', 'order'];

    public function items()
    {
        return $this->hasMany(PathItem::class);
    }
}
