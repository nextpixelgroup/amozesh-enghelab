<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class AdminCourseDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id'        => $this->id,
            'thumbnail' => [
                'id'    => $this->thumbnail_id ?? null,
                'url'   => $this->thumbnail?->url,
            ],
            'intro_url' => $this->intro_url,
            'intro_filename' => $this->intro_filename,
            'poster' => [
                'id'    => $this->poster_id ?? null,
                'url'   => $this->poster?->url,
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
            'summary' => $this->summary,
            'description' => $this->description,
            'status'      => $this->statusObject,
            'requirements' => $this->requirements->map(function ($requirement) {
                return [
                    'value' => $requirement->id,
                    'title' => $requirement->title,
                ];
            }),
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
            ],
            'seasons' => $this->seasons ? $this->seasons->map(function ($season) {
                return [
                    'id' => $season->id,
                    'title' => $season->title,
                    'description' => $season->description,
                    'is_active' => $season->is_active ? true : false,
                    'lessons' => $season->lessons ? $season->lessons->map(function ($lesson) {
                        return [
                            'id' => $lesson->id,
                            'title' => $lesson->title,
                            'description' => $lesson->description,
                            'is_active' => $lesson->is_active ? true : false,
                            'video_url' => $lesson->video_url,
                            'video_filename' => $lesson->video_filename,
                            'poster_id' => $lesson->poster_id,
                            'poster' => [
                                'id' => $lesson->poster_id,
                                'url' => $lesson->poster?->url,
                            ],
                            'duration' => $lesson->duration,
                            'has_quiz' => $lesson->quiz ? true : false,
                            'quiz' => [
                                'id' => $lesson->quiz ? $lesson->quiz->id : Str::uuid(),
                                'has_quiz' => $lesson->quiz ? true : false,
                                'title' => $lesson->quiz?->title ?? '',
                                'description' => $lesson->quiz?->description ?? '',
                                'is_active' =>  $lesson->quiz?->is_active ? true : false,
                                'questions' => $lesson->quiz?->questions ? $lesson->quiz?->questions->sortBy('order')->values()->map(function ($question) {
                                    return [
                                        'id' => $question->id,
                                        'text' => $question->question_text,
                                        'is_active' => $question->is_active ? true : false,
                                        'option1' => ['text' => $question->options[0]->option_text, 'is_correct' => $question->options[0]->is_correct],
                                        'option2' => ['text' => $question->options[1]->option_text, 'is_correct' => $question->options[1]->is_correct],
                                        'option3' => ['text' => $question->options[2]->option_text, 'is_correct' => $question->options[2]->is_correct],
                                        'option4' => ['text' => $question->options[3]->option_text, 'is_correct' => $question->options[3]->is_correct],
                                    ];
                                }) : [
                                    [
                                        'id' => Str::uuid(),
                                        'text' => '',
                                        'is_active' => true,
                                        'option1' => ['text' => '', 'is_correct' => false],
                                        'option2' => ['text' => '', 'is_correct' => false],
                                        'option3' => ['text' => '', 'is_correct' => false],
                                        'option4' => ['text' => '', 'is_correct' => false],
                                    ]
                                ]
                            ],
                        ];
                    }) : [],
                ];
            }) : [],
            'quiz' => [
                'id' => $this->quiz ? $this->quiz->id : Str::uuid(),
                'has_quiz' => $this->quiz ? true : false,
                'title' => $this->quiz?->title ?? '',
                'description' => $this->quiz?->description ?? '',
                'is_active' =>  $this->quiz?->is_active ?? false,
                'questions' => $this->quiz?->questions ? $this->quiz->questions->sortBy('order')->values()->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'text' => $question->question_text,
                        'is_active' => $question->is_active ? true : false,
                        'option1' => ['text' => $question->options[0]->option_text, 'is_correct' => $question->options[0]->is_correct],
                        'option2' => ['text' => $question->options[1]->option_text, 'is_correct' => $question->options[1]->is_correct],
                        'option3' => ['text' => $question->options[2]->option_text, 'is_correct' => $question->options[2]->is_correct],
                        'option4' => ['text' => $question->options[3]->option_text, 'is_correct' => $question->options[3]->is_correct],
                    ];
                }) : [
                    [
                        'id' => Str::uuid(),
                        'text' => '',
                        'is_active' => true,
                        'option1' => ['text' => '', 'is_correct' => false],
                        'option2' => ['text' => '', 'is_correct' => false],
                        'option3' => ['text' => '', 'is_correct' => false],
                        'option4' => ['text' => '', 'is_correct' => false],
                    ]
                ]
            ],
        ];
        return $data;
    }
}
