<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseCreateRequest;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Verta;

class CourseController extends Controller
{
    /**
     * Make sure the generated slug is unique
     *
     * @param string $slug
     * @param int $count
     * @return string
     */
    private function makeSlugUnique(string $slug, int $count = 0): string
    {
        $newSlug = $count === 0 ? $slug : $slug . '-' . $count;

        if (Course::where('slug', $newSlug)->exists()) {
            return $this->makeSlugUnique($slug, $count + 1);
        }

        return $newSlug;
    }
    public function index()
    {
        return Inertia::render('Admin/Courses/List');
    }

    public function create()
    {
        return Inertia::render('Admin/Courses/Create');
    }

    public function store(CourseCreateRequest $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                // Create course
                $course = $this->storeCourse($request);

                // Process seasons, lessons, quizzes, questions, and options
                $this->processSeasons($course, $request->seasons);

                return sendJson(message: 'دوره با موفقیت ایجاد شد.');
            }
            catch (\Exception $e) {
                return sendJson(status:'error',message: $e->getMessage());
            }
        });
    }

    private function storeCourse($request)
    {
        $slug = $request->slug ?? \Illuminate\Support\Str::slug($request->title);

        return Course::create([
            'title'        => $request->title,
            'slug'         => $this->makeSlugUnique($slug),
            'teacher_id'   => $request->teacher,
            'category_id'  => $request->category,
            'price'        => 0,
            'status'       => $request->status,
            'description'  => $request->description,
            'requirements' => $request->requirements,
            'published_at' => $request->published_at
                ? verta()->parse($request->published_at)->datetime()
                : now(),
            'must_complete_quizzes' => $request->must_complete_quizzes,
        ]);
    }

    private function processSeasons($course, $seasons)
    {
        foreach ($seasons as $seasonData) {
            $season = $course->seasons()->create([
                'title'       => $seasonData['title'],
                'description' => $seasonData['description'] ?? null,
                'is_active'   => $seasonData['is_active'] ?? true,
            ]);

            if (isset($seasonData['lessons']) && is_array($seasonData['lessons'])) {
                $this->processLessons($season, $seasonData['lessons']);
            }
        }
    }

    private function processLessons($season, $lessons)
    {
        foreach ($lessons as $lessonData) {
            $lesson = $season->lessons()->create([
                'title'       => $lessonData['title'],
                'description' => $lessonData['description'] ?? null,
                'is_active'   => $lessonData['is_active'] ?? true,
            ]);

            if (isset($lessonData['quiz']) && is_array($lessonData['quiz'])) {
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
        foreach ($questions as $questionData) {
            $question = $quiz->questions()->create([
                'question_text' => $questionData['question'],
                'type'          => $questionData['type'] ?? 'multiple_option',
                'explanation'   => $questionData['explanation'] ?? null,
            ]);

            if (isset($questionData['options']) && is_array($questionData['options'])) {
                $this->processOptions($question, $questionData['options']);
            }
        }
    }

    private function processOptions($question, $options)
    {
        $optionsToInsert = array_map(function($option) use ($question) {
            return [
                'question_id' => $question->id,
                'option_text' => $option['option'],
                'is_correct'  => $option['is_correct'] ?? false,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }, $options);

        $question->options()->insert($optionsToInsert);
    }
}
