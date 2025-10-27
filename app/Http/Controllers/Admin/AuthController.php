<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AuthLoginRequest;
use App\Http\Resources\AdminResource;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthController extends Controller
{

    public function index()
    {
        return Inertia::render('Admin/Auth/Login');
    }


    public function store(AuthLoginRequest $request): RedirectResponse
    {
        try {
            $credentials = $request->only('username', 'password');

            if (!Auth::attempt($credentials)) {
                throw ValidationException::withMessages([
                    'username' => 'نام کاربری یا رمز عبور نادرست است.',
                ]);
            }

            // Successful login, regenerate session and redirect
            $request->session()->regenerate();
            return redirect()
                ->intended(route('admin.users.index'))
                ->with('success', 'با موفقیت وارد شدید');
        }
        catch (Exception $error){
            return back()->withErrors([
                'username' => $error->getMessage(),
            ])->onlyInput('username');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirectMessage('success','خروج با موفقیت انجام شد',null,route('admin.login'));

    }

}
