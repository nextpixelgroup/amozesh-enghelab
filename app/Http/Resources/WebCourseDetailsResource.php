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
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'seasons' => $this->seasons->map(function ($season) {
                return [
                    'id' => $season->id,
                    'title' => $season->title,
                    'lessons' => $season->lessons->map(function ($lesson) {
                        return [
                            'id' => $lesson->id,
                            'title' => $lesson->title,
                            'duration' => $lesson->duration,
                            'video' => $lesson->video?->url,
                            'poster' => $lesson->poster?->url,
                            'completed' => rand(0, 1) ? true : false,
                        ];
                    }),
                ];
            }),
            'stats' => [
                'seasons' => $this->seasons->count(),
                'lessons' => $this->lessons->count(),
                'videos' => /*$this->videos->count()*/1231,
            ],
        ];

    }
}
