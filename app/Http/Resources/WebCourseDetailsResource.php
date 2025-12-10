<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebCourseDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userId = auth()->id();

        $seasons = $this->seasons()
            ->where('is_active', 1)
            ->with([
                'lessons' => function ($q) use ($userId) {
                    $q->where('is_active', 1)
                        ->orderBy('order') // ترتیب درس‌ها مهم است
                        ->with(['poster']);
                },
                'lessons.quiz' => function ($q) use ($userId) {
                    $q->where('is_active', 1)
                        ->with(['questions' => function($q) {
                            $q->where('is_active', 1)->orderBy('order');
                        }]);
                },
                // بارگذاری وضعیت پاس شدن آزمون توسط کاربر جاری
                'lessons.quiz.quizCompletions' => function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                }
            ])
            ->get();
        $lessonCompletions = $this->lessonCompletions->count();
        $lessonCount = $seasons->sum(fn($season) => $season->lessons->count());
        // 2. متغیرهای زنجیره‌ای برای کنترل قفل بودن دروس
        // چون map اسکوپ جدید می‌سازد، این‌ها را باید با use (&...) پاس بدهیم
        $canShowNextVideo = true;
        $lockedReasonMessage = null;
        $processedSeasons = $seasons->map(function ($season) use (&$canShowNextVideo, &$lockedReasonMessage) {

            $processedLessons = $season->lessons->map(function ($lesson) use (&$canShowNextVideo, &$lockedReasonMessage) {

                // وضعیت پاس شدن آزمون همین درس
                // چون eager load کردیم، نیازی به exists() نیست، چک می‌کنیم کالکشن خالی نباشد
                $quizCompleted = $lesson->quiz && $lesson->quiz->quizCompletions->isNotEmpty();

                // وضعیت نمایش این درس بر اساس وضعیت درس‌های قبلی تعیین می‌شود
                $currentLessonCanShow = $canShowNextVideo;
                $currentLockedReason = $lockedReasonMessage;

                // حالا وضعیت را برای درس **بعدی** (در هر فصلی که باشد) آپدیت می‌کنیم
                // اگر زنجیره هنوز باز است، چک می‌کنیم آیا این درس آن را می‌بندد؟
                if ($canShowNextVideo) {
                    // اگر درس آزمون دارد ولی پاس نشده
                    if ($lesson->quiz && !$quizCompleted) {
                        $canShowNextVideo = false; // درس‌های بعدی قفل شوند
                        $lockedReasonMessage = "برای مشاهده درس‌های بعدی باید آزمون درس «{$lesson->title}» را کامل کرده باشید.";
                    }
                }
                $quiz = $lesson->quiz;
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'description' => $lesson->description,
                    'duration' => formatDurationTime($lesson->duration),
                    'video' => $lesson->video_url,
                    'download_url' => $lesson->video_url
                        ? route('web.courses.download.video', $lesson->video_filename)
                        : '',
                    'poster' => $lesson->poster?->url,
                    'completed' => $lesson->completions()->exists(), // اگر برای این هم relation دارید بهتر است eager load شود
                    // مقادیر محاسبه شده
                    'can_show_video' => $currentLessonCanShow,
                    'locked_reason' => $currentLessonCanShow ? null : $currentLockedReason,

                    'quiz' => $quiz ? [
                        'id' => $quiz->id,
                        'title' => $quiz->title,
                        'description' => $quiz->description,
                        'completed' => $quizCompleted,
                        'questions' => $quiz->questions->map(fn($question) => [
                            'id' => $question->id,
                            'text' => $question->question_text,
                            'options' => $question->options->map(function($option) use($quiz,$quizCompleted) {
                                return [
                                    'id' => $option->id,
                                    'text' => $option->option_text,
                                    'selected' => $quizCompleted ? in_array($option->id,$quiz->quizCompletions->pluck('question_option_id')->toArray()) : false,
                                ];
                            }),
                        ]),
                    ] : [],
                ];
            });

            return [
                'id' => $season->id,
                'title' => $season->title,
                'description' => $season->description,
                'duration' => formatDurationTime($season->lessons->sum('duration')),
                'lessons' => $processedLessons,
            ];
        });
        $data = [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'summary' => $this->summary,
            'description' => $this->description,
            'rate' => number_format($this->rate,1),
            'user_rate' => $userId ? $this->ratings()->where('user_id',$userId)->first()?->rate : null,
            'intro' => ['url' => $this->intro_url, 'file_name' => $this->intro_filename],
            'poster' => $this->poster->url ?? '/assets/img/site/books-pattern.png',
            'teacher' => [
                'name' => $this->teacher->firstname.' '.$this->teacher->lastname,
                'students' => number_format($this->teacher->studentsCount),
                'courses' => number_format($this->teacher->teachingCourses->count()),
                'bio' => $this->teacher->bio,
                'avatar' => $this->teacher->avatar->url ?? '/assets/img/default-teacher.svg',
                'slug' => $this->teacher->slug,
                'url' => $this->teacher->slug ? route('web.teachers.show',$this->teacher->slug) : '#',
            ],
            'published_at' => verta()->instance($this->published_at)->format('l j F Y'),
            'category' => $this->categories?->first()?->title,
            'thumbnail' => $this->thumbnail->url ?? '/assets/img/default.svg',
            'students' => $this->students->count(),
            'must_complete_quizzes' => $this->must_complete_quizzes,
            'seasons' => $processedSeasons,
            'stats' => [
                'seasons' => $seasons->count(),
                'lessons' => $seasons->sum(fn($season) => $season->lessons->count()),
                'duration' => formatDurationTime($seasons->sum(fn($season) => $season->lessons->sum('duration')))
            ],
            'progress' => $lessonCount > 0 ? round(($lessonCompletions / $lessonCount) * 100) : 0
        ];

        //dd($data['seasons']);
        return $data;

    }

    private function canShowLessonVideo($season, $lesson, $index, $quizCompletions, $allSeasons): bool
    {
        // درس اول فصل اول آزاد است
        if ($index === 0 && $season->id === $allSeasons->first()->id) {
            return true;
        }

        // درس اول فصل‌های بعدی باید فصل قبل را بررسی کند
        if ($index === 0) {
            // پیدا کردن فصل قبلی
            $prevSeason = $allSeasons->where('id', '<', $season->id)->last();
            if (!$prevSeason || !$prevSeason->lessons->count()) return true;

            $lastLessonPrev = $prevSeason->lessons->last();

            // ✅ اگر فصل قبلی هیچ کوئیز نداشت → آزاد است
            if (!$lastLessonPrev->quiz || !$lastLessonPrev->quiz['id']) {
                return true;
            }

            // ✅ اگر کوئیز آخر فصل قبل گذرانده شده → آزاد است
            if (in_array($lastLessonPrev->quiz['id'], $quizCompletions)) {
                return true;
            }

            // ❌ در غیر این صورت قفل
            return false;
        }

        // درس‌های بعدی همان فصل
        $previousLesson = $season->lessons[$index - 1];

        // ✅ اگر درس قبلی کوئیز ندارد → آزاد است
        if (!$previousLesson->quiz || !$previousLesson->quiz['id']) {
            return true;
        }

        // ✅ اگر کوئیز درس قبلی گذرانده شده → آزاد است
        if (in_array($previousLesson->quiz['id'], $quizCompletions)) {
            return true;
        }

        // ❌ در غیر این صورت قفل
        return false;
    }

    private function lockedReason($season, $lesson, $index, $quizCompletions, $allSeasons): ?string
    {
        if ($this->canShowLessonVideo($season, $lesson, $index, $quizCompletions, $allSeasons))
            return null;

        if ($index === 0) {
            $prevSeason = $allSeasons->where('id', '<', $season->id)->last();
            $prevLessonTitle = $prevSeason?->lessons?->last()?->title ?? 'درس فصل قبل';
            return "برای مشاهده این درس باید آزمون درس قبلی («{$prevLessonTitle}») را کامل کرده باشید.";
        }

        $prevLessonTitle = $season->lessons[$index - 1]->title ?? 'درس قبلی';
        return "برای مشاهده این درس باید آزمون درس قبلی («{$prevLessonTitle}») را کامل کرده باشید.";
    }


}
