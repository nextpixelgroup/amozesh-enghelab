<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    protected $fillable = [
        'body',
        'name',
        'email',
        'is_approved',
        'user_id',
        'parent_id',
        'depth',
        'commentable_id',
        'commentable_type'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    /**
     * Get the parent commentable model (Course or Book).
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owns the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Get the parent comment that this comment is a reply to.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get all replies for this comment.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('replies');
    }

    /**
     * Check if the comment has replies.
     */
    public function hasReplies(): bool
    {
        return $this->replies()->exists();
    }

    /**
     * Scope a query to only include approved comments.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope a query to only include root level comments (not replies).
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Add a reply to the comment.
     */
    public function addReply(array $data, User $user): Comment
    {
        if ($this->depth >= 1) {
            throw new \Exception('حداکثر پاسخ به نظرات یک عمق می‌باشد');
        }

        return $this->replies()->create([
            'body' => $data['body'],
            'user_id' => $user->id,
            'commentable_id' => $this->commentable_id,
            'commentable_type' => $this->commentable_type,
            'parent_id' => $this->id,
            'depth' => $this->depth + 1,
            'is_approved' => true
        ]);
    }
}
