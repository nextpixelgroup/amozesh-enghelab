<?php

namespace App\Models;

use App\Enums\PageStatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isArray;
use function PHPUnit\Framework\isJson;

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
        'thumbnail_id',
        'created_by',
        'updated_by',
        'published_at',
    ];

    protected $casts = [
        'schema' => 'array',
        'published_at' => 'datetime',
    ];

    public function thumbnail()
    {
        return $this->belongsTo(Media::class, 'thumbnail_id');
    }

    public function meta()
    {
        return $this->hasMany(PageMeta::class);
    }

    public function getMetaArrayAttribute(): array
    {

        return $this->meta->mapWithKeys(function ($item) {
            $value = Str::isJson($item->value) ? json_decode($item->value,true): $item->value;
            return [$item->key => $value];
        })->toArray();
    }

    public function updateMeta($key, $value = null)
    {
        $value = is_array($value) ? json_encode($value) : $value;
        $query = $this->meta()->where('key', $key)->first();
        if($query){
            $query->update([
                'value' => $value
            ]);
        }
        else{
            $this->meta()->create([
                'key' => $key,
                'value' => $value
            ]);
        }
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
