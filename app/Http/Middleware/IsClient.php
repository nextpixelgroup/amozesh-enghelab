<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user) {
            return redirectMessage('error','لطفا وارد شوید', redirect: route('panel.index'));
        }
        elseif ($user && $user->isRestricted()){
            Auth::logout();
            return redirectMessage('error', 'حساب شما مسدود شده است لطفا با پشتیبانی تماس بگیرید.', redirect: route('panel.index'));
        }

        /*$allowedRoles = ['client','super-admin'];

        if (!$user->hasAnyRole($allowedRoles)) {
            $message = 'دسترسی غیر مجاز';
            if($user){
                auth()->logout();
            }
            if($request->isJson()){
                return response()->json(['message' => $message], 403);
            }
            else{
                return redirectMessage('error',message: $message, redirect: route('panel.index'));
            }
        }*/

        return $next($request);
    }
}
