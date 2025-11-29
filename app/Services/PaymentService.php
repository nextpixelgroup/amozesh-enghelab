<?php
namespace App\Services;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\Contracts\PaymentServiceInterface;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment as ShetabitPay;

class PaymentService implements PaymentServiceInterface {

    public function createPayment(User $user, ?Order $order, string $driver, int $amount): Payment
    {
        return Payment::create([
            'user_id'   => $user->id,
            'order_id'  => $order?->id,
            'driver'    => $driver,      // wallet, wallet_topup, zarinpal
            'amount'    => $amount,
            'status'    => 'pending',
        ]);
    }

    public function requestGateway(Payment $payment, string $driver, string $callbackUrl): string
    {
        return ShetabitPay::callbackUrl($callbackUrl)->purchase(
            (new Invoice)->amount($payment->amount),
            function ($driver,$transactionId) use ($payment) {
                $payment->update([
                    'transaction_id' => $transactionId,
                    'status' => 'initiated'
                ]);
            }
        )->pay()->toJson();
    }

    public function verify(Payment $payment): Payment {
        $receipt = ShetabitPay::amount($payment->amount)->transactionId($payment->transaction_id)->verify();

        if($receipt) {
            $payment->update([
                'status' => 'paid',
                'reference_id' => $receipt->getReferenceId(),
            ]);
        }
        return $payment;
    }

    public function markPaymentFailed(Payment $payment): void
    {
        $payment->update([
            'status' => 'failed',
        ]);
    }
}
