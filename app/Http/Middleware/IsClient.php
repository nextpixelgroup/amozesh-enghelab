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
            return response()->json(['message' => 'Unauthenticated.'], 401);
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
                return redirectMessage('error',message: $message, redirect: route('panel.login'));
            }
        }*/

        return $next($request);
    }
}
