<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
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

        $allowedRoles = User::assessToAdmin();

        // If user is content manager, only allow specific course routes


        if (!$user->hasAnyRole($allowedRoles)) {
            $message = 'دسترسی غیر مجاز';
            if($user){
                auth()->logout();
            }
            if($request->isJson()){
                return response()->json(['message' => $message], 403);
            }
            else{

                return redirectMessage('error',message: $message, redirect: route('admin.login'));
            }
        }
        if ($user && $user->roleObject['value'] == 'content-manager') {
            $allowedCourseRoutes = [
                'admin.courses.index',
                'admin.courses.create',
                'admin.courses.store',
                'admin.courses.edit',
                'admin.courses.update',
                'admin.courses.categories.index',
                'admin.courses.categories.store',
                'admin.courses.categories.update',
                'admin.courses.categories.destroy',
                'admin.profile',
                'admin.logout',
            ];

            if (!in_array($request->route()->getName(), $allowedCourseRoutes)) {
                $message = 'دسترسی غیر مجاز';
                if($request->isJson()){
                    return response()->json(['message' => $message], 403);
                }
                return redirectMessage('error', message: $message, redirect: route('admin.courses.index'));
            }
            return $next($request);
        }
        return $next($request);
    }
}
