<?php

namespace App\Http\Resources;

use App\Models\CourseStudent;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class WebCourseDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = auth()->user();
        $userId = null;
        if($user) {
            $userId = $user->id;
        }

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
                },
            ])
            ->get();
        $lessonCompletions = $userId
            ? $this->lessons()
                ->whereHas('completions', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->count()
            : 0;
        $lessonCount = $seasons->sum(fn($season) => $season->lessons->count());

        // 2. متغیرهای زنجیره‌ای برای کنترل قفل بودن دروس
        // چون map اسکوپ جدید می‌سازد، این‌ها را باید با use (&...) پاس بدهیم
        $canShowNextVideo = true;
        $lockedReasonMessage = null;
        $canShowVideoForEnrolledCourse = false;

        $enrolledCourse = [];
        if($userId){
            $enrolledCourse = CourseStudent::where('user_id',$userId)->where('course_id', $this->id)->first();
            if($enrolledCourse && $enrolledCourse->has_requested_certificate){
                $canShowVideoForEnrolledCourse = true;
            }
        }

        $course = $this->resource;
        $processedSeasons = $seasons->map(function ($season) use ($enrolledCourse,&$canShowVideoForEnrolledCourse,&$canShowNextVideo, &$lockedReasonMessage, $userId, $course) {

            $processedLessons = $season->lessons->map(function ($lesson) use ($enrolledCourse,&$canShowVideoForEnrolledCourse,&$canShowNextVideo, &$lockedReasonMessage, $userId, $course, $season) {

                // وضعیت پاس شدن آزمون همین درس
                // چون eager load کردیم، نیازی به exists() نیست، چک می‌کنیم کالکشن خالی نباشد
                $quizCompleted = $lesson->quiz && $lesson->quiz->quizCompletions->isNotEmpty();
                $courseCompleted = $lesson->completions()->where('user_id', $userId)->exists();

                $currentLessonCanShow = $canShowNextVideo;
                $currentLockedReason = $lockedReasonMessage;


                if($courseCompleted){
                    $canShowNextVideo = true;
                    if ($lesson->quiz && !$quizCompleted) {
                        $canShowNextVideo = false;
                    }
                    $currentLessonCanShow = true;
                }
                else{
                    if($userId && $enrolledCourse && $enrolledCourse->has_requested_certificate) {
                        $canShowNextVideo = false;
                    }
                }


                //$lockedReasonMessage = "برای مشاهده درس‌های بعدی باید آزمون درس «{$lesson->title}» را کامل کرده باشید.";

                $quiz = $lesson->quiz;
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'description' => $lesson->description,
                    'duration' => formatDurationTime($lesson->duration),
                    'video' => $enrolledCourse && $currentLessonCanShow ? $lesson->video_url : '',
                    'download_url' => $enrolledCourse && $currentLessonCanShow && $lesson->video_url
                        ? route('web.courses.download.video', Crypt::encrypt($lesson->video_filename))
                        : '',
                    'poster' => $lesson->poster?->url,
                    'completed' => $userId
                        ? $lesson->completions()->where('user_id', $userId)->exists()
                        : false,
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
            //'must_complete_quizzes' => $this->must_complete_quizzes,
            'seasons' => $processedSeasons,
            'stats' => [
                'seasons' => $seasons->count(),
                'lessons' => $seasons->sum(fn($season) => $season->lessons->count()),
                'duration' => formatDurationTime($seasons->sum(fn($season) => $season->lessons->sum('duration')))
            ],
            'progress' => $lessonCount > 0 ? round(($lessonCompletions / $lessonCount) * 100) : 0,
            'hasCompletedCourse' => $userId ? $user->hasCompletedCourse($this->resource) : false,
            'quiz' => [
                'hasQuiz' => $this->quiz && $this->quiz->is_active,
                //'videoCompleted' => $user ? $this->video()->where('user_id',$user->id)->first() : null,
                'url' => '',
            ],
            'isBookmarked' => $userId ? (bool)$user->bookmarkedCourses()->where('bookmarkable_id', $this->id)->exists() : false,
            'hasRequestedCertificate' => $userId && $enrolledCourse ? $enrolledCourse->has_requested_certificate : null
        ];
        if(
            $userId &&
            $user->hasCompletedCourse($this->resource) &&
            $this->quiz && $this->quiz->is_active &&
            $this->quiz->quizCompletions->isEmpty()
        ){
            $video = Video::where('user_id', $userId)->where('course_id', $this->id)->first();
            if($video){
                $data['quiz']['url'] = route('web.video.record', $video->uuid);
                $data['quiz']['completed'] = !in_array($video->status,['pending', 'rejected']);
            }
        }
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
