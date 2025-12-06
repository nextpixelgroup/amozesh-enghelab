<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebTeacherDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'avatar' => $this->avatar?->url ?? '/assets/img/default-teacher.svg',
            'bio' => $this->teacherDetails->bio,
            'academic_title' => $this->teacherDetails->academic_title,
            'degree' => $this->teacherDetails->degree,
            'teaching' => $this->teacherDetails->teaching,
            'history' => $this->teacherDetails->history,
            'skills' => $this->teacherDetails->skills,
        ];
    }
}
