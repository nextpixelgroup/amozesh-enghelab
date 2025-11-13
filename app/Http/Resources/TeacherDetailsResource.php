<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this?->id,
            'degree'    => $this?->degree,
            'academic_title' => $this?->academic_title,
            'teaching' => $this?->teaching,
            'job_title' => $this?->job_title,
            'history' => $this?->history,
            'skills' => $this?->skills,
            'bio' => $this?->bio,
            'image'         => [
                'id' => $this->image_id ?? null,
                'url' => $this->image?->url ?? null,
            ]
        ];
    }
}
