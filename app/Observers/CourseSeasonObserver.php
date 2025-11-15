<?php

namespace App\Observers;

use App\Models\CourseSeason;

class CourseSeasonObserver
{
    public function deleted(CourseSeason $season): void
    {
        $season->lessons()->delete();
    }
}
