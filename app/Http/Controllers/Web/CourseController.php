<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebCommentResource;
use App\Http\Resources\WebCourseDetailsResource;
use App\Http\Resources\WebCoursesResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\Setting;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('type', 'course')->orderBy('created_at', 'desc')->get()->map(function ($item) {
           return [
               'value' => $item->slug,
               'title' => $item->title
           ];
        })->prepend([
            'value' => 'all',
            'title' => 'همه دسته‌ها',
        ]);
        $query = Course::with(['lessons','thumbnail', 'categories', 'teacher'])
            ->when($request->filled('category') && $request->category !== 'all', function ($query) use ($request) {
                $query->whereRelation('categories', 'slug', $request->category);
            })
        ->when($request->filled('search'), function ($query) use ($request) {
            $query->where('title', 'like', "%{$request->search}%")
            ->orWhere('description', 'like', "%{$request->search}%");
        })
        ->when($request->filled('sort'), function ($query) use ($request) {
            $query->orderBy('published_at', $request->sort);
        });
        $courses = WebCoursesResource::collection($query->orderBy('published_at', 'desc')->paginate(env('COURSES_PER_PAGE')));
        $stats = Setting::getValue('statCourses');
        $stats['courses'] = number_format(@$stats['courses']);
        $stats['ratings'] = number_format(@$stats['ratings'],1);
        $stats['students'] = number_format(@$stats['students']);
        $stats['seasons'] = number_format(@$stats['seasons']);
        return inertia('Web/Courses/Index', compact('categories', 'courses', 'stats'));
    }

    public function show(Request $request, Course $course)
    {
        $courseRequest = $course;
        $requirements = WebCoursesResource::collection($course->requirements);
        $similarCourses = Course::whereHas('categories', function ($query) use ($course) {
            $query->whereIn('id', $course->categories->pluck('id'));
        })
            ->where('id', '!=', $course->id) // حذف خود دوره
            ->take(5)
            ->get();
        $related = WebCoursesResource::collection($similarCourses);
        $course = WebCourseDetailsResource::make($course);
        $user = auth()->user();
        $isEnrolled = false;
        if($user) $isEnrolled = $user->isEnrolledIn($courseRequest);
        $pageTitle = $courseRequest->title;

        /*$comments = Comment::whereNull('parent_id')
        ->with(['children' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();*/
        return inertia('Web/Courses/Show', compact('course','requirements', 'related', 'isEnrolled', 'pageTitle','user'));
    }

    public function download($filename)
    {
        $remoteUrl = video_upload_path("{$filename}");

        // مرحله 1: دریافت هدرها (برای گرفتن Content-Length)
        $chHead = curl_init($remoteUrl);
        curl_setopt_array($chHead, [
            CURLOPT_NOBODY => true, // فقط هدرها
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $headerData = curl_exec($chHead);
        curl_close($chHead);

        $contentLength = null;
        if (preg_match('/Content-Length:\s*(\d+)/i', $headerData, $matches)) {
            $contentLength = (int)$matches[1];
        }

        // مرحله 2: استریم خود فایل
        $response = new StreamedResponse(function () use ($remoteUrl) {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $remoteUrl,
                CURLOPT_RETURNTRANSFER => false,
                CURLOPT_HEADER => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_FILE => fopen('php://output', 'wb'),
            ]);
            curl_exec($ch);
            curl_close($ch);
        });

        // مرحله 3: ست کردن هدرهای دامنه دانلود
        $response->headers->set('Content-Type', 'video/mp4');
        $response->headers->set('Content-Disposition', "attachment; filename=\"{$filename}\"");

        // این خط باعث میشه سایز فایل برای مرورگر قابل شناسایی بشه 🎯
        if ($contentLength) {
            $response->headers->set('Content-Length', $contentLength);
        }

        return $response;
    }

    public function enroll(Course $course, Request $request)
    {
        $user = auth()->user();
        if(!$user){
            return redirectMessage('error', 'ابتدا وارد سایت شوید', redirect: route('panel.login', ['redirect' => url()->previous()]));
        }

        if (!$user->isEnrolledIn($course)) {
            $user->enrolledCourses()->attach($course, [
                'enrolled_at' => now(),
                'progress' => 0,
            ]);
            return redirectMessage('success', 'عضویت با موفقیت انجام شد');
        }
        else{
            return redirectMessage('error', 'شما قبلا عضو دوره شده‌اید');
        }

    }

    public function LessonCompleted(CourseLesson $lesson, Request $request)
    {
        $user = auth()->user();
        if(!$user){
            return redirectMessage('error', 'ابتدا وارد سایت شوید');
        }
        $completion = $lesson->completions()->exists();
        if(!$completion){
            $completed = $lesson->completions()->create([
                'course_id' => $lesson->course->id,
                'user_id' => $user->id,
                'completed_at' => now(),
                'progress' => 100,
                'status' => 'completed'
            ]);
            if($completed){
                return redirectMessage('success', ' این درس را با موفقیت به پایان رسانده‌اید.');
            }
            else{
                return redirectMessage('error', 'وضعیت تکمیل این درس در سیستم ذخیره نشد.');
            }
        }
        else{
            return redirectMessage('error', 'پخش این درس را پیش‌تر به پایان رسانده‌اید.');
        }
    }

    public function LessonQuizStore(CourseLesson $lesson, Request $request)
    {
        $user = auth()->user();
        if(!$user){
            return redirectMessage('error', 'ابتدا وارد سایت شوید');
        }
        $questions = $lesson->quiz->questions;
        if(array_diff($questions->pluck('id')->toArray(),array_keys($request->selectedAnswers))){
            return redirectMessage('error','لطفا همه سوال ها را پاسخ دهید');
        }
        foreach ($request->selectedAnswers as $questionId => $answerId){
            $lesson->quiz->quizCompletions()->create([
                'user_id' => $user->id,
                'course_id' => $lesson->course->id,
                'question_id' => $questionId,
                'question_option_id' => $answerId,
            ]);
        }
        return redirectMessage('success','آزمون با موفقیت ثبت شد');
    }
}
