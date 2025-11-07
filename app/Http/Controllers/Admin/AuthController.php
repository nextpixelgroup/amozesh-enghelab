<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AuthLoginRequest;
use App\Http\Resources\AdminResource;
use App\Models\Restriction;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthController extends Controller
{

    public function index()
    {
        if (auth()->check()) {
            return redirect()->route('admin.courses.index');
        }

        return Inertia::render('Admin/Auth/Login');
    }


    public function store(AuthLoginRequest $request)
    {
        try {

            $user = User::where('username', $request->username)->first();
            if($user) {
                if (!Hash::check($request->password, $user->password)) {
                    throw ValidationException::withMessages([
                        'username' => 'نام کاربری یا رمز عبور نادرست است.',
                    ]);
                }

                if ($user->isRestricted()) {
                    return back()->withErrors(['mobile' => 'حساب کاربری شما مسدود شده است.'])->onlyInput('mobile');
                }

                if (!collect(User::assessToAdmin())->contains($user->roles->first()->name)) {

                    (new Restriction)->banUser(auth()->user()->id, 'تلاش برای ورود به پنل مدیریت');
                    return back()->withErrors([
                        'mobile' => 'شما مجوز دسترسی به پنل مدیریت را ندارید.',
                    ])->onlyInput('mobile');
                }
                Auth::login($user, true);
                return redirectMessage('success', 'با موفقیت وارد شدید', redirect: route('admin.courses.index'));
            }
            else{
                return redirectMessage('error', 'نام کاربری یا رمز عبور نادرست است.');
            }

        } catch (Exception $error) {
            /* return back()->withErrors([
                 'username' => $error->getMessage(),
             ])->onlyInput('username');*/

            return redirectMessage('error', $error->getMessage());
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirectMessage('success', 'خروج با موفقیت انجام شد', redirect: route('admin.login'));

    }

    public function profile()
    {
        $user = auth()->user();
        return Inertia::render('Admin/Auth/Profile', compact('user'));
    }

}
