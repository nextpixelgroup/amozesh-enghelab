<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CourseStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseCreateRequest;
use App\Http\Resources\AdminCourseDetailsResource;
use App\Http\Resources\AdminCourseResource;
use App\Models\Category;
use App\Models\Course;
use App\Models\Media;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;
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
        return DB::transaction(function () use ($request) {
            try {
                // Create course
                $course = $this->storeCourse($request);

                // Process seasons, lessons, quizzes, questions, and options
                $this->processSeasons($course, $request->seasons);
                if(isset($request->quiz['has_quiz']) && $request->quiz['has_quiz'] == true) {
                    $this->finalQuiz($course, $request->quiz);
                }

                return redirectMessage('success', 'دوره با موفقیت ایجاد شد.',redirect: route('admin.courses.edit',$course->id));
            }
            catch (\Exception $e) {
                return redirectMessage('error',$e->getMessage());
            }
        });
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

    public function search()
    {
        $query = [
            [
                'id' => 1,
                'title' => 'تست',
            ],
            [
                'id' => 2,
                'title' => 'تست دو',
            ]
        ];
        return $query;
    }

    private function storeCourse($request)
    {
        $slug = $request->slug ? createSlug($request->slug) : createSlug($request->title);
        $slug = makeSlugUnique($slug, Course::class);

        $course = Course::create([
            'user_id'      => auth()->user()->id,
            'title'        => $request->title,
            'slug'         => $slug,
            'description'  => $request->description,
            'thumbnail_id' => $request->thumbnail_id,
            'teacher_id'   => $request->teacher,
            'price'        => 0,
            'rate'         => null,
            'must_complete_quizzes' => $request->must_complete_quizzes,
            'status'       => $request->status,
            'published_at' => $request->published_at
                ? verta()->parse($request->published_at)->datetime()
                : now(),
        ]);
        $requirements = $request->requirements ? collect($request->requirements)->pluck('value')->toArray() : [];
        $course->requirements()->sync($requirements);
        $course->categories()->sync($request->category);
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

    private function processLessons($season, $lessons)
    {
        $order = 1;
        foreach ($lessons as $lessonData) {
            $video_id = null;
            $domain = env('FTP_DOMAIN');
            $video_slug = env('COURSE_VIDEO_UPLOAD_SLUG');
            if($lessonData['video_url']){
                $http = env('FTP_SSL') ? 'https://' : 'http://';
                $url = $http.$domain.'/'.$video_slug.$lessonData['video_url'];
                $response = Http::head($url);
                if (!$response->successful()) {
                    return redirectMessage('error', 'چنین ویدیویی یافت نشد');
                }
                else{

                    // Get file info
                    $fileName = basename($lessonData['video_url']);
                    $fileSize = $response->header('Content-Length');
                    $mimeType = $response->header('Content-Type');
                    // Save to media table
                    $filenameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);
                    $media = auth()->user()->media()->create([
                        'file_type' => 'video',
                        'alt'       => $filenameWithoutExt,
                        'file_name' => $fileName,
                        'mime_type' => $mimeType,
                        'ssl'       => true,
                        'domain'    => $domain,
                        'path'      => $video_slug.$lessonData['video_url'],
                        'size'      => $fileSize,
                    ]);

                    $video_id = $media->id;
                }
            }
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
}
