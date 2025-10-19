<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\CourseRating;
use App\Observers\CourseObserver;
use App\Observers\RatingObserver;
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
        Course::observe(CourseObserver::class);
        CourseRating::observe(RatingObserver::class);

    }
}
