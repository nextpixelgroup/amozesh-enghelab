<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Coming Soon Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, every guest or "user"-role request is intercepted by
    | App\Http\Middleware\ComingSoonMiddleware and answered with the
    | Web/ComingSoon page (HTTP 503). Admins and the admin login routes
    | always keep working.
    |
    | Toggle it from the .env file:
    |
    |   COMING_SOON_ENABLED=true   -> سایت حالت "به‌زودی" می‌شود
    |   COMING_SOON_ENABLED=false  -> سایت کامل در دسترس است
    |
    */

    'enabled' => env('COMING_SOON_ENABLED', false),

];
