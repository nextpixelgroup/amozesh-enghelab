<?php
namespace App\Services\Contracts;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;

interface OrderServiceInterface {
    public function createOrder(User $user, Collection $cartItems, string $gateway, array $userData, int $shippingCost): Order;

    public function markOrderPaid(Order $order): void;
    public function markOrderCanceled(Order $order): void;
    public function addPaymentRecord(User $user, Order $order, int $amount, string $driver, string $status = 'pending');

    public function checkFinalizeOrder(Cart $cart, Collection $items): array;
    public function finalizeOrderBeforePayment(Order $order): void;
}
