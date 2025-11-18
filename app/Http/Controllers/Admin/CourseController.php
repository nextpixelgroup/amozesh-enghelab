<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CourseStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseCreateRequest;
use App\Http\Requests\Admin\CourseUpdateRequest;
use App\Http\Resources\AdminCourseDetailsResource;
use App\Http\Resources\AdminCourseResource;
use App\Jobs\UpdateCourseDurationJob;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CourseSeason;
use App\Models\Media;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Verta;

class CourseController extends Controller
{

    public function index(Request $request)
    {

        $query = Course::with(['teacher' => function($query) {$query->with('teacherDetails');}]);
        if($request->filled('status')){
            $query->where('status', $request->status);
        }
        if($request->filled('teacher')){
            $query->where('teacher_id', $request->teacher);
        }
        if($request->filled('category')){
            $query->whereHas('categories', function ($query) use($request) {
                $query->where('id', $request->category);
            });
        }
        if($request->filled('search')){
            $query->where(function ($query) use ($request) {
                $query->where('title', 'like', "%{$request->search}%");
                    //->orWhere('description', 'like', "%{$request->search}%");
            });
        }
        $courses = AdminCourseResource::collection($query->orderBy('id', 'desc')->paginate(config('app.per_page')));
        $status = enumFormated(CourseStatusEnum::cases());
        $teachers = User::whereHas('roles', function ($query) {
            $query->where('name', 'teacher');
        })->get()->map(function ($teacher) {
            return [
                'title' => $teacher->firstname.' '.$teacher->lastname,
                'value' => (string)$teacher->id,
            ];
        });
        $categories = Category::where('type', 'course')->get()->map(function ($category) {
            return [
                'title' => $category->title,
                'value' => (String)$category->id,
            ];
        });
        return Inertia::render('Admin/Courses/List', compact('courses', 'status', 'teachers', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('type', 'course')->get()->map(fn ($item) => ['value' => $item->id, 'title' => $item->title]);
        $teachers = User::query()
            ->whereHas('roles', function($query) {
                $query->where('name', 'teacher');
            })
            ->with('roles')->get()->map(fn ($item) => ['value' => $item->id, 'title' => $item->firstname . ' ' . $item->lastname]);
        $status = enumFormated(CourseStatusEnum::cases());
        $courses = Course::query()->get()->map(fn ($item) => ['value' => $item->id, 'title' => $item->title]);

        $video_upload_slug = video_upload_path();
        return Inertia::render('Admin/Courses/Create', compact('categories', 'teachers', 'status', 'courses', 'video_upload_slug'));
    }

    public function store(CourseCreateRequest $request)
    {
        try {
            // First handle the video upload outside of transaction
            $introId = null;
            if ($request->intro_url) {
                $introId = $this->addVideoToMedia($request->intro_url);
                if (is_a($introId, '\Illuminate\Http\RedirectResponse')) {
                    return $introId; // Return the error response if video upload fails
                }
            }

            DB::beginTransaction();

            try {
                // Create course with the already uploaded video ID
                $course = $this->storeCourse($request, $introId);

                // Process seasons, lessons, quizzes, questions, and options
                $this->processSeasons($course, $request->seasons);

                if(isset($request->quiz['has_quiz']) && $request->quiz['has_quiz'] == true) {
                    $this->finalQuiz($course, $request->quiz);
                }

                UpdateCourseDurationJob::dispatch($course->id);

                DB::commit();

                return redirectMessage('success', 'دوره با موفقیت ایجاد شد.', redirect: route('admin.courses.edit', $course->id));

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e; // Re-throw to be caught by outer catch
            }

        } catch (\Exception $e) {
            $error = log_error($e);
            return redirectMessage('error', $e->getMessage());
        }
    }

    public function edit(Course $course)
    {
        $categories = Category::where('type', 'course')->get()->map(fn ($item) => ['value' => $item->id, 'title' => $item->title]);
        $teachers = User::query()
            ->whereHas('roles', function($query) {
                $query->where('name', 'teacher');
            })
            ->with('roles')->get()->map(fn ($item) => ['value' => $item->id, 'title' => $item->firstname . ' ' . $item->lastname]);
        $status = enumFormated(CourseStatusEnum::cases());
        $courses = Course::query()->get()->map(fn ($item) => ['value' => $item->id, 'title' => $item->title]);

        $video_upload_slug = video_upload_path();
        $course = new AdminCourseDetailsResource($course);

        return inertia('Admin/Courses/Edit', compact('categories', 'teachers', 'status', 'courses', 'video_upload_slug', 'course'));
    }

    public function update(CourseUpdateRequest $request, Course $course)
    {
        try {
            // Handle video upload outside of transaction
            $introId = $course->intro_id;
            if ($request->intro_url && $request->intro_url !== $course->intro?->path) {
                $introId = $this->addVideoToMedia($request->intro_url);
                if (is_a($introId, '\Illuminate\Http\RedirectResponse')) {
                    return $introId; // Return the error response if video upload fails
                }
            }

            DB::beginTransaction();

            try {
                // Update course with the uploaded video ID
                $course = $this->updateCourse($request, $course, $introId);

                // Process seasons, lessons, quizzes, questions, and options
                $this->processUpdateSeasons($course, $request->seasons);

                if(isset($request->quiz['has_quiz'])) {
                    $this->finalUpdateQuiz($course, $request->quiz);
                }

                UpdateCourseDurationJob::dispatch($course->id);

                DB::commit();

                return redirectMessage('success', 'دوره با موفقیت ویرایش شد.', redirect: route('admin.courses.edit', $course->id));

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e; // Re-throw to be caught by outer catch
            }

        } catch (\Exception $e) {
            $error = log_error($e);
            return redirectMessage('error', $e->getMessage());
        }
    }

    private function storeCourse($request, $introId = null)
    {
        $slug = $request->slug ? createSlug($request->slug) : createSlug($request->title);
        $slug = makeSlugUnique($slug, Course::class);

        $args = [
            'user_id'      => auth()->user()->id,
            'title'        => $request->title,
            'slug'         => $slug,
            'description'  => $request->description,
            'thumbnail_id' => $request->thumbnail_id,
            'intro_id'     => $introId,
            'poster_id'    => $request->poster_id,
            'teacher_id'   => $request->teacher,
            'price'        => 0,
            'rate'         => null,
            'must_complete_quizzes' => $request->must_complete_quizzes,
            'status'       => $request->status,
            'published_at' => $request->published_at
                ? verta()->parse($request->published_at)->datetime()
                : now(),
        ];
        $course = Course::create($args);
        $requirements = $request->requirements ? collect($request->requirements)->pluck('value')->toArray() : [];
        $course->requirements()->sync($requirements);
        $course->categories()->sync($request->category);
        return $course;
    }

    private function updateCourse($request, $course, $introId = null)
    {
        $slug = $request->slug;
        if(empty($request->slug)){
            $slug = $course->slug;
        }
        elseif($course->slug !== $request->slug) {
            $slug = $request->slug ? createSlug($request->slug) : createSlug($request->title);
            $slug = makeSlugUnique($slug, Course::class);
        }

        $updateData = [
            'title'                 => $request->title,
            'slug'                  => $slug,
            'description'           => $request->description,
            'thumbnail_id'          => $request->thumbnail_id,
            'teacher_id'            => $request->teacher,
            'must_complete_quizzes' => $request->must_complete_quizzes,
            'status'                => $request->status,
        ];

        // Only update intro_id if a new video was uploaded
        if ($introId !== null) {
            $updateData['intro_id'] = $introId;
        }

        // Update poster_id if provided
        if ($request->has('poster_id')) {
            $updateData['poster_id'] = $request->poster_id;
        }

        $update = $course->update($updateData);
        if($update) {
            $requirements = $request->requirements ? collect($request->requirements)->pluck('value')->toArray() : [];
            $course->requirements()->sync($requirements);
            $course->categories()->sync($request->category);
        }
        return $course;
    }

    private function processSeasons($course, $seasons)
    {
        $order = 1;
        foreach ($seasons as $seasonData) {
            $season = $course->seasons()->create([
                'title'       => $seasonData['title'],
                'description' => $seasonData['description'] ?? null,
                'is_active'   => $seasonData['is_active'] ?? true,
                'order'       => $order,
            ]);
            $order++;
            if (isset($seasonData['lessons']) && is_array($seasonData['lessons'])) {
                $this->processLessons($season, $seasonData['lessons']);
            }
        }
    }
    private function processUpdateSeasons($course, $seasons)
    {
        $order = 1;
        $existingSeasons = $course->seasons()->pluck('id')->toArray();
        $requestSeasonIds = [];
        foreach ($seasons as $seasonData) {
            if(is_numeric($seasonData['id']) && $seasonData['id'] > 0){
                $season = CourseSeason::find($seasonData['id']);
                $season->update([
                    'title' => $seasonData['title'],
                    'description' => $seasonData['description'] ?? null,
                    'is_active' => $seasonData['is_active'] ?? true,
                    'order' => $order,
                ]);

                // اضافه کردن آیدی به لیست فصل‌های درخواست
                $requestSeasonIds[] = $season->id;

                // پردازش دروس این فصل
                if (isset($seasonData['lessons']) && is_array($seasonData['lessons'])) {
                    $this->processUpdateLessons($season, $seasonData['lessons']);
                }
            }
            else {
                $season = $course->seasons()->create([
                    'title' => $seasonData['title'],
                    'description' => $seasonData['description'] ?? null,
                    'is_active' => $seasonData['is_active'] ?? true,
                    'order' => $order,
                ]);
                $requestSeasonIds[] = $season->id;
                if (isset($seasonData['lessons']) && is_array($seasonData['lessons'])) {
                    $this->processUpdateLessons($season, $seasonData['lessons']);
                }
            }
            $order++;

        }
        // حذف فصل‌هایی که در درخواست جدید وجود ندارند
        $seasonsToDelete = array_diff($existingSeasons, $requestSeasonIds);
        if (!empty($seasonsToDelete)) {
            CourseSeason::whereIn('id', $seasonsToDelete)->delete();
        }
    }


    private function processLessons($season, $lessons)
    {
        $order = 1;
        foreach ($lessons as $lessonData) {
            $video_id = $this->addVideoToMedia($lessonData['video_url']);
            $lesson = $season->lessons()->create([
                'title'       => $lessonData['title'],
                'description' => $lessonData['description'] ?? null,
                'video_id'    => $video_id,
                'poster_id'   => $lessonData['poster_id'],
                'duration'    => $lessonData['duration'],
                'order'       => $order,
                'is_active'   => $lessonData['is_active'] ?? true,
            ]);
            $order++;
            if ($lessonData['has_quiz'] == true && isset($lessonData['quiz']) && is_array($lessonData['quiz'])) {
                $this->processQuiz($lesson, $lessonData['quiz']);
            }
        }
    }
    private function processUpdateLessons($season, $lessons)
    {
        $order = 1;
        $existingLessons = $season->lessons()->pluck('id')->toArray();

        $requestLessonIds = [];
        foreach ($lessons as $lessonData) {
            $video_id = $this->addVideoToMedia($lessonData['video_url']);
            if(is_numeric($lessonData['id']) && $lessonData['id'] > 0){
                $requestLessonIds[] = $lessonData['id'];

                $lesson = CourseLesson::where('id',$lessonData['id'])->first();


                $lesson->update([
                    'title' => $lessonData['title'],
                    'description' => $lessonData['description'] ?? null,
                    'video_id' => $video_id,
                    'poster_id' => $lessonData['poster_id'],
                    'duration' => $lessonData['duration'],
                    'order' => $order,
                    'is_active' => $lessonData['is_active'] ?? true,
                ]);

            }
            else {
                $lesson = $season->lessons()->create([
                    'title' => $lessonData['title'],
                    'description' => $lessonData['description'] ?? null,
                    'video_id' => $video_id,
                    'poster_id' => $lessonData['poster_id'],
                    'duration' => $lessonData['duration'],
                    'order' => $order,
                    'is_active' => $lessonData['is_active'] ?? true,
                ]);
                $requestLessonIds[] = $lesson->id;
            }
            $order++;

            if ($lessonData['has_quiz'] == true && isset($lessonData['quiz']) && is_array($lessonData['quiz'])) {
                $this->processUpdateQuiz($lesson, $lessonData['quiz']);
            }

        }
        $lessonsToDelete = array_diff($existingLessons, $requestLessonIds);
        if (!empty($lessonsToDelete)) {
            CourseLesson::whereIn('id', $lessonsToDelete)->delete();
        }
    }

    private function processQuiz($lesson, $quizData)
    {
        $quiz = Quiz::create([
            'lesson_id'   => $lesson->id,
            'title'       => $quizData['title'] ?? 'Quiz',
            'description' => $quizData['description'] ?? null,
            'is_active'   => $quizData['is_active'] ?? true,
        ]);

        if (isset($quizData['questions']) && is_array($quizData['questions'])) {
            $this->processQuestions($quiz, $quizData['questions']);
        }
    }

    private function processUpdateQuiz($lesson, $quizData)
    {
        if(is_numeric($quizData['id']) && $quizData['id'] > 0){
            $quiz = Quiz::find($quizData['id']);
            $quiz->update([
                'lesson_id'   => $lesson->id,
                'title'       => $quizData['title'] ?? 'Quiz',
                'description' => $quizData['description'] ?? null,
                'is_active'   => $quizData['is_active'] ?? true,
            ]);
        }
        else {
            $quiz = Quiz::create([
                'lesson_id' => $lesson->id,
                'title' => $quizData['title'] ?? 'Quiz',
                'description' => $quizData['description'] ?? null,
                'is_active' => $quizData['is_active'] ?? true,
            ]);
        }

        if (isset($quizData['questions']) && is_array($quizData['questions'])) {
            $this->processUpdateQuestions($quiz, $quizData['questions']);
        }
    }

    private function processQuestions($quiz, $questions)
    {
        $order = 1;
        foreach ($questions as $questionData) {
            $question = $quiz->questions()->create([
                'question_text' => $questionData['question'],
                'type'          => $questionData['type'] ?? 'multipleOption',
                'is_active'     => $questionData['is_active'] ?? null,
                'order'         => $order
            ]);
            $order++;
            $this->processOptions($question, $questionData);
        }
    }

    private function processUpdateQuestions($quiz, $questions)
    {
        $order = 1;
        $existingQuestions = $quiz->questions->pluck('id')->toArray();
        $requestQuestionIds = [];
        foreach ($questions as $questionData) {
            $requestQuestionIds[] = $questionData['id'];
            if(is_numeric($questionData['id']) && $questionData['id'] > 0){
                $question = Question::find($questionData['id']);
                $question->update([
                    'question_text' => $questionData['text'],
                    'type'          => $questionData['type'] ?? 'multipleOption',
                    'is_active'     => $questionData['is_active'] ?? null,
                    'order'         => $order
                ]);
            }
            else {
                $question = $quiz->questions()->create([
                    'question_text' => $questionData['text'],
                    'type' => $questionData['type'] ?? 'multipleOption',
                    'is_active' => $questionData['is_active'] ?? null,
                    'order' => $order
                ]);
            }
            $order++;
            $this->processUpdateOptions($question, $questionData);
        }
        $questionsToDelete = array_diff($existingQuestions, $requestQuestionIds);
        if (!empty($questionsToDelete)) {
            Question::whereIn('id', $questionsToDelete)->delete();
        }
    }

    private function processOptions($question, $questionData)
    {
        $options = [
            'option1' => $questionData['option1'] ?? ['text' => '', 'is_correct' => false],
            'option2' => $questionData['option2'] ?? ['text' => '', 'is_correct' => false],
            'option3' => $questionData['option3'] ?? ['text' => '', 'is_correct' => false],
            'option4' => $questionData['option4'] ?? ['text' => '', 'is_correct' => false],
        ];

        $optionsToInsert = array_map(fn($option, $key) => [
            'question_id' => $question->id,
            'option_text' => $option['text'],
            'is_correct'  => $option['is_correct'],
            'created_at'  => now(),
            'updated_at'  => now(),
        ], $options, array_keys($options));

        $question->options()->insert($optionsToInsert);
    }

    private function processUpdateOptions($question, $questionData)
    {
        $options = [
            'option1' => $questionData['option1'] ?? ['text' => '', 'is_correct' => false],
            'option2' => $questionData['option2'] ?? ['text' => '', 'is_correct' => false],
            'option3' => $questionData['option3'] ?? ['text' => '', 'is_correct' => false],
            'option4' => $questionData['option4'] ?? ['text' => '', 'is_correct' => false],
        ];

        if (is_numeric($questionData['id']) && $questionData['id'] > 0) {
            // Get existing options
            $existingOptions = $question->options()->get();

            // Update each option individually
            foreach ($existingOptions as $index => $option) {
                $optionKey = 'option' . ($index + 1);
                if (isset($options[$optionKey])) {
                    $option->update([
                        'option_text' => $options[$optionKey]['text'],
                        'is_correct' => $options[$optionKey]['is_correct'],
                    ]);
                }
            }
        }
        else {
            $optionsToInsert = array_map(fn($option, $key) => [
                'question_id' => $question->id,
                'option_text' => $option['text'],
                'is_correct' => $option['is_correct'],
                'created_at' => now(),
                'updated_at' => now(),
            ], $options, array_keys($options));

            $question->options()->insert($optionsToInsert);
        }
    }

    private function finalQuiz($course,$quizData)
    {
        try {
            $quiz = Quiz::create([
                'course_id'   => $course->id,
                'title'       => $quizData['title'] ?? '',
                'description' => $quizData['description'] ?? '',
                'is_active'   => $quizData['is_active'] ?? false,
            ]);
            if (isset($quizData['questions']) && is_array($quizData['questions'])) {
                $this->processQuestions($quiz, $quizData['questions']);
            }
            return $quiz;
        }
        catch (\Exception $e) {
            return redirectMessage('error',$e->getMessage());
        }
    }

    private function finalUpdateQuiz($course,$quizData)
    {
        try {
            $quiz = Quiz::find($quizData['id']);
            if(is_numeric($quizData['id']) && $quizData['id'] > 0) {
                //dd($quizData);
                $quiz = Quiz::find($quizData['id']);
                $quiz->update([
                    'course_id'   => $course->id,
                    'title'       => $quizData['title'] ?? '',
                    'description' => $quizData['description'] ?? '',
                    'is_active'   => $quizData['is_active'] ?? false,
                ]);
            }
            else{
                $quiz = Quiz::create([
                    'course_id'   => $course->id,
                    'title'       => $quizData['title'] ?? '',
                    'description' => $quizData['description'] ?? '',
                    'is_active'   => $quizData['is_active'] ?? false,
                ]);
            }
            if (isset($quizData['questions']) && is_array($quizData['questions'])) {
                $this->processUpdateQuestions($quiz, $quizData['questions']);
            }
            return $quiz;
        }
        catch (\Exception $e) {
            return redirectMessage('error',$e->getMessage());
        }
    }

    private function addVideoToMedia($videoUrl)
    {
        if (empty($videoUrl)) {
            return null;
        }

        try {
            $domain = env('FTP_DOMAIN');
            $video_slug = env('COURSE_VIDEO_UPLOAD_SLUG');
            $http = env('FTP_SSL') ? 'https://' : 'http://';
            $url = $http . $domain . '/' . $video_slug . ltrim($videoUrl, '/');

            // Verify the video exists and get its details
            $response = Http::timeout(30)->head($url);

            if (!$response->successful()) {
                throw new \Exception("ویدیو یافت نشد: " . $videoUrl);
            }

            $media = Media::where('file_name',$videoUrl)->first();
            if(!$media) {
                // Get file info
                $fileName = basename($videoUrl);
                $fileSize = $response->header('Content-Length', 0);
                $mimeType = $response->header('Content-Type', 'video/mp4');

                // Save to media table
                $filenameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);

                return auth()->user()->media()->create([
                    'file_type' => 'video',
                    'alt' => $filenameWithoutExt,
                    'file_name' => $fileName,
                    'mime_type' => $mimeType,
                    'ssl' => true,
                    'domain' => $domain,
                    'path' => $video_slug . ltrim($videoUrl, '/'),
                    'size' => $fileSize,
                ])->id;
            }
            return $media->id;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }
}
