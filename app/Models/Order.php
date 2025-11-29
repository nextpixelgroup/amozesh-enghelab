<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'original_total', 'discount_total', 'shipping_cost', 'total',
        'status', 'reference_id',
        'gateway', 'paid_at', 'canceled_at', 'failed_at',
        'meta'
    ];

    protected $casts = [
        'meta' => 'array',
        'paid_at' => 'datetime',
        'canceled_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function notes()
    {
        return $this->hasMany(OrderNote::class);
    }
}
