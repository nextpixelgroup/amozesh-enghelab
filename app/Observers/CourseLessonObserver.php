<?php

namespace App\Observers;

use App\Models\CourseLesson;

class CourseLessonObserver
{
    public function deleted(CourseLesson $lesson): void
    {
        $lesson->quiz()->delete();
    }
}
