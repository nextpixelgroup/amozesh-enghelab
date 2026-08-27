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
            //'must_complete_quizzes' => $this->must_complete_quizzes,
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
                            //'has_quiz' => $lesson->quiz ? true : false,
                            'quiz' => $lesson->quiz ? [
                                'id' => $lesson->quiz ? $lesson->quiz->id : Str::uuid(),
                                'has_quiz' => $lesson->quiz ? true : false,
                                'title' => $lesson->quiz?->title ?? '',
                                'description' => $lesson->quiz?->description ?? '',
                                'is_active' =>  $lesson->quiz?->is_active ? true : false,
                                'questions' => $lesson->quiz?->questions->sortBy('order')->values()->map(function ($question) {
                                    return $this->formatQuestion($question);
                                })
                            ] :  [ // ساختار خالی برای وقتی آزمون ندارد

                                'id' => Str::uuid(),
                                'is_active' => false,
                                'title' => '',
                                'description' => '',
                                'questions' => []
                            ]
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
                    return $this->formatQuestion($question);
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

    /**
     * Format a question while tolerating incomplete option data.
     *
     * Questions created before the four-option validation was introduced may
     * have fewer than four options. Missing options are returned as empty
     * values so the admin edit form can still render and repair the question.
     */
    private function formatQuestion($question): array
    {
        $options = $question->options
            ->sortBy('order')
            ->values();

        $formattedOptions = [];

        for ($index = 0; $index < 4; $index++) {
            $option = $options->get($index);

            $formattedOptions['option'.($index + 1)] = [
                'text' => $option?->option_text ?? '',
                'is_correct' => (bool) ($option?->is_correct ?? false),
            ];
        }

        return [
            'id' => $question->id,
            'text' => $question->question_text,
            'is_active' => $question->is_active ? true : false,
            ...$formattedOptions,
        ];
    }
}
