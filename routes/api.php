<?php

use App\Http\Controllers\Api\Admin\V1\CommentController;
use Illuminate\Support\Facades\Route;

require_once 'api/admin.php';
require_once 'api/panel.php';

// Public routes (no authentication required)
Route::get('{type}/{id}/comments', [CommentController::class, 'index']);

// Protected routes (authentication required)
Route::middleware('auth:api')->group(function () {
    // Store a new comment
    Route::post('comments', [CommentController::class, 'store']);

    // Reply to a comment
    Route::post('comments/{comment}/reply', [CommentController::class, 'reply']);

    // Update a comment
    Route::put('comments/{comment}', [CommentController::class, 'update']);

    // Delete a comment
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
});

