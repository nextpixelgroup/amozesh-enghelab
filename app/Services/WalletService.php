<?php
namespace App\Services;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\Contracts\WalletServiceInterface;

class WalletService implements WalletServiceInterface {
    public function balance(User $user): int {
        $topup = Payment::where('user_id', $user->id)
            ->where('driver', 'wallet_topup')
            ->where('status', 'success')
            ->sum('amount');
        $used = Payment::where('user_id', $user->id)
            ->where('driver', 'wallet')
            ->where('status', 'success')
            ->sum('amount');
        return $topup - $used;
    }
    public function withdraw(User $user, Order $order, int $amount) {
        return Payment::create([
            'user_id'  => $user->id,
            'order_id' => $order->id,
            'amount'   => $amount,
            'driver'   => 'wallet',
            'status'   => 'success',
        ]);
    }
    public function createTopupPayment(User $user, int $amount) {
        return Payment::create([
            'user_id' => $user->id,
            'amount'  => $amount,
            'driver'  => 'wallet_topup',
            'status'  => 'pending',
        ]);
    }
}
