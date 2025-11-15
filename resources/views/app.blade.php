<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @routes
    {{-- تزریق شرطی CSS بر اساس نام مسیر --}}
    @if (Route::is('admin.*'))
        {{-- فرض می‌کنیم فایل کامپایل شده admin.css و web.css در مسیر build/assets هستند --}}
        @vite(['resources/css/admin.css'])
    @else
        @vite(['resources/css/web.css'])
    @endif

    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body dir="rtl">
@inertia
</body>
</html>
