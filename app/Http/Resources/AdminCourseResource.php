<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'thumbnail' => [
                'id'    => $this->thumbnail_id ?? null,
                'url'   => $this->thumbnail?->url,
            ],
            'title'       => $this->title,
            'slug'        => $this->slug,
            'categories'  => $this->categories->map(function ($category) {
                return [
                    'value' => $category->id,
                    'title' => $category->title,
                ];
            }),
            'must_complete_quizzes' => $this->must_complete_quizzes,
            'description' => $this->description,
            'status'      => $this->statusObject,
            'member'      => $this->students()->count(),
            'published_at' => $this->publishedAtObject,
            'created_at' => $this->createdAtObject,
            'updated_at' => $this->updatedAtObject,
            'teacher'   => [
                'id' => $this->teacher?->id,
                'firstname' => $this->teacher?->firstname,
                'lastname'  => $this->teacher?->lastname,
                'fullname'  => $this->teacher?->firstname.' '.$this->teacher?->lastname,
                'degree'    => $this->teacher?->teacherDetails?->degree,
                'academic_title' => $this->teacher?->teacherDetails?->academic_title,
                'teaching' => $this->teacher?->teacherDetails?->teaching,
                'job_title' => $this->teacher?->teacherDetails?->job_title,
                'history' => $this->teacher?->teacherDetails?->history,
                'skills' => $this->teacher?->teacherDetails?->skills,
                'bio' => $this->teacher?->teacherDetails?->bio,
                'avatar' => [
                    'id' => $this->teacher?->avatar_id,
                    'url' => $this->teacher?->avatar?->url,
                ],
            ],
        ];
    }
}
