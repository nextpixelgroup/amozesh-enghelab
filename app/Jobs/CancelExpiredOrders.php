<?php

namespace App\Jobs;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelExpiredOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


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
        return;
        $threshold = Carbon::now()->subMinutes(60);

        Order::where('status', 'pending')
            ->where('updated_at', '<=', $threshold)
            ->chunkById(100, function ($orders) {
                foreach ($orders as $order) {
                    // اگر لازم است رویداد یا لاگ داشته باشید، اینجا اضافه کنید
                    $order->update([
                        'status' => 'canceled',
                        'canceled_reason' => 'سفارش شما به دلیل عدم تکمیل فرآیند پرداخت در بازه زمانی تعیین‌شده به صورت خودکار لغو شد.',
                    ]);
                }
            });
    }
}
