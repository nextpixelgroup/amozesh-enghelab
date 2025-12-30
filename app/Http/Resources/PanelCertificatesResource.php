<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PanelCertificatesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'course' => [
                'thumbnail' => $this->course->thumbnail?->url ?? asset('assets/img/default.svg'),
                'title' => $this->course->title,
            ],
            'certificate_number' => $this->certificate_number,
            'created_at' => $this->createdAtObject,
        ];
    }
}
