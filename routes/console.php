<?php

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

Schedule::job(new StatCourseDurationsJob())->everyMinute();
Schedule::job(new StatCourseRatingsJob())->everyMinute();
Schedule::job(new StatCourseStudentsJob())->everyMinute();
Schedule::job(new StatCourseSeasonsJob())->everyMinute();
