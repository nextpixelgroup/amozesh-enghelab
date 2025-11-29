<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request,Book $book)
    {
        try {
            $user = auth()->user();
            if(!$user){
                return redirectMessage('error', 'ابتدا وارد سایت شوید', redirect: route('panel.login', ['redirect' => url()->previous()]));
            }
            elseif($request->qty > $book->max_order){
                return redirectMessage('error', "سفارش شما بیشتر از $book->max_order عدد می‌باشد.");
            }

            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            (new CartService)->add($cart, $book, $request->qty);

            return redirectMessage('success', "به سبد خرید اضافه شد");
        }
        catch (\Exception $exception){
            $error = log_error($exception);
            return redirectMessage('success', "خطای پیش آمد(کد خطا: $error->id)");
        }
    }

    public function checkout()
    {
        return inertia('Web/Payment/Checkout');
    }

    public function pay()
    {
        return inertia('Web/Payment/Pay');
    }

    public function thankYou()
    {
        return inertia('Web/Payment/thankYou');
    }
}
