<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;

class ComingSoonMiddleware
{
    /**
     * اگر کاربر لاگین بود و نقش user نداشت -> سایت را نشان بده
     * در غیر این صورت (مهمان یا نقش user) در همه صفحات به‌جز لاگین ادمین -> ComingSoon
     */
    public function handle(Request $request, Closure $next): Response
    {
        // مسیرهایی که همیشه باید باز باشند (حتی برای مهمان / نقش user)
        // فقط لاگین ادمین استثناست طبق درخواست شما
        if ($this->isAdminLoginRequest($request)) {
            return $next($request);
        }

        // برای health check لاراول
        if ($request->is('up') || $request->is('up/*')) {
            return $next($request);
        }

        $user = $request->user();

        // اگر کاربر لاگین است و نقش user را ندارد -> اجازه نمایش سایت
        // hasRole('user') از spatie/laravel-permission
        if ($user && !$user->hasRole('user')) {
            return $next($request);
        }

        // در غیر این صورت (مهمان یا نقش user) -> فقط ComingSoon
        // برای درخواست‌های JSON / API
        if ($request->expectsJson() || $request->isJson()) {
            return response()->json([
                'message' => 'سایت در حال آماده‌سازی است. به‌زودی برمی‌گردیم.',
            ], 503);
        }

        // برای درخواست‌های Inertia و معمولی -> صفحه ComingSoon
        // از Inertia::render استفاده می‌کنیم تا هم در اولین لود و هم در ناوبری SPA درست کار کند
        return Inertia::render('Web/ComingSoon')->toResponse($request)->setStatusCode(503);
    }

    private function isAdminLoginRequest(Request $request): bool
    {
        // پوشش هم path و هم نام روت
        // GET /admin/login  -> admin.login
        // POST /admin/login -> admin.login.store
        if ($request->routeIs('admin.login') || $request->routeIs('admin.login.store')) {
            return true;
        }

        // fallback بر اساس URL در صورتی که route هنوز resolve نشده باشد (مثلاً 404)
        $path = trim($request->path(), '/');
        if ($path === 'admin/login') {
            return true;
        }

        // اگر به هر دلیلی با slash اضافی آمد
        if ($request->is('admin/login')) {
            return true;
        }

        return false;
    }
}
