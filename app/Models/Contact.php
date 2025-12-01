<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'message',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scope for unread messages
    public function scopeUnread(Builder $query): void
    {
        $query->whereNull('read_at');
    }

    // Mark as read
    public function markAsRead(): bool
    {
        return $this->update(['read_at' => now()]);
    }

    // Check if message is read
    public function isRead(): bool
    {
        return ! is_null($this->read_at);
    }

    protected function readAtObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $value = $attributes['read_at'];
                $label = $value ? verta()->instance($value)->format('Y/m/d H:i') : null;
                return ['value' => $value, 'title' => $label];
            }
        );
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
