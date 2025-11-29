<?php

namespace App\Models;

use App\Enums\PageStatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'type',
        'title',
        'subtitle',
        'summary',
        'content',
        'status',
        'created_by',
        'updated_by',
        'published_at',
    ];

    protected $casts = [
        'schema' => 'array',
        'published_at' => 'datetime',
    ];

    public function meta()
    {
        return $this->hasMany(PageMeta::class);
    }

    public function getMetaArrayAttribute(): array
    {
        return $this->meta->mapWithKeys(function ($item) {
            return [$item->key => $item->value];
        })->toArray();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function statusObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $status = PageStatusEnum::fromKey($attributes['status'])->value;
                return [
                    'value' => $attributes['status'],
                    'title' => $status,
                ];
            }
        );
    }
}
