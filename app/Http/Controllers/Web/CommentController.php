<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebCommentResource;
use App\Models\Comment;
use App\Models\Course;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    public function courseComments(Request $request,Course $course)
    {
        $resource = WebCommentResource::collection($course->comments()->where('is_approved',true)->paginate(env('PER_PAGE')));
        $totalAllComments = Comment::where('commentable_id', $course->id)->where('is_approved', true)
            ->where('commentable_type', get_class($course))
            ->count();
        $comments = $resource->response()->getData(true);
        $comments['total_comments_count'] = $totalAllComments;

        return sendJson(data: $comments);
    }

    public function courseStore(Request $request, Course $course)
    {
        $user = auth()->user();
        if(!$user){
            return sendJson('error', 'ابتدا وارد سایت شوید');
        }
        try {
            if ($user) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'body' => 'required|min:5|max:255',
                    ],
                    [
                        'body.required' => 'وارد کردن نظر الزامی می‌باشد.',
                        'body.min' => 'حداقل کاراکتر باید :min باشد',
                        'body.max' => 'حداکثر کاراکتر باید :max باشد',
                    ]
                );


                $args = [
                    'user_id' => $user->id,
                    'body' => trim(preg_replace('/\s+/', ' ', strip_tags($request->body)))
                ];
            } else {

                $validator = Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|min:2|max:40',
                        'email' => 'nullable|email',
                        'body' => 'required|min:5|max:255',
                    ],
                    [
                        'name.required' => 'وارد کردن نام و نام خانوادگی الزامی می‌باشد.',
                        'name.min' => 'حداقل کاراکتر نام باید :min باشد',
                        'name.max' => 'حداکثر کاراکتر نام باید :min باشد',
                        'email.email' => 'ایمیل معتبر وارد نمایید',
                        'body.required' => 'وارد کردن نظر الزامی می‌باشد.',
                        'body.min' => 'حداقل کاراکتر نظر باید :min باشد',
                        'body.max' => 'حداکثر کاراکتر نظر باید :max باشد',
                    ]
                );

                $args = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'body' => trim(preg_replace('/\s+/', ' ', strip_tags($request->body)))
                ];
            }

            if ($validator->fails()) {
                return sendJson('error', $validator->errors()->first());
            }

            $comment = $course->comments()->create($args);
            if($comment){
                return sendJson('success', 'نظر شما پس از تایید منتشر خواهد شد');
            }
        } catch (Exception $e) {
            $error = log_error($e);
            return sendJson('error', "خطایی پیش آمد(کد خطا: $error->id)");
        }
    }

    public function reply(Request $request, Comment $comment)
    {
        sleep(4);
        $user = auth()->user();
        if(!$user){
            return sendJson('error', 'ابتدا وارد سایت شوید');
        }
        elseif ($comment->depth >= 1) {
            return sendJson('error', 'امکان پاسخ بیشتر از دو سطح وجود ندارد.');
        }

        $reply = $comment->addReply([
            'depth'       => 1,
            'body'        => $request->body,
            'is_approved' => false,
        ],$user );


        return sendJson(message: 'نظر شما پس از تایید منتشر خواهد شد');
    }
}
