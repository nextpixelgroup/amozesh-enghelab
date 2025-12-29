<?php

namespace App\Http\Resources;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\QuizCompletion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminVideoDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $course = Course::with([
            'seasons' => function ($query) {
                $query->with(['lessons' => function ($q) {
                    $q->with(['quiz' => function ($q) {
                        $q->with('questions.options');
                    }]);
                }]);
            }
        ])->find($this->course_id);
        $debugLessons = $course->seasons->flatMap(fn ($s) => $s->lessons);

        $userCompletions = collect();
        if ($this->user_id) {
            $userCompletions = QuizCompletion::where('user_id', $this->user_id)
                ->whereIn('quiz_id', $debugLessons->pluck('quiz.id')->filter()->toArray())
                ->get();
        }
// Lookup: quiz_id => (question_id => completion)
        $userAnswersLookup = $userCompletions
            ->whereNotNull('question_id')
            ->groupBy('quiz_id')
            ->map(fn ($quizGroup) => $quizGroup
                ->keyBy('question_id'));

        $quizzes = $debugLessons
            ->pluck('quiz')
            ->filter()
            ->map(function ($quiz) use ($userAnswersLookup) {
                $answersForQuiz = $userAnswersLookup->get($quiz->id, collect());

                return [
                    'id' => $quiz->id,
                    'is_active' => $quiz->is_active,
                    'title' => $quiz->title,
                    'description' => $quiz->description,
                    'questions' => $quiz->questions()->where('is_active', true)->get()->map(function ($question) use ($answersForQuiz) {
                        $completion = $answersForQuiz->get($question->id);

                        return [
                            'id' => $question->id,
                            'question' => $question->question_text,
                            'userSelected' => $completion?->question_option_id,
                            'options' => $question->options->map(fn ($opt) => [
                                'id' => $opt->id,
                                'option' => $opt->option_text,
                                'is_correct' => (bool) $opt->is_correct,
                            ])->toArray(),
                        ];
                    })->toArray(),
                ];
            })
            ->values()
            ->toArray();

        $quizData = null;
        if ($this->quiz) {
            $quizData = [
                'id' => $this->quiz->id,
                'title' => $this->quiz->title,
                'description' => $this->quiz->description,
                'questions' => $this->quiz->questions()->where('is_active', true)->get()->map(function($question) {
                    return [
                        'id' => $question->id,
                        'question' => $question->question_text,
                        'options' => $question->options->map(function ($option) {
                            return [
                                'id' => $option->id,
                                'option' => $option->option_text,
                                'is_correct' => (bool) $option->is_correct, // اطمینان از بولین بودن
                            ];
                        })->toArray(),
                    ];
                })->toArray(),
            ];
        }
        $certificate = Certificate::where('user_id',$this->user_id)->where('course_id',$this->course_id)->first();
        if($certificate){
            $certificate = [
                'number' => $certificate->certificate_number,
            ];
        }
        else{
            $certificate = [];
        }
        $data = [
            'id' => $this->id,
            'user' => [
                'id' => $this->user_id,
                'name' => $this->user->firstname.' '.$this->user->lastname,
                'mobile' => $this->user->mobile,
                'email' => $this->user->email,
            ],
            'video' => [
                'url' => $this->path ? route('admin.video.url',$this->id) : '',
                'poster' => $this->thumbnail ? route('admin.video.poster',$this->id) : '',
                'status' => $this->status,
                'notes' => $this->notes()->orderBy('created_at', 'desc')->get()->map(function ($note) {
                    return [
                        'user' => $note->user->firstname.' '.$note->user->lastname,
                        'note' => $note->note,
                        'created_at' => verta()->instance($note->created_at)->format('Y/m/d H:i'),
                    ];
                })
            ],
            'certificate' => $certificate,
            'finalQuiz' => $quizData,
            'quizzes' => $quizzes,
            'course' => [
                'title' => $this->course->title ?? 'بدون نام',
                'image' => $this->course->thumbnail->url ?? null,
                'link'  => route('admin.courses.edit', $this->course->id ?? 0),
            ]
        ];
        return $data;
    }
}
