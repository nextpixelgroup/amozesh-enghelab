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
            ->with(['lessons' => function ($q) {
                $q->where('is_active', 1)
                    ->with(['video', 'poster']);
            }])
            ->get();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'summary' => $this->summary,
            'description' => $this->description,
            'intro' => $this->intro?->url,
            'poster' => $this->poster->url ?? '/assets/img/default.svg',
            'teacher' => [
                'name' => $this->teacher->firstname.' '.$this->teacher->lastname,
                'students' => $this->teacher->studentsCount,
                'courses' => $this->teacher->teachingCourses->count(),
                'bio' => $this->teacher->bio,
                'avatar' => $this->teacher->avatar->url ?? '/assets/img/default-teacher.svg',
                'slug' => $this->teacher->slug
            ],
            'published_at' => verta()->instance($this->published_at)->format('l j F Y'),
            'category' => $this->categories->first()->title,
            'thumbnail' => $this->thumbnail->url ?? '/assets/img/default.svg',
            'students' => $this->students->count(),
            'seasons' => $seasons->map(function ($season) {
                // چون lessons قبلاً eager load شده‌اند، query جدید زده نمی‌شود
                return [
                    'id' => $season->id,
                    'title' => $season->title,
                    'duration' => formatDurationTime($season->lessons->sum('duration')),
                    'lessons' => $season->lessons->map(function ($lesson) {
                        return [
                            'id' => $lesson->id,
                            'title' => $lesson->title,
                            'duration' => formatDurationTime($lesson->duration),
                            'video' => $lesson->video?->url,
                            'download_url' => $lesson->video?->url ? route('web.courses.download.video', $lesson->video?->file_name) : '',
                            'poster' => $lesson->poster?->url,
                            'completed' => (bool) rand(0, 1),
                        ];
                    }),
                ];
            }),
            'stats' => [
                'seasons' => $seasons->count(),
                'lessons' => $seasons->sum(fn($season) => $season->lessons->count()),
                'duration' => formatDurationTime(
                    $seasons->sum(fn($season) => $season->lessons->sum('duration'))
                )
            ],
        ];

    }
}
