<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if($user->hasRole('user')) {
                // چک کردن که آیا فیلدهای لازم پر شده‌اند
                if (!$user->firstname || !$user->lastname || !$user->national_code) {
                    return redirectMessage('error', 'لطفا نام، نام خانوادگی و کدملی خود را وارد نمایید', [], route('panel.profile.index'));
                }
            }

        }
        return $next($request);

    }
}
