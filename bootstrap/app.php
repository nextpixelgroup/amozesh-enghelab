<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsClient;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'video/chunk',
            'video/complete',
        ]);
        $middleware->alias([
            'admin' => IsAdmin::class,
            'client' => IsClient::class,
        ]);
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            if (str_starts_with($request->path(), 'admin')) {
                return route('admin.login');
            } elseif (str_starts_with($request->path(), 'panel')) {
                return route('panel.login'); // Make sure this route exists
            }
            return route('panel.login'); // Fallback to default login route
        });


    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            // اگر درخواست از Inertia است، اجازه بده لاراول به‌صورت پیش‌فرض هندل کند
            if ($request->header('X-Inertia')) {
                if($e instanceof AccessDeniedHttpException){
                    return Inertia::render('Web/404'); // کاربر دسترسی ندارد
                }
                elseif ($e instanceof MethodNotAllowedHttpException){
                    return Inertia::render('Web/404'); // متد ارسالی وجود ندارد
                }
                elseif ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
                    return Inertia::render('Web/404'); // وجود ندارد
                }
                return null; // لاراول خودش redirect/back با errors برمی‌گرداند
            }
            elseif($request->header('Content-Type') == 'application/json') {
               //dd(get_class($e));
                //dd($request->url());
                if($e instanceof AuthenticationException){
                    return sendJson('error', 'ابتدا وارد سایت شوید');
                }
                elseif($e instanceof ValidationException){
                    return sendJson('error', collect($e->errors())->first()[0]);
                }
                elseif($e instanceof AccessDeniedHttpException){
                    return sendJson('error', 'چنین درخواستی یافت نشد. (1001)');
                }
                elseif ($e instanceof AuthorizationException) {
                    return sendJson('error', 'شما دسترسی ندارید. (1002)');
                } //Model and Route not found error
                elseif ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
                    return sendJson('error', 'چنین درخواستی یافت نشد. (1002)', []);
                }
                elseif ($e instanceof RouteNotFoundException) {
                    return sendJson('error', 'چنین درخواستی یافت نشد. (1003)', []);
                } elseif ($e instanceof MethodNotAllowedHttpException) {
                    return sendJson('error', 'دسترسی به این متد امکان پذیر نمی باشد.');
                }
                elseif ($e instanceof  ThrottleRequestsException){
                    return sendJson('error', 'تعداد درخواست های شما بیش از حد مجاز می باشد.');
                }
            }
            else{
                $path = $request->path();
                if($e instanceof MethodNotAllowedHttpException){
                    // برای مسیرهای ادمین
                    if (str_starts_with($path, 'admin')) {
                        return Inertia::render('Admin/405');
                    }

                    // برای مسیرهای پنل
                    if (str_starts_with($path, 'panel')) {
                        return response()->view('errors.405', [], 405);
                    }

                    // برای سایر موارد
                    return response()->view('errors.405', [], 405);
                }
                elseif ($e instanceof NotFoundHttpException) {

                    // برای مسیرهای ادمین
                    if (str_starts_with($path, 'admin')) {
                        return Inertia::render('Admin/404');
                    }

                    // برای مسیرهای پنل
                    if (str_starts_with($path, 'panel')) {
                        return response()->view('errors.404', [], 404);
                    }

                    // برای سایر موارد
                    return Inertia::render('Web/404');
                }
                elseif($e instanceof AccessDeniedHttpException){
                    return Inertia::render('Web/404');
                }
            }
        });
    })->create();
