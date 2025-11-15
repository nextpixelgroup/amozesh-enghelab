<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'icon',
        'url',
        'parent_id',
        'order',
        'is_active',
        'target',
        'type',
        'meta',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'meta' => 'array',
    ];

    // رابطه با زیرمنوها
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    // رابطه با منوی والد
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    // اسکوپ برای فقط منوهای فعال
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // اسکوپ برای منوهای ریشه
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}
