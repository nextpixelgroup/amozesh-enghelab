<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\V1\Admin\ContactMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactRequest;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function store(ContactRequest $request): JsonResponse
    {
        $message = Contact::create($request->validated());

        // Here you can add email notification logic if needed

        return response()->json([
            'message' => 'پیام شما با موفقیت ارسال شد',
            'data' => $message
        ], 201);
    }

    // Admin only endpoints (protected by auth middleware)
    public function index()
    {
        $messages = Contact::latest()->paginate(15);
        return response()->json($messages);
    }

    public function show(ContactMessage $message)
    {
        // Mark as read when viewed
        if (! $message->isRead()) {
            $message->markAsRead();
        }

        return response()->json($message);
    }
}
