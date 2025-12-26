<?php

namespace App\Models;

use App\Enums\CourseStatusEnum;
use App\Observers\CourseObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Course extends Model
{
    public static function label(): string
    {
        return 'دوره';
    }

    protected $fillable = [
        'user_id', 'title', 'slug', 'summary', 'description', 'thumbnail_id', 'intro_url', 'intro_filename', 'poster_id', 'teacher_id', 'category_id', 'price', 'rate', 'must_complete_quizzes', 'status', 'views', 'published_at', 'duration'
    ];

    protected $casts = [
        'must_complete_quizzes' => 'boolean',
    ];

    public function bookmarks()
    {
        return $this->morphMany(Bookmark::class, 'bookmarkable');
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function paths()
    {
        return $this->belongsToMany(Path::class, 'path_items', 'course_id', 'path_id');
    }

    public function LessonCompletions()
    {
        return $this->hasMany(LessonCompletion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thumbnail()
    {
        return $this->belongsTo(Media::class, 'thumbnail_id');
    }

    public function poster()
    {
        return $this->belongsTo(Media::class, 'poster_id');
    }

    // Relationships
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable')->withTimestamps()->orderBy('updated_at','DESC');
    }

    public function seasons()
    {
        return $this->hasMany(CourseSeason::class)->orderBy('order');
    }

    public function lessons()
    {
        return $this->hasManyThrough(
            CourseLesson::class,       // مدل مقصد
            CourseSeason::class,       // مدل واسط
            'course_id',               // foreignKey در CourseSeason → اشاره به Course
            'season_id',               // foreignKey در CourseLesson → اشاره به Season
            'id',                      // localKey در Course
            'id'                       // localKey در CourseSeason
        );
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


    public function students()
    {
        return $this->belongsToMany(User::class, 'course_students')
            ->withPivot(['enrolled_at', 'completed_at', 'has_passed', 'progress'])
            ->withTimestamps();
    }

    public function ratings()
    {
        return $this->hasMany(CourseRating::class);
    }


    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    public function quizCompletions()
    {
        return $this->hasMany(QuizCompletion::class);
    }

    public function requirements()
    {
        return $this->belongsToMany(Course::class, 'course_requirements', 'course_id', 'requirement_id');

    }

    /**
     * The courses that require this course.
     */
    public function requiredBy()
    {
        return $this->belongsToMany(Course::class, 'course_requirements', 'requirement_id', 'course_id')
            ->using(CourseRequirement::class)
            ->withTimestamps();
    }

    public function getEnrolledStudentsCountAttribute()
    {
        return $this->students()->count();
    }

    public function updateAverageRating()
    {
        $this->rate = $this->ratings()->avg('rate');
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

    public function statusObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $status = CourseStatusEnum::fromKey($attributes['status'])->value;
                return [
                    'value' => $attributes['status'],
                    'title' => $status,
                ];
            }
        );
    }

    protected function publishedAtObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $value = $attributes['published_at'] ?? '';
                $title = verta()->instance($value)->format('Y/m/d H:i');
                return ['value' => $value, 'title' => $title];
            }
        );
    }

    protected function createdAtObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $value = $attributes['created_at'] ?? '';
                $title = verta()->instance($value)->format('Y/m/d H:i');
                return ['value' => $value, 'title' => $title];
            }
        );
    }

    protected function updatedAtObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $value = $attributes['updated_at'] ?? '';
                $title = verta()->instance($value)->format('Y/m/d H:i');
                return ['value' => $value, 'title' => $title];
            }
        );
    }
}
