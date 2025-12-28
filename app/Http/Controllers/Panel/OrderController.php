<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Resources\PanelOrdersResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = $user->orders()->with('items')->orderBy('id', 'desc')->get();
        $orders = PanelOrdersResource::collection($query);
        return inertia('Panel/Orders/Index', compact('orders'));
    }
}
