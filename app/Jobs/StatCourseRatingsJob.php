<?php

namespace App\Jobs;

use App\Models\Setting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class StatCourseRatingsJob implements ShouldQueue
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
        $setting['ratings'] = 0;
        $Setting->setValue('statCourses', $setting);
    }
}
