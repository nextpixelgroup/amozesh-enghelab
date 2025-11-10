<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\GenderEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'slug',
        'mobile',
        'email',
        'birth_date',
        'gender',
        'national_code',
        'postal_code',
        'address',
        'company',
        'password',
        'avatar_id',
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

    public static function allRoles(){
        return collect([
            [
                'title' => 'مدیر',
                'value' => 'admin',
            ],
            [
                'title' => 'مدیر محتوا',
                'value' => 'content-manager',
            ],
            [
                'title' => 'مدرس',
                'value' => 'teacher',
            ],
            [
                'title' => 'کاربر',
                'value' => 'user',
            ]
        ]);
    }

    public static function assessToAdmin()
    {
        return ['super-admin', 'admin', 'content-manager'];
    }

    public function educationals(): HasMany
    {
        return $this->hasMany(Educational::class);
    }

    public function teacherDetails()
    {
        return $this->hasOne(TeacherDetail::class, 'user_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function avatar()
    {
        return $this->belongsTo(Media::class, 'avatar_id');
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

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function restrictions()
    {
        return $this->hasMany(Restriction::class);
    }

    public function isRestricted(): bool
    {
        return $this->restrictions()->where(function ($query){
            $query->where('expires_at',null)->orWhere('expires_at','>',now());
        })->count() > 0;
    }

    /*public function activeRestriction()
    {
        return $this->restrictions->first(fn ($restriction) => $restriction->isActive());
    }*/

    protected function genderObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $value = $attributes['gender'] ?? '';
                $title = $value ? GenderEnum::fromKey($value)->value : '';
                return ['value' => $value, 'title' => $title];
            }
        );
    }
    protected function roleObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $value = $this->roles()->first()?->name ?? '';
                $role = $this->allRoles()->where('value', $value)->first();
                $value = isset($role['value']) ? $role['value'] : '';
                $title = isset($role['title']) ? $role['title'] : '';
                return ['value' => $value, 'title' => $title];
            }
        );
    }

    protected function birthDateObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $dateExp = explode('-',verta()->instance($attributes['birth_date'])->format('Y-m-d'));
                return [
                    'jalali' => $attributes['birth_date'] ? verta()->instance($attributes['birth_date'])->format('Y/m/d') : null,
                    'gregorian' => $attributes['birth_date'] ? Carbon::parse($attributes['birth_date'])->format('Y/m/d') : null,
                    'object' => [
                        'year'  => $attributes['birth_date'] ? (int)removeFirstZeros($dateExp[0]) : null,
                        'month' => $attributes['birth_date'] ? (int)removeFirstZeros($dateExp[1]) : null,
                        'day'   => $attributes['birth_date'] ? (int)removeFirstZeros($dateExp[2]) : null,
                    ]
                ];
            }
        );
    }

    public function status()
    {
        //dump($this->isRestricted());
        if($this->isRestricted()){
            $data = ['value' => 'ban', 'title' => 'بن شده'];
        }else{
            $data = ['value' => 'active', 'title' => 'فعال'];
        }
        return $data;
    }

}
