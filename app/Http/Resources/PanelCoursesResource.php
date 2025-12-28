<?php

namespace App\Http\Resources;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PanelCoursesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $user = auth()->user();
        $userId = $user->id;
        $lessonCompletions = $this->lessons()
            ->whereHas('completions', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->count();
        $lessonCount = $this->seasons->sum(fn($season) => $season->lessons()->where('is_active',true)->count());

        $students = $this->students()->count();
        $data = [
            'thumbnail' => $this->thumbnail ? $this->thumbnail->url : asset('assets/img/default.svg'),
            'title' => $this->title,
            'category' => $this->categories->count() ? $this->categories->first()->title : '',
            'teacher' => $this->teacher->firstname . ' ' . $this->teacher->lastname,
            'duration' => formatDurationTime($this->duration),
            'students' => $students ? number_format($students) : '0',
            'price' => $this->price > 0 ? number_format($this->price) : 'رایگان',
            'url' => route('web.courses.show',$this->slug),
            'passed' => $user ? $user->hasCompletedCourse($this->resource) : false,
            'progress' => $lessonCount > 0 ? round(($lessonCompletions / $lessonCount) * 100) : 0,
        ];
        return $data;
    }
}
