<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebCoursesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        //dd($this->lessons()->sum('duration'));
        $students = $this->students()->count();
        return [
            'thumbnail' => $this->thumbnail ? $this->thumbnail->url : asset('assets/img/default.svg'),
            'title' => $this->title,
            'category' => $this->categories->count() ? $this->categories->first()->title : '',
            'teacher' => $this->teacher->firstname . ' ' . $this->teacher->lastname,
            'duration' => formatDurationTime($this->duration),
            'students' => $students ? number_format($students) : '0',
            'price' => $this->price > 0 ? number_format($this->price) : 'رایگان',
            'url' => route('web.courses.show',$this->slug)
        ];
    }
}
