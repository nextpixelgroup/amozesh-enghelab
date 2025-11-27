<?php
namespace App\Services;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Model;

class CartService
{
    public function add(Cart $cart, Model $product, int $qty = 1)
    {
        $item = $cart->items()
            ->where('item_id', $product->id)
            ->where('item_type', get_class($product))
            ->first();

        if ($item) {
            $item->qty = $qty;
            $item->save();
            return $item;
        }

        return $cart->items()->create([
            'item_id'   => $product->id,
            'item_type' => get_class($product),
            'qty'  => $qty,
        ]);
    }
}
