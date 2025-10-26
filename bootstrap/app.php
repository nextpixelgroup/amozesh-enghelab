<?php

use App\Http\Middleware\IsAdmin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => IsAdmin::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            if($request->header('Content-Type') == 'application/json') {
               //dd(get_class($e));
                //dd($request->url());
                if($e instanceof AuthenticationException){
                    return sendJson('error', 'unauthenticated');
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
                } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                    return sendJson('error', 'دسترسی به این متد امکان پذیر نمی باشد.');
                }
                elseif ($e instanceof  ThrottleRequestsException){
                    return sendJson('error', 'تعداد درخواست های شما بیش از حد مجاز می باشد.');
                }
            }
        });
    })->create();
