<?php
namespace App\Services\Contracts;
use App\Models\Order;
use App\Models\User;
interface WalletServiceInterface {
    public function balance(User $user): int;
    public function withdraw(User $user, Order $order, int $amount);
    public function createTopupPayment(User $user, int $amount);
}
