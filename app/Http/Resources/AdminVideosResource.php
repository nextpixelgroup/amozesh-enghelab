<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminVideosResource extends JsonResource
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
            'status' => $this->statusObject,
            'created_at' => $this->createdAtObject,
            'course' => [
                'id' => $this->course->id,
                'title' => $this->course->title,
            ],
            'quiz' => [
                'id' => $this->quiz->id,
                'title' => $this->quiz->title,
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->firstname || $this->user->lastname ? $this->user->firstname.' '.$this->user->lastname : 'بدون نام',
            ]
        ];
    }
}
