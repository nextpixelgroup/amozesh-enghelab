<?php

use App\Jobs\CancelExpiredOrders;
use App\Jobs\StatCourseDurationsJob;
use App\Jobs\StatCourseRatingsJob;
use App\Jobs\StatCourseSeasonsJob;
use App\Jobs\StatCourseStudentsJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new StatCourseDurationsJob())->everyFifteenMinutes();
Schedule::job(new StatCourseRatingsJob())->everyFifteenMinutes();
Schedule::job(new StatCourseStudentsJob())->everyFifteenMinutes();
Schedule::job(new StatCourseSeasonsJob())->everyFifteenMinutes();
Schedule::job(new CancelExpiredOrders())->everyMinute();
