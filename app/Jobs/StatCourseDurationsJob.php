<?php

namespace App\Jobs;

use App\Models\Course;
use App\Models\Setting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class StatCourseDurationsJob implements ShouldQueue
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
        $setting['courses'] = Course::count();
        $Setting->setValue('statCourses', $setting);
    }
}
