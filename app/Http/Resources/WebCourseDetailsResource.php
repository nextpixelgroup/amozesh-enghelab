<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebCourseDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $seasons = $this->seasons()
            ->where('is_active', 1)
            ->with([
                'lessons' => function ($q) {
                    $q->where('is_active', 1)->with(['video', 'poster']);
                },
                'lessons.quiz' => function ($q) {
                    $q->where('is_active', 1);
                },
                'lessons.quiz.questions' => function ($q) {
                    $q->where('is_active', 1)->orderBy('order');
                }
            ])
            ->get();

        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'summary' => $this->summary,
            'description' => $this->description,
            'intro' => ['url' => $this->intro?->url, 'file_name' => $this->intro?->file_name],
            'poster' => $this->poster->url ?? '/assets/img/shrine.jpg',
            'teacher' => [
                'name' => $this->teacher->firstname.' '.$this->teacher->lastname,
                'students' => number_format($this->teacher->studentsCount),
                'courses' => number_format($this->teacher->teachingCourses->count()),
                'bio' => $this->teacher->bio,
                'avatar' => $this->teacher->avatar->url ?? '/assets/img/default-teacher.svg',
                'slug' => $this->teacher->slug
            ],
            'published_at' => verta()->instance($this->published_at)->format('l j F Y'),
            'category' => $this->categories?->first()?->title,
            'thumbnail' => $this->thumbnail->url ?? '/assets/img/default.svg',
            'students' => $this->students->count(),
            'must_complete_quizzes' => $this->must_complete_quizzes,
            'seasons' => $seasons->map(function ($season) {
                // چون lessons قبلاً eager load شده‌اند، query جدید زده نمی‌شود
                return [
                    'id' => $season->id,
                    'title' => $season->title,
                    'description' => $season->description,
                    'duration' => formatDurationTime($season->lessons->sum('duration')),
                    'lessons' => $season->lessons->map(function ($lesson) {
                        return [
                            'id' => $lesson->id,
                            'title' => $lesson->title,
                            'description' => $lesson->description,
                            'duration' => formatDurationTime($lesson->duration),
                            'video' => $lesson->video?->url,
                            'download_url' => $lesson->video?->url ? route('web.courses.download.video', $lesson->video?->file_name) : '',
                            'poster' => $lesson->poster?->url,
                            'completed' => $lesson->completions()->exists(),
                            'quiz' => $lesson->quiz ? [
                                'id' => $lesson->quiz->id,
                                'title' => $lesson->quiz->title,
                                'description' => $lesson->quiz->description,
                                'completed' => $quizCompleted = $lesson->quiz->quizCompletions()->exists(),
                                'questions' => $lesson->quiz->questions->map(function ($question) use($quizCompleted) {
                                    return [
                                        'id' => $question->id,
                                        'text' => $question->question_text,
                                        'options' => $question->options->map(function ($option) use($quizCompleted) {
                                            return [
                                                'id' => $option->id,
                                                'text' => $option->option_text,
                                                'selected' => $quizCompleted ? $option->quizCompletions : null
                                            ];
                                        })
                                    ];
                                })
                            ] : []
                        ];
                    }),
                ];
            }),
            'stats' => [
                'seasons' => $seasons->count(),
                'lessons' => $seasons->sum(fn($season) => $season->lessons->count()),
                'duration' => formatDurationTime($seasons->sum(fn($season) => $season->lessons->sum('duration')))
            ],
        ];
        //dd($data);
        return $data;

    }
}
