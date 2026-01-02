<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getItem(Model $product)
    {
        return $this->items()
            ->where('item_id', $product->id)
            ->where('item_type', get_class($product))
            ->first();
    }
}
