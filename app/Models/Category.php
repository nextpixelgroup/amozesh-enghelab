<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['title', 'slug', 'icon', 'is_active', 'description', 'type'];
    
    /**
     * The possible category types.
     *
     * @var array
     */
    public const TYPES = [
        'course' => 'دوره',
        'blog' => 'مقاله',
        'product' => 'محصول',
        'general' => 'عمومی'
    ];

    public function courses()
    {
        return $this->morphedByMany(Course::class, 'categorizable');
    }
}
