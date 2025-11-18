<?php

namespace App\Jobs;

use App\Models\CourseStudent;
use App\Models\Setting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class StatCourseStudentsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $Setting = new Setting;
        $setting = $Setting->getValue('statCourses');
        $setting['students'] = CourseStudent::count();
        $Setting->setValue('statCourses', $setting);
    }
}
