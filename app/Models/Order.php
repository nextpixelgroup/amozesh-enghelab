<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'original_total', 'discount_total', 'shipping_cost', 'total',
        'fullname', 'address', 'mobile', 'email', 'postal_code', 'user_note',
        'status', 'reference_id',
        'gateway', 'paid_at', 'canceled_at', 'failed_at',
        'meta','canceled_reason'
    ];

    protected $casts = [
        'meta' => 'array',
        'paid_at' => 'datetime',
        'canceled_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

    public function statusObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $status = OrderStatusEnum::fromKey($attributes['status'])->value;
                return [
                    'value' => $attributes['status'],
                    'title' => $status,
                ];
            }
        );
    }

    protected function createdAtObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $value = $attributes['created_at'];
                $label = verta()->instance($value)->format('Y/m/d H:i');
                return ['value' => $value, 'title' => $label];
            }
        );
    }
}
