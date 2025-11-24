<?php

namespace App\Observers;

use App\Models\CourseRating;

class CourseRatingObserver
{
    /**
     * Handle the CourseRating "created" event.
     */

    public function created(CourseRating $rating)
    {
        $this->updateCourseRating($rating->course);
    }

    public function updated(CourseRating $rating)
    {
        $this->updateCourseRating($rating->course);
    }

    public function deleted(CourseRating $rating)
    {
        $this->updateCourseRating($rating->course);
    }

    protected function updateCourseRating($course)
    {
        $course->updateAverageRating();
    }

    /**
     * Handle the CourseRating "restored" event.
     */
    public function restored(CourseRating $courseRating): void
    {
        //
    }

    /**
     * Handle the CourseRating "force deleted" event.
     */
    public function forceDeleted(CourseRating $courseRating): void
    {
        //
    }
}
