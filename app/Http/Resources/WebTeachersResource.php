<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebTeachersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title'  => $this->firstname.' '.$this->lastname,
            'avatar' => $this->avatar?->url ?? '/assets/img/default-teacher.svg',
            'degree' => $this->teacherDetails?->degree,
            'url' => route('web.teachers.show',$this->slug),
        ];
    }
}
