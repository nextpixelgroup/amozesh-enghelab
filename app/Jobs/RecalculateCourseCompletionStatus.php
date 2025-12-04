<?php

namespace App\Jobs;

use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateCourseCompletionStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $courseId;

    /**
     * Create a new job instance.
     *
     * @param int $courseId
     * @return void
     */
    public function __construct(int $courseId)
    {
        $this->courseId = $courseId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $course = Course::find($this->courseId);
        if (!$course) {
            return;
        }
        $activeLessonsCount = $course->seasons()
            ->where('is_active', true)
            ->withCount([
                'lessons' => function($q) {
                    $q->where('is_active', true);
                }
            ])
            ->get()
            ->sum('lessons_count');

        if ($activeLessonsCount === 0) {
            return;
        }

        // Get all course students with their completion status
        $course->students()
            ->withCount([
                'lessonCompletions' => function($query) use ($course) {
                    $query->whereHas('lesson', function($q) use ($course) {
                        $q->whereHas('season', fn($s) => $s->where('course_id', $course->id))
                            ->where('is_active', true);
                    });
                }
            ])
            ->chunk(500, function($studentsChunk) use ($course, $activeLessonsCount) {

                foreach ($studentsChunk as $student) {
                    if ($student->lesson_completions_count >= $activeLessonsCount) {
                        continue;
                    }

                    if ($student->pivot->has_passed || $student->pivot->completed_at) {
                        $course->students()->updateExistingPivot($student->id, [
                            'has_passed' => false,
                            'completed_at' => null,
                            'progress' => 0
                        ]);
                    }
                }
            });
    }
}
