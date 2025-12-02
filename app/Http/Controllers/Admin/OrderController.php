<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminOrderDetailsResource;
use App\Http\Resources\AdminOrdersResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()->with('user');

        if($request->filled('status')){
            $query->where('status', $request->status);
        }

        if($request->filled('search')){
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // جستجو داخل خود جدول orders
                $q->where('reference_id', 'like', "%{$search}%")

                    // جستجو داخل user
                    ->orWhereHas('user', function($userQuery) use ($search) {
                        $userQuery->where(function($u) use ($search) {
                            $u->where('firstname', 'like', "%{$search}%")
                                ->orWhere('lastname', 'like', "%{$search}%")
                                ->orWhere('mobile', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                    });
            });
        }

        $orders = AdminOrdersResource::collection($query->orderBy('created_at', 'desc')->paginate(env('PER_PAGE')));
        $status = enumFormated(OrderStatusEnum::cases());
        return Inertia::render('Admin/Orders/List', compact('orders', 'status'));
    }

    public function edit(Order $order)
    {
        $order = AdminOrderDetailsResource::make($order);
        $status = enumFormated(OrderStatusEnum::cases());
        return Inertia::render('Admin/Orders/Edit', compact('order', 'status'));
    }

    public function update(Request $request, Order $order)
    {
        $user = auth()->user();
        if($order->status !== $request->status){
            $oldStatus = OrderStatusEnum::fromKey($order->status)->value;
            $newStatus = OrderStatusEnum::fromKey($request->status)->value;
            $order->update(['status' => $request->status]);
            $order->notes()->create([
                'message' => "وضعیت از `{$oldStatus}` به `{$newStatus}` تغییر کرد",
                'creator_id' => $user->id,
            ]);
        }
        if($request->filled('note')){
            $order->notes()->create([
                'message' => $request->note,
                'creator_id' => $user->id,
            ]);
        }
        return redirectMessage('success','با موفقیت انجام شد');
    }
}
