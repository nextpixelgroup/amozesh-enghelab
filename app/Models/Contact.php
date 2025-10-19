<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'subject',
        'message',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
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
}
