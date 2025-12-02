<?php
namespace App\Services;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use App\Services\Contracts\OrderServiceInterface;
use Illuminate\Support\Collection;

class OrderService implements OrderServiceInterface {
    public function createOrder(User $user, Collection $cartItems, string $gateway, array $userData, int $shippingCost = 0): Order
    {
        $originalTotal = 0;
        $total = 0;
        $discount = 0;
        foreach ($cartItems as $cartItem) {
            $price = $cartItem->item->price;
            $salePrice = $cartItem->item->special_price ?: $price;

            $originalTotal += ($price * $cartItem->qty);
            $total += ($salePrice * $cartItem->qty);

            if ($salePrice < $price) {
                $discount += ($price - $salePrice) * $cartItem->qty;
            }
        }

        $order = Order::create([
            'user_id' => $user->id,
            'original_total' => $originalTotal,
            'discount_total' => $discount,
            'shipping_cost'  => $shippingCost,
            'total'          => $total+$shippingCost,
            'status'         => 'pending',
            'gateway'        => $gateway,
            'fullname'       => $userData['firstname'].' '.$userData['lastname'],
            'address'        => $userData['address'],
            'mobile'         => $userData['mobile'],
            'postal_code'    => $userData['postal_code'],
            'email'          => $userData['email'],
            'user_note'      => $userData['user_note'],
        ]);

        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $cartItem->item_id,
                'item_type' => $cartItem->item_type,
                'qty' => $cartItem->qty,
                'title' => $cartItem->item->title,
                'price' => $cartItem->item->price,
                'discount' => $cartItem->item->special_price ? $cartItem->item->price-$cartItem->item->special_price : 0,
                'sale_price' => $cartItem->item->special_price ?: $cartItem->item->price,
            ]);
        }
        $this->finalizeOrderBeforePayment($order);

        return $order;
    }
    public function markOrderPaid(Order $order): void {
        $order->update(['status' => 'paid']);
    }

    public function markOrderCanceled(Order $order): void {
        $order->update(['status' => 'canceled']);
    }
    public function addPaymentRecord(User $user, Order $order, int $amount, string $driver, string $status = 'pending') {
        return Payment::create([
            'user_id'  => $user->id,
            'order_id' => $order->id,
            'amount'   => $amount,
            'driver'   => $driver,
            'status'   => $status,
        ]);
    }


    public function checkFinalizeOrder(Cart $cart, Collection $items): array
    {
        if($cart->items->count() !== $items->count()){
            return ['status' => false, 'message' => 'سبد خرید شما تغییر کرده است. لطفا سبد خرید را چک نمایید و دوباره اقدام به پرداخت نمایید.'];
        }
        foreach ($cart->items as $item) {

            // فقط اگر آیتم از نوع Book بود
            if ($item->item_type === Book::class) {

                $book = $item->item;
                if($book->is_stock == false){
                    return ['status' => false, 'message' => " ناموجود شد'$book->title'"];
                }
                if($book->stock !== null && $book->stock < $item->qty){
                    return ['status' => false, 'message' => "تعداد موجودی $book->title فقط $book->stock عدد است و به صورت خودکار از سبد خرید شما حذف می‌گردد. لطفا دوباره سفارش دهید."];
                }
            }
        }
        return ['status' => true];
    }

    public function finalizeOrderBeforePayment(Order $order): void
    {
        foreach ($order->items as $item) {

            // فقط اگر آیتم از نوع Book بود
            if ($item->item_type === Book::class) {

                $book = $item->item;
                // شرایط کم کردن موجودی
                if ($book->is_stock == true &&
                    $book->stock !== null &&
                    $book->stock > 0) {

                    // کاهش به اندازه qty سفارش
                    $newStock = $book->stock - $item->qty;

                    // اگر به هر دلیل موجودی کم بود (نباید برسد به این مرحله ولی برای اطمینان)
                    if ($newStock < 0) {
                        $order->note([
                            'message' => "موجودی کتاب با شناسه {$book->id} کافی نمی‌باشد",
                        ]);
                        return;
                    }

                    $book->stock = $newStock;
                    if($newStock == 0){
                        $book->is_stock = false;
                    }
                    $book->save();
                }
            }
        }
    }

}
