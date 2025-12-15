<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Kavenegar\KavenegarApi;
use Throwable;

class SendOtpJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $mobile, public int $code)
    {
        $this->onConnection('redis')->onQueue('otp');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $kavenegar = new KavenegarApi(config('kavenegar.apikey'));
            $kavenegar->VerifyLookup($this->mobile, $this->code, '', '', "userotp", "sms");
        } catch (Throwable $e) {

            report($e); // Horizon اینو می‌گیرد

            throw $e; // مهم: تا job fail شود
        }

    }
}
