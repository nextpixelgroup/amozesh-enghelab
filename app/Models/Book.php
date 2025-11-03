<?php

namespace App\Models;

use App\Enums\BookStatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'slug',
        'expert',
        'content',
        'price',
        'special_price',
        'publisher',
        'year_published',
        'size',
        'edition',
        'author',
        'is_stock',
        'stock',
        'max_order',
        'status',
        'thumbnail_id',
    ];

    protected $casts = [
        'price' => 'decimal:0',
        'max_order' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function thumbnail()
    {
        return $this->belongsTo(Media::class, 'thumbnail_id');
    }

    /**
     * Get all categories for the book.
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    /**
     * Get all comments for the book.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->whereNull('parent_id')
            ->with('replies.user')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get all comments including replies for the book.
     */
    public function allComments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->with('replies.user')
            ->orderBy('created_at', 'desc');
    }

    public function statusObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $status = BookStatusEnum::fromKey($attributes['status'])->value;
                return [
                    'value' => $attributes['status'],
                    'title' => $status,
                ];
            }
        );
    }
}
