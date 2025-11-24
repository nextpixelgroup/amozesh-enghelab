<?php

namespace App\Providers;

use App\Models\BookRating;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CourseRating;
use App\Models\CourseSeason;
use App\Models\Question;
use App\Models\Quiz;
use App\Observers\BookRatingObserver;
use App\Observers\CourseLessonObserver;
use App\Observers\CourseObserver;
use App\Observers\CourseRatingObserver;
use App\Observers\CourseSeasonObserver;
use App\Observers\QuestionObserver;
use App\Observers\QuizObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        BookRating::observe(BookRatingObserver::class);
        CourseLesson::observe(CourseLessonObserver::class);
        CourseRating::observe(CourseRatingObserver::class);
        Course::observe(CourseObserver::class);
        CourseSeason::observe(CourseSeasonObserver::class);
        Question::observe(QuestionObserver::class);
        Quiz::observe(QuizObserver::class);
    }
}
