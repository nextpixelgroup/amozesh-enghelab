<?php

namespace App\Jobs;

use App\Models\Course;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateCourseDurationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $courseId) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $course = Course::find($this->courseId);
        if (!$course) return;

        $course->update([
            'duration' => $course->lessons()->where('course_lessons.is_active', 1)->sum('course_lessons.duration')
        ]);
    }
}
