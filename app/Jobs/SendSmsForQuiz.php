<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Kavenegar\KavenegarApi;
use Throwable;

class SendSmsForQuiz implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user, public string $uuid, public string $pattern)
    {
        $this->onConnection('redis');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $user = $this->user;
            $kavenegar = new KavenegarApi(config('kavenegar.apikey'));
            $kavenegar->VerifyLookup($user->mobile, $user->firstname, $this->uuid, '', $this->pattern, "sms");
        } catch (Throwable $e) {

            report($e); // Horizon اینو می‌گیرد

            throw $e; // مهم: تا job fail شود
        }
    }
}
