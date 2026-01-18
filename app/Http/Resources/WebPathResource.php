<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebPathResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $stateCurrentCourse = false;
        $user = auth()->user();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'enrolled' => $this->enrolled,
            'courses' => $this->items
                ->map(function ($item) use($user,&$stateCurrentCourse) {
                    $isEnrolled = $user ? $user->isEnrolledIn($item->course) : false;
                    $isPassed = $user ? $user->hasCompletedCourse($item->course) : false;

                    $currentCourse = false;
                    if (!$stateCurrentCourse && (!$isEnrolled || !$isPassed)) {
                        $currentCourse = true;
                        $stateCurrentCourse = true;
                    }
                    return $item->course ? [
                        'thumbnail' => $item->course->thumbnail ? $item->course->thumbnail->url : asset('assets/img/default.svg'),
                        'title' => $item->course->title,
                        'category' => $item->course->categories->count() ? $item->course->categories->first()->title : '',
                        'teacher' => $item->course->teacher->firstname . ' ' . $item->course->teacher->lastname,
                        'duration' => formatDurationTime($item->course->duration),
                        'students' => $item->course->students()->count() ? number_format($item->course->students()->count()) : '0',
                        'price' => $item->course->price > 0 ? number_format($item->course->price) : 'رایگان',
                        'url' => route('web.courses.show', $item->course->slug),
                        'passed' => $isPassed,
                        'enrolled' => $isEnrolled,
                        'currentCourse' => $currentCourse
                    ] : null;
                })
                ->filter()
                ->values()
        ];
    }
}
