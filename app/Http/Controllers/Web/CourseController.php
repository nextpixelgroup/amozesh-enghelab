<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebCommentResource;
use App\Http\Resources\WebCourseDetailsResource;
use App\Http\Resources\WebCoursesResource;
use App\Jobs\SendSmsForQuiz;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CourseStudent;
use App\Models\EnrollPath;
use App\Models\Path;
use App\Models\PathItem;
use App\Models\Setting;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
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
            ->where('status', 'publish')
            ->when($request->filled('category') && $request->category !== 'all', function ($query) use ($request) {
                $query->whereRelation('categories', 'slug', $request->category);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('title', 'like', "%{$request->search}%")
                        ->orWhere('description', 'like', "%{$request->search}%")
                        ->orWhere('summary', 'like', "%{$request->search}%");
                });
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
        $pageTitle = 'دوره‌ها';
        return inertia('Web/Courses/Index', compact('categories', 'courses', 'stats', 'pageTitle'));
    }

    public function show(Request $request, Course $course)
    {
        Gate::authorize('view', $course);

        $user = auth()->user();
        $enrollPath = null;
        // چک میکنیم که این دوره در مسیر هست یا نه
        $pathItem = PathItem::where('course_id', $course->id)->first();
        if($user && $pathItem) {
            // چک میکنیم که آیا کاربر در مسیر مورد نظر عضو هست یا نه
            $enrollPath = EnrollPath::where('path_id', $pathItem->path_id)->where('user_id', $user->id)->exists();
            if($user?->account_type === 'user' && $enrollPath === false){
                // در اینجا مشخص میکنیم اگر کاربر معمولی بود و مسیر رو ثبت نام نکرده مجاز هست که بتونه هرجور دلش بخواد دوره هارو ببینه
                $enrollPath = null;
            }
        }
        if($enrollPath === true || $user?->account_type === 'student') {

            // همه دوره های مسیر موردنظر رو میگیریم
            $pathItems = PathItem::where('path_id', $pathItem->path_id)->get();

            // اگر دوره در مسیر قرار داشت
            if($pathItem){

                // اگر در مسیر ثبت نام کرده بود
                if($enrollPath === true){
                    // گرفتن دوره‌هایی که کاربر قبلاً در مسیر مورد نظر گذرانده
                    $completedCourses = CourseStudent::where('user_id',$user->id)
                        ->whereIn('course_id',$pathItems->pluck('course_id'))
                        ->where('has_passed', true)
                        ->pluck('course_id');
                    // بررسی اینکه آیا کاربر قبلاً دوره‌های قبلی را گذرانده
                    $previousCourses = $pathItems->where('order', '<', $pathItem->order);

                    foreach ($previousCourses as $prev) {
                        if (!$completedCourses->contains($prev->course_id)) {
                            // اگر یکی از دوره‌های قبلی گذرانده نشده
                            $enrollPath = false;
                            break;
                        }
                    }
                }
            }
        }

        $course->update(['views' => $course->views + 1]);
        $courseRequest = $course;
        $requirements = WebCoursesResource::collection($course->requirements()->where('status','publish')->get());
        $similarCourses = Course::whereHas('categories', function ($query) use ($course) {
            $query->whereIn('id', $course->categories->pluck('id'));
        })
            ->where('id', '!=', $course->id)
            ->where('status', 'publish')
            ->take(5)
            ->get();
        $related = WebCoursesResource::collection($similarCourses);
        $course = WebCourseDetailsResource::make($course);
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
        return inertia('Web/Courses/Show', compact('course','requirements', 'related', 'isEnrolled', 'pageTitle','user', 'enrollPath'));
    }

    public function rating(Request $request, Course $course)
    {
        $user = auth()->user();
        if(!$user){
            return sendJson('error', 'ابتدا وارد سایت شوید');
        }
        elseif ($request->rate < 1 || $request->rate > 5) {
            return sendJson('error', 'امتیاز باید بین ۱ تا ۵ باشد');
        }
        if($user->hasRatedCourse($course)){
            $findRate = $course->ratings()->where('user_id',$user->id)->first();
            $diffInMinutes = now()->diffInMinutes($findRate->updated_at,true);
            if($diffInMinutes <= 1){
                return sendJson('error', 'شما فقط هر یک دقیقه یک‌بار می‌توانید امتیاز را تغییر دهید');
            }
            $findRate->update(['rate' => $request->rate]);
        }
        else{
            $course->ratings()->create([
                'user_id' => $user->id,
                'rate' => $request->rate
            ]);
        }
        return sendJson('success', 'امتیاز با موفقیت ثبت شد', ['rate' => number_format(Course::find($course->id)->rate,1)]);
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
            return redirectMessage('error', 'ابتدا وارد سایت شوید', redirect: route('panel.index', ['redirect' => url()->previous()]));
        }

        if (!$user->isEnrolledIn($course)) {
            $parms = [
                'enrolled_at' => now(),
                'progress' => 0,
            ];
            if($user->account_type === 'student'){
                $parms['has_requested_certificate'] = true;
            }
            $user->enrolledCourses()->attach($course, $parms);

            return redirectMessage('success', 'عضویت با موفقیت انجام شد');
        }
        else{
            return redirectMessage('error', 'شما قبلا عضو دوره شده‌اید');
        }

    }

    public function hasCertificate(Course $course, Request $request)
    {
        $user = $request->user();
        if($user->account_type === 'student' && $request->response === false){
            return redirectMessage('error', 'گواهینامه برای دانشجو اجباری می‌باشد.');
        }
        $courseStudent = CourseStudent::where('user_id',$user->id)->where('course_id', $course->id)->first();
        $courseStudent->update(['has_requested_certificate' => $request->response]);
        return redirectMessage('success', 'درخواست شما با موفقیت ثبت شد.');
    }

    public function LessonCompleted(CourseLesson $lesson, Request $request)
    {
        $user = auth()->user();
        if(!$user){
            return redirectMessage('error', 'ابتدا وارد سایت شوید');
        }

        // Check if already completed
        $completion = $lesson->completions()->where('user_id', $user->id)->exists();
        if($completion){
            return redirectMessage('error', 'پخش این درس را پیش‌تر به پایان رسانده‌اید.');
        }

        // Mark lesson as completed
        $completed = $lesson->completions()->create([
            'course_id' => $lesson->course->id,
            'user_id' => $user->id,
            'completed_at' => now(),
            'progress' => 100,
            'status' => 'completed'
        ]);

        if(!$completed){
            return redirectMessage('error', 'وضعیت تکمیل این درس در سیستم ذخیره نشد.');
        }

        // Check if all lessons in the course are completed
        $course = $lesson->course;

        // Count only active lessons in active seasons
        $totalLessons = $course->seasons()
            ->where('is_active', true)
            ->withCount(['lessons' => function($query) {
                $query->where('is_active', true);
            }])
            ->get()
            ->sum('lessons_count');

        $completedLessons = $user->lessonCompletions()
            ->where('course_id', $course->id)
            ->whereHas('lesson', function($query) {
                $query->where('is_active', true);
            })
            ->count();

        // If all lessons are completed, update CourseStudent record
        if($totalLessons > 0 && $completedLessons >= $totalLessons) {
            $courseStudent = CourseStudent::where('course_id', $course->id)
                ->where('user_id', $user->id)
                ->first();

            if($courseStudent) {
                $courseStudent->update([
                    'completed_at' => now(),
                    'has_passed' => true,
                    'progress' => 100
                ]);
            }
            if($courseStudent->has_requested_certificate === true && $course->quiz && $course->quiz->is_active && $course->quiz->quizCompletions->isEmpty()){
                $uuid = (string) Str::uuid();
                $video = Video::create([
                    'uuid'      => $uuid,
                    'user_id'   => $user->id,
                    'course_id' => $course->id,
                    'quiz_id'   => $course->quiz->id,
                    'status'    => 'pending',
                ]);
                SendSmsForQuiz::dispatch($user,$uuid, 'finalQuiz');
            }
        }



        return redirectMessage('success', 'این درس را با موفقیت به پایان رسانده‌اید.');
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
