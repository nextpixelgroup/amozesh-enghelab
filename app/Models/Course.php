<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Course extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'requirements', 'thumbnail', 'teacher_id', 'category_id', 'price', 'rate', 'must_complete_quizzes', 'status', 'published_at'
    ];

    protected $casts = [
        'must_complete_quizzes' => 'boolean',
    ];

    public function thumbnail()
    {
        return $this->belongsTo(Media::class, 'thumbnail_id');
    }

    // Relationships
    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function seasons()
    {
        return $this->hasMany(CourseSeason::class)->orderBy('order');
    }

    /**
     * Get all comments for the course.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->whereNull('parent_id')
            ->with('replies.user')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get all comments including replies for the course.
     */
    public function allComments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->with('replies.user')
            ->orderBy('created_at', 'desc');
    }

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_student')
            ->withPivot(['enrolled_at', 'completed_at', 'has_passed', 'progress'])
            ->withTimestamps();
    }

    public function ratings()
    {
        return $this->hasMany(CourseRating::class);
    }


    public function quizzes()
    {
        return $this->morphMany(Quiz::class, 'quizzable');
    }

    public function getEnrolledStudentsCountAttribute()
    {
        return $this->students()->count();
    }

    public function updateAverageRating()
    {
        $this->rate = $this->ratings()->avg('rating');
        $this->save();
    }

    // Check if course is currently active
    public function getIsRunningAttribute()
    {
        $now = now();
        return $this->is_active
            && (!$this->starts_at || $this->starts_at->lte($now))
            && (!$this->ends_at || $this->ends_at->gte($now));
    }

    // Get formatted price
    public function getFormattedPriceAttribute()
    {
        if ($this->is_free) {
            return 'رایگان';
        }
        return number_format($this->price) . ' تومان';
    }
}
