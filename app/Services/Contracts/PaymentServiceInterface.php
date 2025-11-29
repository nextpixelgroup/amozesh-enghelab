<?php
namespace App\Services\Contracts;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;

interface PaymentServiceInterface {
    public function createPayment(User $user, ?Order $order, string $driver, int $amount): Payment;
    public function requestGateway(Payment $payment, string $driver, string $callbackUrl): string;
    public function verify(Payment $payment): Payment;

    public function markPaymentFailed(Payment $payment): void;
}
