<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationalResource extends JsonResource
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
            'university' => $this?->university,
            'city' => $this?->city,
            'field_of_study' => $this?->field_of_study,
            'degree' => $this?->degree,
            'start_date' => $this?->startAtObject,
            'end_date' => $this?->endAtObject,
            'is_studying' => $this?->is_studying,
            'description' => $this?->description,
        ];
    }
}
