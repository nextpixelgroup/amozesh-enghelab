<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ContactStoreRequest;
use App\Http\Resources\WebContactResource;
use App\Models\Contact;
use App\Models\Page;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contact = WebContactResource::make(Page::where('type', 'contact')->first());
        return inertia('Web/Contact', compact('contact'));
    }

    public function store(ContactStoreRequest $request)
    {
        try {
            // 1) یافتن آخرین پیام کاربر با ایمیل یا موبایل
            $lastMessage = Contact::where(function ($q) use ($request) {
                $q->where('email', $request->email)
                    ->orWhere('mobile', $request->mobile);
            })
                ->latest()
                ->first();

            // 2) اگر پیام قبلی وجود دارد و کمتر از ۱ ساعت گذشته است → خطا
            if ($lastMessage && $lastMessage->created_at->diffInMinutes(now()) < 30) {

                $remaining = round(30 - $lastMessage->created_at->diffInMinutes(now()));

                return redirectMessage('error',
                    "شما قبلاً پیام ارسال کرده‌اید. لطفاً $remaining دقیقه دیگر دوباره تلاش کنید."
                );
            }

            // 3) اگر مجاز باشد → ذخیره پیام جدید
            Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'message' => strip_tags($request->input('message')),
            ]);

            return redirectMessage('success', 'پیام با موفقیت ارسال شد. کارشناسان ما به زودی با شما تماس خواهند گرفت');
        }
        catch (\Exception $e) {
            $error = log_error($e);
            return redirectMessage('error', "خطایی پیش آمد(کدخطا:$error->id)");
        }
    }
}
