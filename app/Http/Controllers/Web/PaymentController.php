<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function cart()
    {
        return inertia('Web/Cart');
    }

    public function checkout()
    {
        return inertia('Web/Checkout');
    }

    public function thankYou()
    {
        return inertia('Web/thankYou');
    }
}
