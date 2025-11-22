<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CommentRequest;
use App\Http\Resources\AdminCommentResource;
use App\Models\Book;
use App\Models\Comment;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function index()
    {
        $comments = Comment::with('user')
        ->orderBy('created_at', 'desc')
        ->paginate(env('PER_PAGE'));
        $comments = AdminCommentResource::collection($comments);

        return Inertia::render('Admin/Comments/List', compact('comments'));
        /*$model = $this->getCommentableModel($type, $id);

        if (!$model) {
            return response()->json([
                'status' => 'error',
                'message' => 'نوع کامنت نامعتبر است.'
            ], Response::HTTP_NOT_FOUND);
        }

        $comments = $model->comments()
            ->with(['user' => function($query) {
                $query->select('id', 'name', 'avatar');
            }])
            ->with(['replies' => function($query) {
                $query->with(['user' => function($q) {
                    $q->select('id', 'name', 'avatar');
                }]);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $comments
        ]);*/
    }

    public function approve(Comment $comment)
    {
        $comment->update([
            'is_approved' => true
        ]);
        return redirectMessage('success', 'نظر با موفقیت تایید شد');
    }
    public function store(CommentRequest $request): JsonResponse
    {
        $model = $this->getCommentableModel($request->commentable_type, $request->commentable_id);

        if (!$model) {
            return response()->json([
                'status' => 'error',
                'message' => 'نوع کامنت نامعتبر است.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $comment = $model->comments()->create([
            'body' => $request->body,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id ?? null,
            'depth' => $request->parent_id ? 1 : 0,
            'is_approved' => true // Set to false if you want to moderate comments
        ]);

        $comment->load('user:id,name,avatar');

        return response()->json([
            'status' => 'success',
            'message' => 'نظر شما با موفقیت ثبت شد و پس از تایید نمایش داده می‌شود.',
            'data' => $comment
        ], Response::HTTP_CREATED);
    }


    public function reply(Request $request, Comment $comment)
    {
        if ($comment->depth >= 1) {
            return redirectMessage('error', 'امکان پاسخ بیشتر از دو سطح وجود ندارد.');
        }

        $user = auth()->user();
        $reply = $comment->addReply([
            'depth' => 1,
            'body' => $request->body,
        ],$user );


        return redirectMessage('success', 'پاسخ شما با موفقیت ثبت شد.');
    }

    /**
     * Update the specified comment
     *
     * @param CommentRequest $request
     * @param Comment $comment
     * @return JsonResponse
     */
    public function update(CommentRequest $request, Comment $comment): JsonResponse
    {
        // Check if the authenticated user is the owner of the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'شما اجازه ویرایش این نظر را ندارید.'
            ], Response::HTTP_FORBIDDEN);
        }

        $comment->update([
            'body' => $request->body,
            'is_approved' => false // Set to false for moderation after edit
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'نظر با موفقیت به روزرسانی شد.',
            'data' => $comment
        ]);
    }

    public function destroy(Comment $comment)
    {

        $comment->delete();

        return redirectMessage('success', 'نظر با موفقیت حذف شد');
    }

    /**
     * Get the commentable model instance
     *
     * @param string $type
     * @param int $id
     * @return mixed
     */
    private function getCommentableModel(string $type, int $id)
    {
        return match ($type) {
            'course' => Course::find($id),
            'book' => Book::find($id),
            default => null,
        };
    }
}
