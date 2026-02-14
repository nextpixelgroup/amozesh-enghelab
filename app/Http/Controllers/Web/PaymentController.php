<?php

namespace App\Http\Controllers\Web;

use App\Enums\PaymentDriverEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\PayRequest;
use App\Http\Resources\CartItemResource;
use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\OrderNote;
use App\Models\Payment;
use App\Rules\IsMobile;
use App\Services\CartService;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\Contracts\PaymentServiceInterface;
use App\Services\Contracts\WalletServiceInterface;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\WalletService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function cart(Request $request)
    {
        $user = auth()->user();
        $items = [];
        if ($user) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
            $items = $cart->items()->with('item')->orderBy('created_at', 'desc')->get();
        }
        $items = CartItemResource::collection($items);
        $pageTitle = 'سبد خرید';
        return inertia('Web/Payment/Cart', compact('user', 'items', 'pageTitle'));
    }

    public function store(Request $request,Book $book)
    {
        try {
            $user = auth()->user();
            if(!$user){
                return redirectMessage('error', 'ابتدا وارد سایت شوید', redirect: route('panel.index', ['redirect' => url()->previous()]));
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

    public function update(Request $request, CartItem $cartItem)
    {
        Gate::authorize('update', $cartItem);
        try {
            $cartItem->update(['qty' => $request->qty]);
            return redirectMessage('success', "سبد خرید ویرایش شد");
        } catch (\Exception $exception) {
            $error = log_error($exception);
            return redirectMessage('success', "خطای پیش آمد(کد خطا: $error->id)");
        }
    }

    public function destroy(CartItem $cartItem)
    {
        Gate::authorize('delete', $cartItem);
        try {
            $cartItem->delete();
            return redirectMessage('success', "سبد خرید ویرایش شد");
        } catch (\Exception $exception) {
            $error = log_error($exception);
            return redirectMessage('success', "خطای پیش آمد(کد خطا: $error->id)");
        }
    }

    public function checkout()
    {
        $user = auth()->user();
        $items = [];
        if ($user) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
            $items = $cart->items()->with('item')->get();
        }
        $items = CartItemResource::collection($items);
        $shipping_price = $this->shipping_price();
        $pageTitle = 'جزئیات پرداخت';
        return inertia('Web/Payment/Checkout', compact('user', 'items', 'shipping_price', 'pageTitle'));
    }

    public function pay(PayRequest $request)
    { // ارسال به درگاه
        $user = $request->user();
        try {
            $orderService = new OrderService;
            $paymentService = new PaymentService;
            $walletService = new WalletService;
            $gateway = $request->input('gateway'); // zarinpal | wallet | mixed


            $cart = Cart::with('items')
                ->where('user_id', $user->id)
                ->get();
            if ($cart->isEmpty() || $cart->first()->items->count() === 0) {
                return redirectMessage('error','سبد خرید شما خالی می‌باشد');
            }
            elseif (!$gateway){
                return redirectMessage('error','نوع پرداخت مشخص نشده است');
            }
            $check = $orderService->checkFinalizeOrder($cart->first(),collect($request->items));
            if(!$check['status']){
                return redirectMessage('error',$check['message']);
            }

            $userData = [
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'address' => $request->input('address'),
                'postal_code' => $request->input('postal_code'),
                'email' => $request->input('email'),
            ];
            $user->update();
            $userData['user_note'] = $request->input('note');
            $userData['mobile'] = $user->mobile;
            $order = $orderService->createOrder($user, $cart->first()->items,$gateway,$userData,$this->shipping_price());

            $cart->first()->delete();

            if ($gateway === 'wallet') {
                // زمان فعال سازی کیف پول باید این کدها بازبینی شوند
                /* $balance = $walletService->balance($user);
                 if ($balance < $order->total) {
                     return redirectMessage('error','اعتبار کیف پول شما کافی نمی‌باشد');
                 }
                 $paymentService->createPayment($user, $order, 'wallet', $order->total);
                 $orderService->markOrderPaid($order);
                 //$cartItems->each->delete();
                 return response()->json(['status' => 'paid']);*/
            }
            elseif($gateway === 'mixed'){
                // زمان فعال سازی این بخش یعنی ترکیب کیف پول و باقی مانده توسط درگاه باید این کدها بازبینی شوند
                /*$balance = $walletService->balance($user);
                $useWallet = min($balance, $order->total);
                $needGateway = $order->total - $useWallet;
                if ($useWallet > 0) {
                    $paymentService->createPayment($user, $order, 'wallet', $useWallet);
                }
                $gatewayPayment = $paymentService->createPayment($user, $order, $gateway, $needGateway);
                $url = $paymentService->requestGateway($gatewayPayment);
                return response()->json(['redirect' => $url]);*/
            }
            elseif($gateway === 'sadad'){ // درگاه بانک ملی
                $payment = $paymentService->createPayment($user, $order, $gateway, $order->total);
                $data = $paymentService->requestGateway($payment, $gateway, route('web.payment.callback', ['driver' => $gateway]));

                $data = json_decode($data,true);
                return redirectMessage('success','',[],route('web.paying',$data));
            }
        }
        catch (Exception $e){
            $error = log_error($e);
            return redirectMessage('error', "خطایی پیش آمد(کدخطا: $error->id)");
        }


    }

    public function topup(Request $request, PaymentServiceInterface $paymentService)
    { // شارژ کیف پول
        $user = $request->user();
        $amount = (int) $request->input('amount');
        $payment = $paymentService->createPayment($user, null, 'wallet_topup', $amount);
        $url = $paymentService->requestGateway($payment);
        return response()->json(['redirect' => $url]);
    }

    public function paying(Request $request)
    {
        return inertia('Web/Payment/Paying', [
            'action'      => $request->input('action'),
            'inputs'      => $request->input('inputs'),
            'method'      => $request->input('method'),
        ]);
    }

    public function callback(Request $request)
    {
        $orderService = new OrderService;
        $paymentService = new PaymentService;
        $payment = Payment::where('transaction_id', $request->input('transactionId'))->first();
        if(!$payment){
            $data = [
                'driver'        => '',
                'transactionId' => '',
                'status'        => 'failed',
                'message'       => 'تراکنش موردنظر یافت نشد'
            ];
        }
        elseif($payment->status == 'paid'){
            $data = [
                'driver'        => PaymentDriverEnum::fromKey($payment->driver),
                'transactionId' => $payment->transaction_id,
                'status'        => 'success',
                'message'       => 'این تراکنش قبلا پرداخت شده است'
            ];
        }
        elseif(in_array($payment->status,['failed','refunded'])){
            $data = [
                'driver'        => '',
                'transactionId' => '',
                'status'        => 'failed',
                'message'       => 'تراکنش یافت نشد'
            ];
        }
        else {
            if($request->filled('cancel') && $request->cancel == true){
                $orderService->markOrderCanceled($payment->order);
                $paymentService->markPaymentFailed($payment);
            }
            else {
                $paymentService->verify($payment);
                if ($payment->order_id) {
                    $orderService->markOrderPaid($payment->order);
                }
            }
            $driver = $request->query('driver');
            $transactionId = $request->query('transactionId');
            $isCancelled = $request->boolean('cancel');

            $data = [
                'driver' => PaymentDriverEnum::fromKey($driver),
                'transactionId' => $transactionId,
                'status' => $isCancelled ? 'failed' : 'success',
                'message' => $isCancelled ? 'پرداخت توسط کاربر لغو شد.' : 'پرداخت با موفقیت انجام شد.',
            ];
        }
        $data['pageTitle'] = 'رسید پرداخت';
        return inertia('Web/Payment/Callback',$data);
    }

    public function shipping_price()
    {
        return 90000;
    }

    public function cartCount(Request $request)
    {
        $count = auth()->check() ? auth()->user()->cart_items_count : 0;
        return response()->json(['count' => $count]);
    }

}
