<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Path extends Model
{
    protected $fillable = ['title', 'description', 'order'];

    public function items()
    {
        return $this->hasMany(PathItem::class);
    }
}
