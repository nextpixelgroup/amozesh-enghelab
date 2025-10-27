<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>به زودی!</title>
    <script src="{{ asset('tailwind.js') }}"></script>

    <!-- فونت فارسی Vazir -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v30.1.0/dist/font-face.css">
    <style>
        body { font-family: 'Vazir', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen text-gray-200">

<div class="bg-gray-800 rounded-xl shadow-xl p-12 text-center max-w-lg w-full">
    <h1 class="text-5xl font-black mb-4 flex flex-row-reverse items-center justify-center gap-3">
        <span>به زودی</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z" />
        </svg>
    </h1>
    <p class="text-gray-400 mb-8 text-lg">سایت ما در حال آماده‌سازی است. لطفاً منتظر بمانید.</p>

    <!-- Countdown -->
    <div id="countdown" class="flex justify-center text-4xl font-semibold mb-8" dir="ltr">
        <div class="flex flex-col items-center mx-3">
            <span id="days">00</span>
            <span class="text-sm font-normal text-gray-400">روز</span>
        </div>
        <div class="flex flex-col items-center mx-3">
            <span id="hours">00</span>
            <span class="text-sm font-normal text-gray-400">ساعت</span>
        </div>
        <div class="flex flex-col items-center mx-3">
            <span id="minutes">00</span>
            <span class="text-sm font-normal text-gray-400">دقیقه</span>
        </div>
        <div class="flex flex-col items-center mx-3">
            <span id="seconds">00</span>
            <span class="text-sm font-normal text-gray-400">ثانیه</span>
        </div>
    </div>

</div>

<script>
    const launchDate = new Date("2025-01-11T00:00:00").getTime();

    const countdown = setInterval(() => {
        const now = new Date().getTime();
        const distance = launchDate - now;

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("days").innerText = days.toString().padStart(2,'0');
        document.getElementById("hours").innerText = hours.toString().padStart(2,'0');
        document.getElementById("minutes").innerText = minutes.toString().padStart(2,'0');
        document.getElementById("seconds").innerText = seconds.toString().padStart(2,'0');

        if (distance < 0) {
            clearInterval(countdown);
            document.getElementById("countdown").innerHTML = "🎉 سایت راه‌اندازی شد!";
        }
    }, 1000);
</script>

</body>
</html>
