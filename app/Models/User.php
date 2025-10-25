<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens,HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'mobile',
        'email',
        'birth_date',
        'gender',
        'national_code',
        'postal_code',
        'address',
        'company',
        'password',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function educationals(): HasMany
    {
        return $this->hasMany(Educational::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketReplies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function teachingCourses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_student')
            ->withPivot(['enrolled_at', 'completed_at', 'has_passed', 'progress'])
            ->withTimestamps();
    }


// متد کمکی برای دریافت آخرین مقطع تحصیلی
    public function getLatestDegreeAttribute()
    {
        return $this->educationals()
            ->orderBy('end_date', 'desc')
            ->orderBy('start_date', 'desc')
            ->first();
    }


    public function completedLessons()
    {
        return $this->belongsToMany(CourseLesson::class, 'lesson_completions')
            ->withTimestamps();
    }

    public function hasCompletedLesson(CourseLesson $lesson)
    {
        return $this->completedLessons()->where('lesson_id', $lesson->id)->exists();
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function hasPassedQuiz(Quiz $quiz)
    {
        return $this->quizAttempts()
            ->where('quiz_id', $quiz->id)
            ->where('passed', true)
            ->exists();
    }

    public function hasCompletedCourse(Course $course)
    {
        return $this->enrolledCourses()
            ->where('course_id', $course->id)
            ->whereNotNull('completed_at')
            ->exists();
    }

    public function ratings()
    {
        return $this->hasMany(CourseRating::class);
    }

    public function hasRatedCourse(Course $course)
    {
        return $this->ratings()
            ->where('course_id', $course->id)
            ->exists();
    }


}
