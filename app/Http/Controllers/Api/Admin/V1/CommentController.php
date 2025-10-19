<?php

namespace App\Http\Controllers\Api\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CommentRequest;
use App\Models\Book;
use App\Models\Comment;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    /**
     * Get all comments for a commentable item (Course or Book)
     *
     * @param string $type
     * @param int $id
     * @return JsonResponse
     */
    public function index(string $type, int $id): JsonResponse
    {
        $model = $this->getCommentableModel($type, $id);

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
        ]);
    }

    /**
     * Store a new comment
     *
     * @param CommentRequest $request
     * @return JsonResponse
     */
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

    /**
     * Reply to a comment
     *
     * @param CommentRequest $request
     * @param Comment $comment
     * @return JsonResponse
     */
    public function reply(CommentRequest $request, Comment $comment): JsonResponse
    {
        if ($comment->depth >= 2) {
            return response()->json([
                'status' => 'error',
                'message' => 'امکان پاسخ بیشتر از دو سطح وجود ندارد.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $reply = $comment->addReply([
            'body' => $request->body,
        ], Auth::user());

        $reply->load('user:id,name,avatar');

        return response()->json([
            'status' => 'success',
            'message' => 'پاسخ شما با موفقیت ثبت شد.',
            'data' => $reply
        ], Response::HTTP_CREATED);
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

    /**
     * Remove the specified comment
     *
     * @param Comment $comment
     * @return JsonResponse
     */
    public function destroy(Comment $comment): JsonResponse
    {
        // Check if the authenticated user is the owner of the comment or an admin
        if ($comment->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'شما اجازه حذف این نظر را ندارید.'
            ], Response::HTTP_FORBIDDEN);
        }

        // Delete all replies first
        $comment->replies()->delete();

        // Then delete the comment
        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'نظر با موفقیت حذف شد.'
        ]);
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
