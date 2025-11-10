<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function cart()
    {
        return inertia('Web/Payment/Cart');
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
