<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Jobs\SendOtpJob;
use App\Models\OTP;
use App\Models\Restriction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function index()
    {
        if(auth()->check()){
            return redirect()->route('panel.profile.index');
        }
        return Inertia::render('Panel/Auth/Index');
    }

    public function login(Request $request)
    {
        if(auth()->check()){
            return redirect()->route('panel.profile.index');
        }
        return Inertia::render('Panel/Auth/Login');
    }

    public function sendCode(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'mobile' => ['required', 'regex:/^09\d{9}$/'],
                ],
                [
                    'mobile.required' => 'وارد کردن شماره همراه الزامی است.',
                    'mobile.regex' => 'شماره همراه باید با ۰۹ شروع شود و ۱۱ رقم باشد.',
                ]
            );

            if ($validator->fails()) {
                return redirectMessage('error', $validator->errors()->first());
            }

            $mobile = $request->mobile;
            $maxAttempts = 5;
            $banDays = 1;

            $otp = OTP::where('login', $mobile)->first();
            $user = User::where('mobile',$mobile)->first();

            // بررسی مسدود بودن به خاطر تلاش ناموفق
            if ($user && $otp && $otp->attempts >= $maxAttempts) {
                // چون ممکنه کاربر لاگین نکرده باشه، مسدودیت بر اساس موبایل میشه نه user_id
                (new Restriction)->temporaryBanUser($user->id, $banDays, "{$maxAttempts} تلاش برای ورود ناموفق.");
                return redirectMessage('error', "{$maxAttempts} تلاش ناموفق. دسترسی شما {$banDays} روز مسدود شد.");
            }

            if (!$otp) {
                $otp = new OTP();
                $otp->login = $mobile;
            }

            $code = 12345;
            if(env('APP_ENV') == 'production'){
                $code = rand(10000, 99999);
            }
            $otp->code = $code;
            $otp->attempts = 0;
            $otp->expires_at = now()->addMinutes(5);
            $otp->save();
            if(env('APP_ENV') == 'production'){
                SendOtpJob::dispatch($mobile,$code);
            }

            return redirectMessage('success', 'کد تأیید ارسال شد.');

        } catch (Exception $e) {
            $error = log_error($e);
            return redirectMessage('error', "خطایی پیش آمد.(کد خطا:$error->id)");
        }
    }


    public function verifyCode(Request $request)
    {
        parse_str(parse_url(url()->previous(), PHP_URL_QUERY), $queryArray);
        $redirect = null;
        if(isset($queryArray['redirect'])){
            $redirect = $queryArray['redirect'];
        }
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'mobile' => ['required', 'regex:/^09\d{9}$/'],
                    'code' => ['required', 'digits:5'],
                ],
                [
                    'mobile.required' => 'وارد کردن شماره همراه الزامی است.',
                    'mobile.regex' => 'شماره همراه باید با ۰۹ شروع شود و ۱۱ رقم باشد.',
                    'code.required' => 'کد تأیید را وارد کنید.',
                    'code.digits' => 'کد تأیید باید دقیقا ۵ رقم باشد.',
                ]
            );

            if ($validator->fails()) {
                return redirectMessage('error', $validator->errors()->first());
            }

            $mobile = $request->mobile;
            $code = $request->code;
            $maxAttempts = 5;
            $banDays = 1;

            $otp = OTP::where('login', $mobile)->first();
            $user = User::where('mobile',$mobile)->first();

            if (!$otp) {
                return redirectMessage('error', 'کد یافت نشد. ابتدا باید درخواست ارسال کد بدهید.');
            }

            // بررسی انقضا
            if ($otp->expires_at->isPast()) {
                return redirectMessage('error', 'کد منقضی شده. لطفاً دوباره درخواست دهید.');
            }

            // بررسی تعداد تلاش‌ها
            if ($user && $otp->attempts >= $maxAttempts) {
                (new Restriction)->temporaryBanUser($mobile, $banDays, "{$maxAttempts} تلاش ناموفق.");
                return redirectMessage('error', "حساب به مدت {$banDays} روز مسدود شد.");
            }

            // بررسی صحت کد
            if ($otp->code != $code) {
                $otp->increment('attempts');
                return redirectMessage('error', 'کد وارد شده نادرست است.');
            }

            // ✅ کد درست است → پاک کردن رکورد OTP
            $otp->delete();

            // اگر کاربر وجود ندارد → ایجاد کن
            $user = User::where('mobile', $mobile)->first();
            if (!$user) {
                $user = User::create([
                    'mobile' => $mobile,
                    'password' => bcrypt(Str::random(50)), // رمز موقت
                ]);
            }

            // لاگین کردن کاربر
            Auth::login($user);
            if(!$redirect){
                $redirect = route('panel.profile.index');
            }
            return redirectMessage('success', 'ورود با موفقیت انجام شد.', redirect: $redirect);

        } catch (Exception $e) {
            $error = log_error($e);
            return redirectMessage('error', "خطایی پیش آمد(کد خطا: $error->id)");
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirectMessage('success', 'خروج با موفقیت انجام شد', redirect: route('panel.login'));
    }

}
