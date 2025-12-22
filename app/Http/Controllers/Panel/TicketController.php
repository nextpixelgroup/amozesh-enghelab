<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        return inertia('Panel/Tickets/Index');
    }

    public function store(Request $request)
    {

        $user = auth()->user();

        $lastMessage = Ticket::where('user_id', $user->id)->latest()
            ->first();

        // 2) اگر پیام قبلی وجود دارد و کمتر از ۱ ساعت گذشته است → خطا
        if ($lastMessage && $lastMessage->created_at->diffInMinutes(now()) < 5) {

            $remaining = round(5 - $lastMessage->created_at->diffInMinutes(now()));

            return redirectMessage('error',
                "شما قبلاً پیام ارسال کرده‌اید. لطفاً $remaining دقیقه دیگر دوباره تلاش کنید."
            );
        }

        $request->validate([
            'subject' => ['required', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:2000'],
        ],
        [
            'subject.required' => 'موضوع اجباری می‌باشد',
            'subject.max' => 'حداکثر کاراکتر موضوع باید :max باشد',
            'message.required' => 'متن پیام اجباری می‌باشد',
            'message.max' => 'حداکثر کاراکتر متن باید :max باشد',
        ]);
        try {


            $ticket = $user->tickets()->create([
                'subject' => $request->subject,
                'message' => $request->message,
            ]);
            return redirectMessage('success','درخواست شما با موفقیت ثبت شد، کارشناسان به زودی با شما در تماس خواهند بود.');

        }
        catch (\Exception $exception){
            $error = log_error($exception);
            return redirectMessage('error', "خطایی پیش آمد(کدخطا:$error->id)");
        }
    }
}
