<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'slug', 'icon', 'description', 'type'];

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

    protected function createdAtObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $value = $attributes['created_at'];
                $label = verta()->instance($value)->format('Y/m/d H:i');
                return ['value' => $value, 'title' => $label];
            }
        );
    }

}
